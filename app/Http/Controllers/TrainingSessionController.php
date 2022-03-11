<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainingSessionRequest;
use App\Http\Resources\TrainingSessionResource;
use App\Models\Coach;
use App\Models\Gym;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;



class TrainingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $trainingSessions = TrainingSessionResource::collection(TrainingSession::with('gym')->get());
            return DataTables::of($trainingSessions)->addIndexColumn()->make(true);
        }
        return view('menu.training_sessions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gyms = Gym::all();
        $coaches = $gyms->first()->coaches;
        return view('menu.training_sessions.create', compact('gyms','coaches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrainingSessionRequest $request)
    {
        $trainSessionWithCoach=array_slice(request()->all(),count(request()->all())-1);
        $trainingSession=array_slice(request()->all(),1,count(request()->all())-2);
        $trainingSession=TrainingSession::create($trainingSession);
        $trainSessionWithCoach['training_session_id']=$trainingSession->id;
        DB::table('coach_training_session')->insert($trainSessionWithCoach);
        return view('menu.training_sessions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trainingSession = TrainingSession::find($id);
        return view('menu.training_sessions.show', compact('trainingSession'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trainingSession = TrainingSession::find($id);
        if (
            $trainingSession->strats_at < now() &&
            $trainingSession->finishes_at > now() &&
            $trainingSession->gym_members->count() > 0
        ) 
        {
            return 'Hahaha';
        } 
        else {
            $gyms = Gym::all();
            $coaches = $gyms->first()->coaches;
            return view('menu.training_sessions.edit', compact('trainingSession', 'gyms', 'coaches'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTrainingSessionRequest $request, $id)
    {
        $validated = $request->validated();

        $trainingSession = TrainingSession::find($id);
        if ($trainingSession) {
            $trainingSession->update($validated);
        }
        return view('menu.training_sessions.show', compact('trainingSession'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainingSession = TrainingSession::find($id);
        if (
            $trainingSession->strats_at < now() &&
            $trainingSession->finishes_at > now() &&
            $trainingSession->gym_members->count() > 0
        ) 
        {
            return response()->json(['fail' => 'Can\'t delete this session']);
        } else {
            TrainingSession::find($id)->delete();
            return response()->json(['success' => 'This session has been deleted successfully']);
        }
    }
    public function getCoaches($gymId)
    {
        $coaches=Coach::where('gym_id',$gymId)->get();
        return response()->json($coaches);
    }
}