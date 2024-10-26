<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\User;
use Illuminate\Http\Request;

class CartProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::with('user', 'cartProducts.product')->paginate(20); // Lấy tất cả giỏ hàng với thông tin người dùng và sản phẩm
        return view('cart.cartAdmin', compact('carts'));
    }


    // public function showCart($id){
    //     $cart_id = Cart::findOrFaile($id);
    //     $user = User::find($cart_id->user_id);
    //     return view('cartAdmin', compact('cart_id', 'user'));
    // }

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
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cart_id)
    {
        // Tìm giỏ hàng theo ID
        $cart = Cart::findOrFail($cart_id);

        // Xóa các bản ghi liên quan trong bảng cart_product
        $cart->cartProducts()->delete();

        // Sau đó xóa giỏ hàng
        $cart->delete();

        // Redirect về trang trước với thông báo thành công
        return redirect()->back()->with('success', 'Cart deleted successfully.');
    }
}
