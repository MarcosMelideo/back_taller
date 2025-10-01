<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentItem;
use DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sql = 'SELECT *
        FROM payments 
        WHERE deleted_at is null
        ORDER BY date DESC';
        $payments = DB::select($sql);
        return response()->json($payments);
    }

    /**
     * listado de paymentos filtrado por client id.
     */
    public function paymentsByClientId($client_id)
    {
        $sql = 'SELECT *
        FROM payments
        WHERE client_id like '.$client_id.'
        AND deleted_at is null
        ORDER BY date';
        $payments = DB::select($sql);
        return response()->json($payments);
    }


    /*
        * listado de paymentos filtrado por vehicle id.
    */
    public function paymentsByVehicleId($vehicle_id)
    {
        $sql = 'SELECT *
        FROM payments
        WHERE vehicle_id like '.$vehicle_id.'
        AND deleted_at is null
        ORDER BY date';
        $payments = DB::select($sql);
        return response()->json($payments);
    }

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
        $payment = '';
        try {
            DB::transaction(function() use ($request) {
                $payment = new Payment();
                $payment->date = $request->date;
                $payment->client_name = $request->client_name;
                $payment->client_lastname = $request->client_lastname;
                $payment->client_id = $request->client_id;
                $payment->brand = $request->brand;
                $payment->model = $request->model;
                $payment->year = $request->year;
                $payment->mileage = $request->mileage;
                $payment->patent = $request->patent;
                $payment->vehicle_id = $request->vehicle_id;
                $payment->total = $request->total;
                $payment->number = $request->number;
                $payment->save();

                $payment_id = $payment->id;
                foreach ($request->paymentItems as $item) {
                    $paymentItems = new PaymentItem();
                    $paymentItems->payment_id = $payment_id;
                    $paymentItems->quantity = $item["quantity"];
                    $paymentItems->description = $item["description"];
                    $paymentItems->price_by_unit = $item["price_by_unit"];
                    $paymentItems->subtotal = $item["subtotal"];
                    $paymentItems->save();
                }
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
        return response()->json([
            'message' => '¡Cobro registrado con éxito!',
            'payment' => $payment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::where('id', '=', $id)->get();
        return response()->json($payment);
    }
    
    public function showPaymentItems($payment_id)
    {
        $payment_items = PaymentItem::where('payment_id', '=', $payment_id)->get();
        return response()->json($payment_items);
    }

    public function showDeleted(string $id)
    {
        $sql = 'SELECT *
        FROM payments
        WHERE id like '.$id.'';
        $payments = DB::select($sql);
        return response()->json($payments);
    }
    
    public function showPaymentItemsDeleted($id)
    {
        $sql = 'SELECT *
        FROM payment_items
        WHERE payment_id like '.$id.'';
        $payments = DB::select($sql);
        return response()->json($payments);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id)->toArray();
        return $payment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::find($id);
        $payment->client_id = $request->client_id;
        $payment->client_name = $request->client_name;
        $payment->date = $request->date;
        $payment->save();

        return response()->json([
            'message' => '¡Cobro actualizado exitosamente!',
            'payment' => $payment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        try {
            $paymentItems = $this->showPaymentItems($id);
            foreach ($paymentItems->original as $item) {
                PaymentItem::findOrFail($item->id)->delete();
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        } finally {
            Payment::findOrFail($id)->delete();
            return response()->json(['message' => '¡Cobro eliminado exitosamente!'], 200);
        }
    }

    public function deletedPayments()
    {
        $payments = Payment::onlyTrashed()->get();
        return response()->json($payments);
    }

    public function restore($id)
    {
        $payments = Payment::onlyTrashed()->find($id);
        $payments->restore();
        return response()->json(['message' => '¡Presupuesto restaurado exitosamente!'], 200);
    }

    public function lastPaymentNumber(){
        $number = Payment::all()->last()->number ?? 0;
        return response()->json($number);
    }
}
