<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Class_schedules;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ClasseController extends Controller
{

    public function index()
    {
        return Classes::all();

    }


    public function store(Request $request)
    {

        try {

            DB::beginTransaction();
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

            DB::commit();
            response()->json([
                'message' => 'Inserido com sucesso!'
            ], 201);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Deu merda!', $e
            ], 400);
            DB::rollBack();
        }
    }


    public function show(request $request)
    {

        $subject = $request->subject;
        $week_day = $request->week_day;
        $time = $request->from;


        if (!$week_day || $subject || $time) {
            response()->json([
                'error' => 'Não existe filtros, por favor, insira',
            ], 400);
        }
        $from = ConvertHourToMinutes($time);

        $result = DB::table('classes')
            ->whereExists(function ($query) use ($from, $week_day) {
                $query->select('class_schedules.*')
                    ->from('class_schedules')
                    ->whereRaw('class_schedules.class_id = classes.id')
                    ->whereRaw('class_schedules.week_day = ?', $week_day)
                    ->whereRaw('class_schedules.from <= ?', $from)
                    ->whereRaw('class_schedules.to > ?', $from);
            })
            ->where('classes.subject', '=', $subject)
            ->join('users', 'users.id', '=', 'classes.user_id')
            ->get();

        // $result = DB::table('classes')
        // ->join('class_schedules', 'classes.id', '=', 'class_schedules.class_id')
        // ->join('users', 'users.id', '=', 'classes.user_id')
        // ->select('classes.subject', 'classes.cost', 'class_schedules.week_day', 
        // 'class_schedules.from', 'classes.user_id', 'users.name', 'users.avatar', 'users.bio')
        // ->where('classes.subject', '=', $subject)
        // ->where('class_schedules.week_day', '=', $week_day)
        // ->where('class_schedules.from', '=', $from)
        // ->get();



        return $result;
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
