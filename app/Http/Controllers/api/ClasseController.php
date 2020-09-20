<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Class_schedules;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;


class ClasseController extends Controller
{

    public function index()
    {
        return Classes::all();
    }


    public function store(Request $request)
    {
        $user = new User($request->all());
        $user->save();

        $classes = new Classes($request->all());
        $classes->user_id = $user->id;
        $classes->save();

        $tamanho = (count($request->schedule) - 1);

        for ($i = 0; $i <= $tamanho; $i++) {

            $schudeles = new Class_schedules($request->schedule[$i]);
            $from = ConvertHourToMinutes($schudeles->from);
            $to = ConvertHourToMinutes($schudeles->to);
            $schudeles->class_id = $classes->id;
            $schudeles->from = $from;
            $schudeles->to = $to;
            $schudeles->save();
        };
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
