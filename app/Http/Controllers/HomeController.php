<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

use App\Models\User;

use App\Models\Cart;

use App\Models\Order;



use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        $user = User::where('usertype','user')->get()->count();

        $product = Product::all()->count();

        $order = Order::all()->count();

        $deliverd = Order::where('status','delivered')->get()->count();

        return view('admin.index',compact('user','product','order','deliverd'));
    }

    public function home(){

        $product = Product::all();

        if(Auth::id()){

            $user = Auth::user();

            $userid = $user->id;

            $count = Cart::where('user_id',$userid)->count();

        }
        else{
            $count = '';
        }




        return view('home.index', compact('product','count'));
    }

    public function login_home(){

        $product = Product::all();

        if(Auth::id()){

            $user = Auth::user();

            $userid = $user->id;

            $count = Cart::where('user_id',$userid)->count();

        }
        else{
            $count = '';
        }

        return view('home.index', compact('product','count'));
    }

    public function product_details($id){

        $data = Product::find($id);

        if(Auth::id()){

            $user = Auth::user();

            $userid = $user->id;

            $count = Cart::where('user_id',$userid)->count();

        }
        else{
            $count = '';
        }

        return view('home.product_details', compact('data','count'));
    }

    public function add_cart($id){

        $product_id = $id;

        $user = Auth::user();

        $user_id = $user->id;

        $data = new Cart;

        $data->user_id = $user_id;

        $data->product_id = $product_id;

        $data->save();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Added to the Cart Successfully');


        return redirect()->back();

    }
    public function generateQrCode()
    {
        // Generar un código QR y mostrarlo en una vista
        $qrCode = QrCode::size(200)->generate('QR');

        return view('qr-code', compact('qrCode'));
    }



    public function mycart(){

        if (Auth::id()) {
            $user = Auth::user();
            $userid = $user->id;
            $count = Cart::where('user_id', $userid)->count();
            $cart = Cart::where('user_id', $userid)->get(); // Cambié $data a $cart
        } else {
            $count = 0;
            $cart = collect(); // Si no hay usuario autenticado, la colección está vacía
        }

        return view('home.mycart', compact('count', 'cart'));
    }

    public function delete_cart($id){
        $data = Cart::find($id);
        $data->delete();
        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Deleted from the Cart Successfully');
        return redirect()->back();
    }

    public function comfirm_order(Request $request){

        $name = $request->name;

        $address = $request->address;

        $phone = $request->phone;

        $userid = Auth::user()->id;

        $cart = Cart::where('user_id', $userid)->get();

        foreach($cart as $carts){

            $order = new Order;

            $order->name = $name;

            $order->rec_address = $address;

            $order->phone = $phone;

            $order->user_id = $userid;

            $order->product_id = $carts->product_id;

            $order->save();


        }

        $cart_romove = Cart::where('user_id', $userid)->get();

        foreach($cart_romove as $remove){

                    $data = Cart::find($remove->id);

                    $data->delete();

                }

                toastr()->timeOut(10000)->closeButton()->addSuccess('Order Placed Successfully');

                return redirect()->back();


    }

    public function myorders(){

        $user = Auth::user()->id;

        $count = Cart::where('user_id', $user)->get()->count();

        $order = Order::where('user_id', $user)->get();

        return view('home.order',compact('count','order'));
    }
}
