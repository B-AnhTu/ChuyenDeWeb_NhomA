<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Rules\NoSpace;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function showCheckoutForm()
    {
        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', Auth::id())
            ->with('cartProducts.product')
            ->first();

        if (!$cart || $cart->cartProducts->isEmpty()) {
            return redirect()->route('cart.view')
                ->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Tính tổng tiền
        $total = $cart->cartProducts->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        return view('checkout', compact('cart', 'total', 'user'));
    }


    public function processCheckout(Request $request)
    {
        // Validate đầu vào
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/', new NoSpecialCharacters, new NoSpace],
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,banking',
            'note' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Lấy giỏ hàng
            $cart = Cart::where('user_id', Auth::id())
                ->with('cartProducts.product')
                ->first();

            if (!$cart || $cart->cartProducts->isEmpty()) {
                throw new \Exception('Giỏ hàng trống');
            }

            // Tính tổng tiền
            $total = $cart->cartProducts->sum(function ($item) {
                return optional($item->product)->price * $item->quantity;
            });

            // Tạo đơn hàng mới
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'shipping_name' => $validated['shipping_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'note' => $validated['note'] ?? null,
                'status' => 'pending'
            ]);
            foreach ($cart->cartProducts as $item) {
                // Tạo chi tiết đơn hàng
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // Giảm số lượng tồn kho và tăng số lượng đã bán
                $product = $item->product;
                $product->decrement('stock_quantity', $item->quantity);
                $product->increment('sold_quantity', $item->quantity);
            }

            // Xóa giỏ hàng
            $cart->cartProducts()->delete(); // Xóa các sản phẩm trong giỏ hàng
            $cart->delete(); // Xóa giỏ hàng

            DB::commit();

            return redirect()->route('cart.view')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function showTrackingForm()
    {
        return view('order.tracking-form');
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.my-orders', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function orderDetail($id)
    {
        $order = Order::with(['orderDetails.product'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('order.detail', compact('order'));
    }

    // Tra cứu đơn hàng bằng mã đơn và email
    public function trackOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|numeric',
            'email' => 'required|email'
        ]);

        $order = Order::with(['orderDetails.product'])
            ->where('id', $validated['order_id'])
            ->where('shipping_email', $validated['email'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng với thông tin đã nhập');
        }

        return view('order.tracking-result', compact('order'));
    }

    // Hủy đơn hàng
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Cập nhật trạng thái đơn hàng
            $order->status = 'cancelled';
            $order->save();

            // Hoàn lại số lượng tồn kho
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                $product->increment('stock_quantity', $detail->quantity);
                $product->decrement('sold_quantity', $detail->quantity);
            }

            DB::commit();
            return back()->with('success', 'Đơn hàng đã được hủy thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng');
        }
    }

    // Xác nhận đã nhận hàng
    public function confirmReceived($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'shipping')
            ->firstOrFail();

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Cảm ơn bạn đã xác nhận nhận hàng');
    }
}
