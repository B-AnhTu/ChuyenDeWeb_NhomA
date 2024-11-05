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
            ->with('cartProducts.product') // Lấy sản phẩm thông qua cartProducts
            ->first();

        if (!$cart || $cart->cartProducts->isEmpty()) {
            return redirect()->route('cart.view')
                ->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Tính tổng tiền
        $total = $cart->cartProducts->sum(function ($item) {
            return optional($item->product)->price * $item->quantity; // Sử dụng optional để tránh lỗi null
        });

        return view('checkout', compact('cart', 'total'));
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

            // Tạo chi tiết đơn hàng
            foreach ($cart->cartProducts as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);
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
}
