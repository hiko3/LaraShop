<?php

namespace App\Models;
use Auth;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'stock_id',
        'user_id'
    ];

    
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function showCart()
    {
        $data['myCarts'] = $this->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        $data['count'] = 0;
        $data['sum'] = 0;
        foreach($data['myCarts'] as $myCart) {
            $data['count']++;
            $data['sum'] += $myCart->stock->fee;
        }
        return $data;
    }

    public function addCart($stock_id)
    {
        $user_id = Auth::id();
        $cart_add_info = Cart::firstOrCreate(['stock_id' => $stock_id, 'user_id' => $user_id]);
        return $cart_add_info;
    }

    public function checkoutCart()
    {
        $checkoutItems = $this->where('user_id', Auth::id())->get();
        $this->where('user_id', Auth::id())->delete();
        return $checkoutItems;
    }
}
