<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: white;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #1f2937;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1f2937;
            font-size: 32px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            color: #1f2937;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item label {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 3px;
        }
        .info-item value {
            display: block;
            color: #1f2937;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background: #1f2937;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }
        table tr:last-child td {
            border-bottom: 2px solid #1f2937;
        }
        .totals {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: #1f2937;
        }
        .total-row.grand {
            border-top: 2px solid #1f2937;
            padding-top: 15px;
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
        }
        .total-row.grand .amount {
            color: #2563eb;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #1f2937;
            color: #666;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>JOLITS</h1>
            <p>Online Food Ordering System</p>
            <p>Order Receipt</p>
        </div>

        <!-- Order Information -->
        <div class="section">
            <h2 class="section-title">Order Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Order Number</label>
                    <value>#{{ $order->id }}</value>
                </div>
                <div class="info-item">
                    <label>Order Date</label>
                    <value>{{ $order->created_at->format('M d, Y - h:i A') }}</value>
                </div>
                <div class="info-item">
                    <label>Payment Method</label>
                    <value>{{ ucfirst($order->payment_method) }}</value>
                </div>
                <div class="info-item">
                    <label>Status</label>
                    <value>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </value>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <h2 class="section-title">Customer Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Customer Name</label>
                    <value>{{ $order->delivery_name ?? $order->user->name }}</value>
                </div>
                <div class="info-item">
                    <label>Phone Number</label>
                    <value>{{ $order->delivery_phone ?? $order->user->phone }}</value>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <label>Delivery Address</label>
                    <value>{{ $order->delivery_address }}</value>
                </div>
                @if($order->notes)
                <div class="info-item" style="grid-column: 1 / -1;">
                    <label>Special Instructions</label>
                    <value>{{ $order->notes }}</value>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="section">
            <h2 class="section-title">Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->menuItem->name }}</strong><br>
                            <small style="color: #666;">{{ \Illuminate\Support\Str::limit($item->menuItem->description, 60) }}</small>
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">${{ number_format($item->price, 2) }}</td>
                        <td style="text-align: right;"><strong>${{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Delivery Fee</span>
                <span>${{ number_format($order->delivery_fee ?? 5, 2) }}</span>
            </div>
            <div class="total-row grand">
                <span>Total Amount</span>
                <span class="amount">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your order!</strong></p>
            <p>For any questions, please contact our support team</p>
            <p style="margin-top: 10px;">This is a computer-generated receipt</p>
        </div>

        <!-- Print Button -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;">
                Print Receipt
            </button>
            <button onclick="window.close()" style="background: #1f2937; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; margin-left: 10px;">
                Close
            </button>
        </div>
    </div>
</body>
</html>
