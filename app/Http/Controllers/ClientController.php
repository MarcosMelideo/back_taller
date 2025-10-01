<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sql = 'SELECT *, concat(lastname, " ",NAME) AS fullname
        FROM clients 
        WHERE deleted_at is null
        ORDER BY lastname';
        $clients = DB::select($sql);
        return response()->json($clients);
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
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|max:255',      
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(),400);
        }
        try{
            DB::transaction(function() use ($request) {
                $client = new Client();
                $client->name = $request->name;
                $client->lastname = $request->lastname;
                $client->email = !!$request->email?$request->email:"";
                $client->phone = $request->phone;
                $client->register_date = $request->register_date;
                $client->save();
            });
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
            ], 409);
        }
        return response()->json([
            'message' => '¡Cliente registrado exitosamente!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::find($id);
        return response()->json($client);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id)->toArray();
        return $client;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|max:255',      
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        Client::findOrFail($id)->update(
            array(
                'name'=>$request->name,
                'lastname'=>$request->lastname,
                'email'=>$request->email?$request->email:"",
                'phone'=>$request->phone,
                'register_date' => $request->register_date,
        ));
        
        return response()->json(['message' => '¡Cliente actualizado exitosamente!'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($this->hasVehicle($id)->original) {
            return response()->json([
                'error' => "El cliente tiene vehiculos asociados",
            ], 409);
        }else{
            Client::findOrFail($id)->delete();
            return response()->json(['message' => '¡Cliente eliminado exitosamente!'], 200);
        }
    }

    public function deletedClients()
    {
        $clients = Client::onlyTrashed()->get();
        return response()->json($clients);
    }

    public function restore($id)
    {
        $client = Client::onlyTrashed()->find($id);
        $client->restore();
        return response()->json(['message' => '¡Cliente restaurado exitosamente!'], 200);
    }

    public function isDuplicated($name, $lastname){
        $sql = 'SELECT concat(lastname, " ",NAME) AS fullname
        FROM clients 
        WHERE name like "'.$name.'" AND lastname like "'.$lastname.'" 
        AND deleted_at is null';
        $client = DB::select($sql);
        return response()->json(!!$client?true:false);
    }

    public function hasVehicle($id){
        $sql = 'SELECT id
        FROM vehicles
        WHERE client_id LIKE '.$id.'
        AND deleted_at is null
        LIMIT 1';

        $hasVehicle = DB::select($sql);
        return response()->json(!!$hasVehicle?true:false);
    }
}
