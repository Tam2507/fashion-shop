<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdmin;

// Public routes
Route::get('/', function () {
    try {
        $latestPosts = \App\Models\Post::published()->ordered()->get();
        return view('home', compact('latestPosts'));
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('home');

// Static pages
Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'create'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{categoryId}', [ProductController::class, 'byCategory'])->name('products.category');

// Blog public
Route::get('/blog', [\App\Http\Controllers\PostController::class, 'publicIndex'])->name('posts.index');

// Search API
Route::get('/api/search', [ProductController::class, 'search'])->name('api.search');

// SePay payment callbacks (public)
Route::get('/payment/success', [\App\Http\Controllers\SePayController::class, 'success'])->name('payment.success');
Route::get('/payment/error', [\App\Http\Controllers\SePayController::class, 'error'])->name('payment.error');
Route::get('/payment/cancel', [\App\Http\Controllers\SePayController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/sepay/ipn', [\App\Http\Controllers\SePayController::class, 'ipn'])->name('payment.sepay.ipn')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Cart
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/buy-now/{productId}', [OrderController::class, 'buyNow'])->name('orders.buy-now');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/confirm-received', [OrderController::class, 'confirmReceived'])->name('orders.confirm-received');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/coupon/apply', [OrderController::class, 'applyCoupon'])->name('coupon.apply');
    Route::get('/payment/sepay/{orderId}', [\App\Http\Controllers\SePayController::class, 'checkout'])->name('payment.sepay');
});



// Wishlist and Reviews (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::delete('/wishlist-clear', [\App\Http\Controllers\WishlistController::class, 'clear'])->name('wishlist.clear');

    Route::post('/products/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/replies', [\App\Http\Controllers\ReviewController::class, 'storeReply'])->name('reviews.replies.store');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
});

// Messages (public and auth)
Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'userIndex'])->name('messages.user.index');
Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
Route::get('/messages/list', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');

// Blog (public) - Only show detail page
Route::get('/blog/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.show');
Route::post('/blog/{post}/comments', [\App\Http\Controllers\PostController::class, 'storeComment'])->name('posts.comments.store');

// Admin routes
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'adminHome'])->name('home');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Products management - Resource routes
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index']);
    
    // Bulk actions and image management
    Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('/products/{product}/images', [ProductController::class, 'manageImages'])->name('products.images');
    Route::post('/products/{product}/images', [ProductController::class, 'uploadImages'])->name('products.images.upload');
    Route::delete('/products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
    
    // Image color assignment
    Route::post('/images/{image}/assign-color', [ProductController::class, 'assignColor'])->name('images.assign-color');
    Route::post('/products/{product}/assign-main-image-color', [ProductController::class, 'assignMainImageColor'])->name('products.assign-main-image-color');
    
    // Variant management
    Route::post('/products/{product}/variants', [ProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::post('/products/{product}/variants/{variant}', [ProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::delete('/products/{product}/variants/{variant}', [ProductController::class, 'destroyVariant'])->name('products.variants.destroy');
    
    // Categories management
    Route::resource('categories', CategoryController::class);
    
    // Banner management
    Route::resource('banners', \App\Http\Controllers\BannerController::class);
    Route::post('/banners/{banner}/toggle-status', [\App\Http\Controllers\BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    
    // Footer settings
    Route::get('/footer-settings', [\App\Http\Controllers\FooterSettingController::class, 'index'])->name('footer-settings.index');
    Route::put('/footer-settings', [\App\Http\Controllers\FooterSettingController::class, 'update'])->name('footer-settings.update');
    
    // Features management
    Route::resource('features', \App\Http\Controllers\FeatureController::class);
    Route::post('/features/{feature}/toggle-status', [\App\Http\Controllers\FeatureController::class, 'toggleStatus'])->name('features.toggle-status');
    
    // Payment methods management
    Route::resource('payment-methods', \App\Http\Controllers\PaymentMethodController::class);
    Route::post('/payment-methods/{paymentMethod}/toggle-status', [\App\Http\Controllers\PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
    
    // Product Sections management
    Route::resource('product-sections', \App\Http\Controllers\ProductSectionController::class);
    Route::post('/product-sections/{productSection}/manage-products', [\App\Http\Controllers\ProductSectionController::class, 'manageProducts'])->name('product-sections.manage-products');
    
    // Orders management
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'adminShow'])->name('orders.show');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{id}/print-shipping', [OrderController::class, 'printShippingLabel'])->name('orders.print-shipping');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    // Settings
    Route::get('/settings/sepay', [\App\Http\Controllers\Admin\SettingsController::class, 'sepaySettings'])->name('settings.sepay');
    Route::post('/settings/sepay', [\App\Http\Controllers\Admin\SettingsController::class, 'updateSepaySettings'])->name('settings.sepay.update');
    
    // Messages
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'adminIndex'])->name('messages.index');
    Route::get('/messages/{id}', [\App\Http\Controllers\MessageController::class, 'adminShow'])->name('messages.show');
    Route::post('/messages/{id}/reply', [\App\Http\Controllers\MessageController::class, 'adminReply'])->name('messages.reply');
    
    // Contacts
    Route::get('/contacts', [\App\Http\Controllers\ContactController::class, 'adminIndex'])->name('contacts.index');
    Route::get('/contacts/{id}', [\App\Http\Controllers\ContactController::class, 'adminShow'])->name('contacts.show');
    Route::post('/contacts/{id}/reply', [\App\Http\Controllers\ContactController::class, 'adminReply'])->name('contacts.reply');
    Route::delete('/contacts/{id}', [\App\Http\Controllers\ContactController::class, 'adminDestroy'])->name('contacts.destroy');
    
    // Contact Info Management
    Route::get('/contact-info/edit', [\App\Http\Controllers\ContactController::class, 'adminEditInfo'])->name('contact-info.edit');
    Route::put('/contact-info', [\App\Http\Controllers\ContactController::class, 'adminUpdateInfo'])->name('contact-info.update');
    
    // About Page Management
    Route::get('/about/edit', [\App\Http\Controllers\AboutController::class, 'adminEdit'])->name('about.edit');
    Route::put('/about', [\App\Http\Controllers\AboutController::class, 'adminUpdate'])->name('about.update');
    Route::delete('/about/image/{imageNumber}', [\App\Http\Controllers\AboutController::class, 'adminDeleteImage'])->name('about.delete-image');
    
    // Posts (Blog) management
    Route::resource('posts', \App\Http\Controllers\PostController::class);
    
    // Coupons management
    Route::resource('coupons', \App\Http\Controllers\CouponController::class);
    Route::post('/coupons/{coupon}/toggle-status', [\App\Http\Controllers\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::post('/coupons/{coupon}/send-notification', [\App\Http\Controllers\CouponController::class, 'sendNotification'])->name('coupons.send-notification');
    
    // Users management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Admin accounts management (tạo/sửa admin từ trang users)
    Route::get('/admins/create', [AdminController::class, 'createAdmin'])->name('admins.create');
    Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::get('/admins/{id}/edit', [AdminController::class, 'editAdmin'])->name('admins.edit');
    Route::put('/admins/{id}', [AdminController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{id}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');
    
    // Statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
});

require __DIR__.'/auth.php';
