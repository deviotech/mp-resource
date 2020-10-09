<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;
use Laravel\Cashier\SubscriptionBuilder\RedirectToCheckoutResponse;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\PaymentStatus;
use Log;




class PaymentController extends Controller
{
    public function preparePayment()
{
    $order_id = rand();
    $payment = Mollie::api()->payments->create([
        "amount" => [
            "currency" => "GBP",
            "value" => "10.00" // You must send the correct number of decimals, thus we enforce the use of strings
        ],
        "description" => "Order #12345",
        "redirectUrl" => route('user.order.success'),
        "webhookUrl" => 'https://76046ab0c715.ngrok.io/mp-resource/public/api/webhooks',
        
        "metadata" => [
            "order_id" => $order_id,
        ],
    ]);

    // save order details
    
    // $data = $request->except('token');
    // $data['order_id'] = $order_id;
    // $data['user_id'] = Auth::id();
    // PaymentStatus::create($request->except('token'));

    // redirect customer to Mollie checkout page
    return redirect($payment->getCheckoutUrl(), 303);
}

/**
 * After the customer has completed the transaction,
 * you can fetch, check and process the payment.
 * This logic typically goes into the controller handling the inbound webhook request.
 * See the webhook docs in /docs and on mollie.com for more information.
 */
public function handleWebhookNotification(Request $request) {
    $paymentId = $request->input('id');
    $payment = Mollie::api()->payments->get($paymentId);

    if ($payment->isPaid())
    {
        // PaymentStatus::where('user_id', Auth::id())
        //  ->update(['payment_id' => $paymentId]);
    }
}
    public function orderSuccess(){
     echo "payment _success";   
    }
    public function test_order(){
        
        // return view('test_order');
    }
}
