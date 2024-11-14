<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderDetails.product'])
            ->orderBy('created_at', 'desc');

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Tìm kiếm theo mã đơn hàng hoặc email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('shipping_email', 'like', "%{$search}%")
                    ->orWhere('shipping_phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo khoảng thời gian
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20);
        return view('orders.index', compact('orders'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with(['orderDetails.product', 'user'])
            ->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;

            $order->status = $newStatus;
            $order->save();

            // Xử lý logic khi hủy đơn hàng
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    $product->increment('stock_quantity', $detail->quantity);
                    $product->decrement('sold_quantity', $detail->quantity);
                }
            }

            // Xử lý logic khi khôi phục đơn hàng đã hủy
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    $product->decrement('stock_quantity', $detail->quantity);
                    $product->increment('sold_quantity', $detail->quantity);
                }
            }

            DB::commit();

            // Có thể thêm logic gửi email thông báo cho khách hàng ở đây

            return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Hiển thị trang thống kê đơn hàng
     */
    public function statistics(Request $request)
    {
        // Xử lý khoảng thời gian
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now());

        // Thống kê tổng quan
        $overview = $this->getOrdersOverview($dateFrom, $dateTo);
        
        // Thống kê theo trạng thái
        $statusStats = $this->getOrdersByStatus($dateFrom, $dateTo);
        
        // Thống kê theo ngày
        $dailyStats = $this->getDailyOrderStats($dateFrom, $dateTo);
        
        // Top sản phẩm bán chạy
        $topProducts = $this->getTopSellingProducts($dateFrom, $dateTo);

        return view('orders.statistics', compact(
            'overview',
            'statusStats',
            'dailyStats',
            'topProducts',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Lấy thống kê tổng quan
     */
    private function getOrdersOverview($dateFrom, $dateTo)
    {
        return [
            'total_orders' => Order::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'completed')
                ->sum('total_amount'),
            'average_order_value' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'completed')
                ->avg('total_amount'),
            'total_customers' => Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->distinct('user_id')
                ->count(),
        ];
    }

    /**
     * Lấy thống kê theo trạng thái đơn hàng
     */
    private function getOrdersByStatus($dateFrom, $dateTo)
    {
        return Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as total'), DB::raw('sum(total_amount) as revenue'))
            ->groupBy('status')
            ->get();
    }

    /**
     * Lấy thống kê đơn hàng theo ngày
     */
    private function getDailyOrderStats($dateFrom, $dateTo)
    {
        return Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total_orders'),
                DB::raw('sum(total_amount) as daily_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Lấy danh sách top sản phẩm bán chạy
     */
    private function getTopSellingProducts($dateFrom, $dateTo)
    {
        return DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('product', 'order_details.product_id', '=', 'product.product_id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->where('orders.status', 'completed')
            ->select(
                'product.product_id',
                'product.product_name',
                DB::raw('sum(order_details.quantity) as total_quantity'),
                DB::raw('sum(order_details.quantity * order_details.price) as total_revenue')
            )
            ->groupBy('product.product_id', 'product.product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
    }
}
