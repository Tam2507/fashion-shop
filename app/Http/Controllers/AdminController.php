<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function adminHome()
    {
        return view('admin.home');
    }

    // Dashboard - Thống kê toàn vẹn (Unified Dashboard)
    public function dashboard()
    {
        // Thống kê tổng quan
        $totalOrders = Order::count();
        // Chỉ tính doanh thu từ đơn hàng đã giao
        $totalRevenue = Order::where('status', 'delivered')->sum('total_price');
        $totalProducts = Product::count();
        $totalUsers = User::where('is_admin', false)->count();
        $totalCategories = Category::count();
        
        // Đơn hàng gần đây
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        // Thống kê hôm nay
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayUsers = User::whereDate('created_at', today())->count();
        // Chỉ tính doanh thu từ đơn hàng đã giao hôm nay
        $todayRevenue = Order::where('status', 'delivered')->whereDate('created_at', today())->sum('total_price');

        // Sản phẩm bán chạy (top 5 theo số lượng đã bán)
        $topProducts = \App\Models\OrderItem::with('product.images')
            ->whereHas('order', fn($q) => $q->whereNotIn('status', ['cancelled']))
            ->selectRaw('product_id, SUM(quantity) as total_sold, SUM(quantity * price) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue', 
            'totalProducts',
            'totalUsers',
            'totalCategories',
            'recentOrders',
            'todayOrders',
            'todayUsers',
            'todayRevenue',
            'topProducts'
        ));
    }

    // Dashboard Unified - Quản lý tất cả từ một nơi
    public function dashboardUnified()
    {
        // Lấy dữ liệu sản phẩm
        $products = Product::with('category')->get();
        
        // Lấy dữ liệu danh mục
        $categories = Category::with('products')->get();
        
        // Lấy dữ liệu đơn hàng
        $orders = Order::with('user')->latest()->get();
        
        // Lấy dữ liệu khách hàng (không phải admin)
        $users = User::where('is_admin', false)->with('orders')->get();
        
        // Lấy dữ liệu admin
        $admins = User::where('is_admin', true)->get();

        return view('admin.dashboard-unified', compact(
            'products',
            'categories',
            'orders',
            'users',
            'admins'
        ));
    }

    // Danh sách sản phẩm admin
    public function products()
    {
        $products = Product::with('category')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    // Danh sách danh mục admin
    public function categories()
    {
        $categories = Category::paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    // Danh sách người dùng admin
    public function users(Request $request)
    {
        $search = $request->input('search');

        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Thống kê doanh thu
    public function statistics()
    {
        // Chỉ tính doanh thu từ đơn hàng đã giao
        $totalRevenue = Order::where('status', 'delivered')->sum('total_price');
        $monthlyRevenue = Order::where('status', 'delivered')
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->get();
        $orderStats = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.statistics', compact('totalRevenue', 'monthlyRevenue', 'orderStats'));
    }

    // Xóa người dùng
    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Người dùng đã được xóa');
    }

    // Quản lý tài khoản admin
    public function admins(Request $request)
    {
        $query = User::where('is_admin', true);

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $admins = $query->latest()->paginate(15)->withQueryString();
        return view('admin.admins.index', compact('admins'));
    }

    public function createAdmin()
    {
        return view('admin.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_admin'] = true;

        User::create($validated);
        return redirect()->route('admin.admins.index')->with('success', 'Tài khoản admin mới đã được tạo');
    }

    public function editAdmin($id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validated['password'] ?? false) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);
        return redirect()->route('admin.admins.index')->with('success', 'Tài khoản admin đã được cập nhật');
    }

    public function deleteAdmin($id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);
        
        // Ngăn xóa admin nếu chỉ có 1 admin duy nhất
        if (User::where('is_admin', true)->count() === 1) {
            return redirect()->back()->with('error', 'Không thể xóa admin cuối cùng');
        }

        $admin->delete();
        return redirect()->back()->with('success', 'Tài khoản admin đã được xóa');
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
