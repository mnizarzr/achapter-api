<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class PaymentController extends Controller
{

    function __construct()
    {
        $this->middleware('hasApiKey');
    }

    function index(Request $request)
    {
        $payment = Payment::where('code', $request->code)->update(['completed_at' => Date::now()]);
        if ($payment) return $this->responseSuccess(200, "Payment success", null);
        return $this->responseError(400, "Payment failed", "Payment failed");
    }
}
