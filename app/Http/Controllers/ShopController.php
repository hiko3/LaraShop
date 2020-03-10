<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Cart;
use Illuminate\Support\Facades\Mail;
use App\Mail\Thanks;
use Auth;

class ShopController extends Controller
{
    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $stocks = Stock::paginate(6);
        return view('shop.index', compact('stocks'));
    }

    public function myCart()
    {
        $data = $this->cart->showCart();
        return view('shop.mycart', $data);
    }

    public function addMycart(Request $request)
    {
        $stock_id = $request->stock_id;
        $cart_add_info = $this->cart->addCart($stock_id);
        if ($cart_add_info->wasRecentlyCreated) {
            $message = 'カートに追加しました';
            return redirect()->route('mycart')->with('flash_message', $message);
        } else {
            $message = 'カートに登録済みです';
            return redirect()->route('item.index')->with('flash_message', $message);
        }
    }

    public function destroyCart($id)
    {
        $this->cart->find($id)->delete();
        return redirect()->route('mycart')->with('flash_message', '商品をカート内から削除しました');
    }

    public function checkout(Cart $cart)
    {
        $user = Auth::user();
        $mailData['user'] = $user->name;
        $mailData['checkoutItems'] = $cart->checkoutCart();
        Mail::to($user->email)->send(new Thanks($mailData));
        return view('shop.checkout');
    }
}
