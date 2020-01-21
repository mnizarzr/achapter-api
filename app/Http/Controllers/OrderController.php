<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class OrderController extends Controller
{

    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('hasJWT');
    }

    public function create($id)
    {
        if (Auth::user()["id"] == $id) {

            $order = new Order;
            $payment = new Payment;

            $count = Order::all()->count();

            $reference = "AC/" . Date::now()->isoFormat("YYYY-MM") . "/" . ($count + 1);

            $paymentCode = random_int(100000000, 999999999);

            $books = $this->request->books;
            $arrBooks = [];
            foreach ($books as $book) {
                $json = json_decode($book, true);
                array_push($arrBooks, new OrderDetail([
                    "book_id" => $json["book_id"],
                    "book_price" => $json["book_price"],
                    "quantity" => $json["quantity"]
                ]));
            }

            $order->reference = $reference;
            $order->user_id = Auth::user()["id"];
            $order->shipper_id = $this->request->shipper_id;
            $order->shipping_address = $this->request->shipping_address;
            $order->completed = 0;
            $order->created_at = Date::now();

            $payment->user_id = Auth::user()["id"];
            $payment->code = $paymentCode;

            $order->save();
            $order->orderDetail()->saveMany($arrBooks);
            $order->payment()->save($payment);

            return $this->responseSuccess(200, "Order queued", ["Payment Code" => $paymentCode]);
        }
        return $this->responseError(401, "ERR_UNAUTHORIZED", "Unauthorized request");
    }
}
