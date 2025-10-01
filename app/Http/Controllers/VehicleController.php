<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Validator;
use DB;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sql = 'SELECT *, concat(brand, " ",model) AS vehicle_name
        FROM vehicles 
        WHERE deleted_at is null';
        $vehicles = DB::select($sql);
        return response()->json($vehicles);
    }

    public function vehiclesByClientId($id)
    {
        $sql = 'SELECT *, concat(brand, " ",model) AS vehicle_name
        FROM vehicles 
        WHERE deleted_at is null 
        AND client_id LIKE '.$id;
        $vehicles = DB::select($sql);
        return response()->json($vehicles);
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
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|max:255',
            'patent' => 'required|max:255',
            'mileage' => 'required|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(),422);
        }
        try{
            DB::transaction(function() use ($request) {
                $vehicle = new Vehicle();
                $vehicle->brand = $request->brand;
                $vehicle->model = $request->model;
                $vehicle->year = $request->year;
                $vehicle->patent = $request->patent;
                $vehicle->mileage = $request->mileage;
                $vehicle->client_id = $request->client_id;
                $vehicle->client_name = $request->client_name;
                $vehicle->register_date = $request->register_date;
                $vehicle->save();
            });
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
            ], 405);
        }
        return response()->json([
            'message' => '¡Vehiculo registrado exitosamente!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::find($id);
        return response()->json($vehicle);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::findOrFail($id)->toArray();
        return $vehicle;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'year' => 'required|max:255',
            'patent' => 'required|max:255',
            'mileage' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(),422);
        }

        Vehicle::findOrFail($id)->update(
            array(
                'brand' => $request->brand,
                'model' => $request->model,
                'year' => $request->year,
                'patent' => $request->patent,
                'mileage' => $request->mileage,
                'client_id' => $request->client_id,
                'client_name' => $request->client_name,
                'register_date' => $request->register_date,
            )
        );

        return response()->json(['message' => '¡Vehiculo actualizado exitosamente!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Vehicle::findOrFail($id)->delete();
        return response()->json(['message' => '¡Vehiculo eliminado exitosamente!'], 200);
    }

    public function deletedVehicles()
    {
        $vehicle =  Vehicle::onlyTrashed()->get();
        return response()->json($vehicle);
    }

    public function restore($id)
    {
        $vehicle = Vehicle::onlyTrashed()->find($id);
        $vehicle->restore();
        return response()->json(['message' => '¡Vehiculo restaurado exitosamente!'], 200);
    }
}
