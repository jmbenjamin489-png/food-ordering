<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesReport;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::today();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::today();

        // Ensure daily reports exist
        $date = $startDate->copy();
        while ($date->lte($endDate)) {
            SalesReport::generateDailyReport($date);
            $date->addDay();
        }

        $reports = SalesReport::whereBetween('report_date', [$startDate, $endDate])->get();

        // ✅ CORRECT CALCULATIONS
        $totalRevenue = $reports->sum('total_sales');
        $totalOrders = $reports->sum('completed_orders');
        $averageOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $completedOrders = $totalOrders;

        return view('admin.reports.index', compact(
            'reports',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'averageOrder',
            'completedOrders'
        ));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $date = $startDate->copy();
        while ($date->lte($endDate)) {
            SalesReport::generateDailyReport($date);
            $date->addDay();
        }

        return back()->with('success', 'Sales report generated successfully.');
    }

    public function dailyReport(Request $request)
    {
        $requestDate = $request->date ? Carbon::parse($request->date) : Carbon::today();

        SalesReport::generateDailyReport($requestDate);

        return redirect()->route('admin.reports.index', [
            'start_date' => $requestDate->toDateString(),
            'end_date' => $requestDate->toDateString(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::today();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::today();

        $reports = SalesReport::whereBetween('report_date', [$startDate, $endDate])
            ->orderBy('report_date')
            ->get();

        $fileName = 'sales-report-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($reports) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Report Date',
                'Total Orders',
                'Completed Orders',
                'Cancelled Orders',
                'Total Sales',
                'Total Tax',
                'Total Delivery Fees',
            ]);

            foreach ($reports as $report) {
                fputcsv($handle, [
                    $report->report_date?->format('Y-m-d'),
                    $report->total_orders,
                    $report->completed_orders,
                    $report->cancelled_orders,
                    $report->total_sales,
                    $report->total_tax,
                    $report->total_delivery_fees,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
