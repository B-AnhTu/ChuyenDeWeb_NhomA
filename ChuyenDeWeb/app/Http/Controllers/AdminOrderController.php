<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

    public function statistics(Request $request)
    {
        // Tổng số đơn hàng và thống kê theo trạng thái
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipping_orders' => Order::where('status', 'shipping')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('total_amount'),
        ];

        // Thống kê doanh thu theo tháng (bao gồm cả năm)
        $monthlyStats = Order::where('status', 'completed')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total_orders, SUM(total_amount) as revenue')
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->get()
            ->map(function ($item) {
                $item->month_name = \Carbon\Carbon::createFromFormat('m', $item->month)->format('F Y');
                return $item;
            });

        return view('orders.statistics', compact('stats', 'monthlyStats'));
    }
}
