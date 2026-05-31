<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // 1. Ô thống kê doanh thu (Chỉ tính các đơn hàng completed)
            $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

            // 2. Ô thống kê sản phẩm
            $totalProducts = Product::count();

            // 3. Ô thống kê danh mục
            $totalCategories = Category::count();

            // 4. Ô thống kê khách hàng
            // Tùy theo logic phân quyền, ở đây lấy những người dùng thông thường
            $totalCustomers = User::where('role', 'user')->count();

            // 5. Biểu đồ đơn hàng đã bán trong 1 tháng
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            
            // Sửa lỗi logic groupBy cho chuẩn SQL Strict Mode
            $ordersChartData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'ASC')
            ->get();

            $orderDates = $ordersChartData->pluck('date')->toArray();
            $orderCounts = $ordersChartData->pluck('count')->toArray();

            // 6. Biểu đồ số lượng tài khoản users đã đăng ký trong 1 tháng
            $usersChartData = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'ASC')
            ->get();

            $userDates = $usersChartData->pluck('date')->toArray();
            $userCounts = $usersChartData->pluck('count')->toArray();

            // 7. 5 đơn hàng mới nhất
            $latestOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

            return view('admin.dashboard', compact(
                'totalRevenue', 
                'totalProducts', 
                'totalCategories', 
                'totalCustomers',
                'orderDates',
                'orderCounts',
                'userDates',
                'userCounts',
                'latestOrders'
            ));

        } catch (\Exception $e) {
            // Nếu bị lỗi (chẳng hạn thiếu cột database, lỗi kết nối, v.v) thì sẽ bắt lỗi tại đây
            // Đẩy thông báo lỗi ra ngoài màn hình cho Admin biết thay vì chết cứng trang
            session()->flash('error', 'Phát hiện lỗi logic hoặc Database khi tải Thống kê: ' . $e->getMessage());
            
            // Trả về trang Dashboard với các dữ liệu trống (0) để giao diện không bị vỡ
            return view('admin.dashboard', [
                'totalRevenue' => 0,
                'totalProducts' => 0,
                'totalCategories' => 0,
                'totalCustomers' => 0,
                'orderDates' => [],
                'orderCounts' => [],
                'userDates' => [],
                'userCounts' => [],
                'latestOrders' => []
            ]);
        }
    }
}
