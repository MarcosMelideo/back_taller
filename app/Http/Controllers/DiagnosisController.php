<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosis;
use Illuminate\Support\Facades\Validator;
use DB;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sql = 'SELECT *
        FROM diagnoses
        WHERE deleted_at is null';
        $diagnoses = DB::select($sql);
        return response()->json($diagnoses);
    }

    public function diagnosesByClientId($client_id)
    {
        $sql = 'SELECT *
        FROM diagnoses 
        WHERE deleted_at is null 
        AND client_id LIKE '.$client_id;
        $diagnoses = DB::select($sql);
        return response()->json($diagnoses);
    }

    public function diagnosesByVehicleId($vehicle_id){
        $sql = 'SELECT *
        FROM diagnoses 
        WHERE deleted_at is null 
        AND vehicle_id LIKE '.$vehicle_id;
        $diagnoses = DB::select($sql);
        return response()->json($diagnoses);
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
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'state' => 'required',
            'date_state' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'vehicle_id'=> 'required',
            'client_name' => 'required',
            'client_id' => 'required',
            'register_date' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(),422);
        }
        try{
            DB::transaction(function() use ($request) {
                $diagnosis = new Diagnosis();
                $diagnosis->description = $request->description;
                $diagnosis->state = $request->state;
                $diagnosis->date_state = $request->date_state;
                $diagnosis->brand = $request->brand;
                $diagnosis->model = $request->model;
                $diagnosis->vehicle_id = $request->vehicle_id;
                $diagnosis->client_name = $request->client_name;
                $diagnosis->client_id = $request->client_id;
                $diagnosis->register_date = $request->register_date;
                $diagnosis->save();
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
        return response()->json([
            'message' => '¡Diagnostico registrado exitosamente!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $diagnosis = Diagnosis::find($id);
        return response()->json($diagnosis);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $diagnosis = Diagnosis::findOrFail($id)->toArray();
        return $diagnosis;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'state' => 'required',
            'date_state' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'vehicle_id'=> 'required',
            'client_name' => 'required',
            'client_id' => 'required',
            'register_date' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(),422);
        }

        try{
            $diagnosis = Diagnosis::find($id);
            $diagnosis->description = $request->description;
            $diagnosis->state = $request->state;
            $diagnosis->date_state = $request->date_state;
            $diagnosis->brand = $request->brand;
            $diagnosis->model = $request->model;
            $diagnosis->vehicle_id = $request->vehicle_id;
            $diagnosis->client_name = $request->client_name;
            $diagnosis->client_id = $request->client_id;
            $diagnosis->register_date = $request->register_date;
            $diagnosis->save();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    
        return response()->json([
            'message' => '¡Diagnostico actualizado exitosamente!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Diagnosis::findOrFail($id)->delete();
        return response()->json(['message' => '¡diagnostico eliminado exitosamente!'], 200);
    }

    public function deletedDiagnoses()
    {
        $diagnosis =  Diagnosis::onlyTrashed()->get();
        return response()->json($diagnosis);
    }

    public function restore($id)
    {
        $diagnosis = Diagnosis::onlyTrashed()->find($id);
        $diagnosis->restore();
        return response()->json(['message' => '¡Diagnostico restaurado exitosamente!'], 200);
    }
}
