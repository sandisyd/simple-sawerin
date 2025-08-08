<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\Donation;
use DB;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoiceItem;

class DonationController extends Controller
{

    public function __construct(){
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }
    public function index($username){
        $user = User::where('username',$username)->firstOrFail();
        return view("donation",compact('user'));
    }

    public function store(Request $request){
        $request->validate([
            'name'=> ['required','string','max:255'],
            'email'=> ['required','string','email', 'max:255'],
            'amount'=> ['required','integer','min:1'],
            'message'=>['required','string'],
        ]);

        DB::beginTransaction();
        try {

            $donation = Donation::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'email' => $request->email,
                'amount' => $request->amount,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            $invoiceItems = new InvoiceItem(
                [
                    'name' => 'Donation',
                    'price' => $request->amount,
                    'quantity' => 1
                ]
            );

            $createInvoice = new CreateInvoiceRequest([
                'external_id' => 'donation-'.$donation->id,
                'payer_email' => $request->email,
                'amount' => $request->amount,
                'items' => [$invoiceItems],
                'invoice_duration' => 24,
                'success_redirect_url' => route('donations.success', ['id' => $donation->id]),
            ]);

            $api = new InvoiceApi();
            $generateInvoice = $api->createInvoice($createInvoice);

            $payment = Payment::create([
                'donation_id' => $donation->id,
                'payment_id' => $generateInvoice['id'],
                'payment_method' => 'xendit',
                'status' => 'pending',
                'payment_url' => $generateInvoice['invoice_url']
            ]);

            DB::commit();

            return redirect($payment->payment_url);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Failed to create donation');
        }
    }

    public function callbackXendit(Request $request){
        $getToken = $request->header('x-callback-token');
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');

        if (!$callbackToken || $getToken !== $callbackToken) {
            # code...
            return response()->json(['message'=>'unauthorized'], 401);
        }
        $payment = Payment::where('payment_id', $request->id)->first();

        if (!$payment) {
            # code...
            return response()->json(['message'=>'Payment not found'], 404);
        }
        $payment->update([
            'status'=>$request->status === 'PAID' ? 'success payment' : 'failed payment'
        ]);

        if ($request->status === 'PAID') {
            # code...
            $donation = Donation::find($payment->donation_id);
            $donation->update([
                'status'=>'success payment'
            ]);
        }

        return response()->json(['message'=>'payment updated']);
    }

    public function success($id){
        $donation = Donation::find($id);
        return view('success', compact('donation'));
    }
}
