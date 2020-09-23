<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Connections;

class ConnectionsController extends Controller
{
   
    public function index()
    {
        return count(Connections::all());
    }

   
    public function store(Request $request)
    {
        $connections = new Connections($request->all());
        $connections->save();

        response()->json([],201);
    }

   
    public function show($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}
