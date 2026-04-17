    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\Customer\HomeController;
    use App\Http\Controllers\Customer\OrderController;
    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\MenuController as AdminMenuController;
    use App\Http\Controllers\Admin\OrderController as AdminOrderController;
    use App\Http\Controllers\Admin\SalesReportController;
    use App\Http\Controllers\Admin\UserController as AdminUserController;
    use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
    use App\Http\Middleware\AdminMiddleware;

    // Serve files from storage/app/public directory
    Route::get('/storage/{path}', function ($path) {
        $file = storage_path('app/public/' . $path);
        if (!file_exists($file)) {
            abort(404, 'File not found: ' . $file);
        }
        return response()->file($file);
    })->where('path', '.*')->name('storage.serve');

    // Authentication Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Customer Routes (Public & Authenticated)
    Route::get('/', [HomeController::class, 'index'])->name('customer.home');
    Route::get('/menu', [HomeController::class, 'menu'])->name('customer.menu');
    Route::get('/menu/{menuItem}', [HomeController::class, 'show'])->name('customer.menu.show');

    // Customer Authenticated Routes
    Route::middleware(['auth'])->group(function () {
        // Cart Routes
        Route::get('/cart', [OrderController::class, 'cart'])->name('customer.cart');
        Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('customer.cart.add');
        Route::post('/cart/update', [OrderController::class, 'updateCart'])->name('customer.cart.update');
        Route::delete('/cart/remove/{itemId}', [OrderController::class, 'removeFromCart'])->name('customer.cart.remove');

    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('customer.orders.cancel')
        ->middleware('auth');


        // Checkout & Orders
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('customer.checkout');
        Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('customer.order.place');
        Route::get('/order/success/{order}', [OrderController::class, 'orderSuccess'])->name('customer.order-success');

        // Order History
        Route::get('/orders', [OrderController::class, 'orderHistory'])->name('customer.orders');
        Route::get('/orders/{order}', [OrderController::class, 'orderDetails'])->name('customer.order-details');

        // Profile
        Route::get('/profile', [AuthController::class, 'profile'])->name('customer.profile');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('customer.profile.update');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Menu Management
        Route::resource('menu', AdminMenuController::class);
        Route::post('menu/{menuItem}/toggle', [AdminMenuController::class, 'toggleAvailability'])->name('menu.toggle');

        // Categories Management
        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        // Users Management
        Route::resource('users', AdminUserController::class)->except(['show']);

        // Order Management
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('orders/daily-history', [AdminOrderController::class, 'dailyHistory'])->name('orders.daily-history');
        Route::get('orders/{order}/print', [AdminOrderController::class, 'printReceipt'])->name('orders.print');

        // Sales Reports
        Route::get('reports', [SalesReportController::class, 'index'])->name('reports.index');
        Route::get('reports/daily', [SalesReportController::class, 'dailyReport'])->name('reports.daily');
        Route::post('reports/generate', [SalesReportController::class, 'generateReport'])->name('reports.generate');
        Route::get('reports/export', [SalesReportController::class, 'export'])->name('reports.export');
    });
