<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function getYears()
    {
        $years = DB::table('payments')
                    ->pluck('payment_date')
                    ->map(function($year){
                        return date('Y', strtotime($year));
                    })->unique()->toArray();
        
        $yearsArray = [];
        foreach ($years as $year) {
            $yearsArray[] = $year;
        }

        return response()->json($yearsArray);
    }

    public function getMonthsData()
    {
        $year = $_GET['year'];

        $currencies = DB::table('payments')->get()->pluck('currency')->unique()->toArray();

        $sumByMonths = [];
        for($i = 1; $i <= 12; $i++) {
            
            $_currency = [];

            foreach ($currencies as $currency) {
    
               $_currency[$currency] = DB::table('payments')
                                                ->whereYear('payment_date', $year)
                                                ->whereMonth('payment_date', $i)
                                                ->where('currency', $currency)
                                                ->pluck('amount')
                                                ->sum();     
    
            }

            $sumByMonths[] = $_currency;
    
        } 

        return response()->json([
            'sum_by_month' => $sumByMonths
        ]);

    }

    public function getPaymentsByYear()
    {
        $year = $_GET['year'];

        $paymentsList = DB::table('payments')
                        ->whereYear('payment_date', $year) 
                        ->orderBy('payment_date', 'desc')  
                        ->get()
                        ->map(function($month){
                            $month->payment_date = date('Y-m-d', strtotime($month->payment_date));
                            return collect($month)->except(['created_at', 'updated_at']);
                        })->toArray();   
                        
        return response()->json([
            'payments_data' => $paymentsList
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([		
            'amount' => 'required',	
            'payment_date' => 'required',	
            'sign' => 'required',	
            'currency' => 'required'
        ]);	

        $payment = new Payment();		
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->sign = $request->sign;
        $payment->currency = $request->currency;		              
                
        $payment->save();		
                
        return response()->json([		
            'status' => true,	
            'message' => 'SAVED',	
            'id' => $payment->id	
        ]);       		
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'id' => 'required',
            'amount' => 'required',
            'sign' => 'required',
            'currency' => 'required',
            'payment_date' => 'required'
        ]);


        $payment = Payment::find($request->id);

        $payment->amount = $request->amount;
        $payment->sign = $request->sign;
        $payment->currency = $request->currency;
        $payment->payment_date = $request->payment_date;
    
        $payment->save();			

        return response()->json([
            'status' => true,
            'message' => 'UPDATED'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment, $id)
    {
        $to_delete = $payment::findOrFail($id); 

        $to_delete->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'DELETED'
        ]);
        
    }
}
