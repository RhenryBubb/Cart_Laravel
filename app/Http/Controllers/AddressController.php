<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Address;
use App\Order;
use App\Cart;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Validation\Validator;
use Session;
use Auth;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('autorizador');
    }

    public function add(AddressRequest $request)
    {

        $data = $request->all();
        Address::create($data);
        $address = Address::where('user_id', Auth::user()->id)->first();
        $cart = Cart::where('token', Session::get('token'))->first();
        $params = [
            'user_id' => $request->input('user_id'),
            'cart_id' => $cart->id,
            'payment_id' => $request->input('payment_id'),
            'address_id' => $address->id,
            'order_status' => 'waiting',
            'cart_token' => Session::get('token'),
        ];

        Order::create($params);

        return redirect()
        ->route('checkout.carrinho', ['id' => Auth::user()->id]);
    }
}
