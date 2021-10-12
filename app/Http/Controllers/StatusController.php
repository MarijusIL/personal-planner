<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Log;
use Validator;


class StatusController extends Controller
{
    const RESULTS_IN_PAGE = 10;
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::orderBy('name')->get();
        return view('status.index', ['statuses' => $statuses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('status.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
           'status_name' => ['required', 'min:2', 'max:16'],
        ],
        );
       if ($validator->fails()) {
           $request->flash();
           return redirect()->back()->withErrors($validator);
       }
        $log = new Log;
        $log->timestamp = date('Y-m-d H:i:s');
        $log->category = 'STATUS';
        $log->type = 'CREATED';
        $log->info = "status_name|$request->status_name";
        $log->save();

        $status = new Status;
        $status->name = $request->status_name;
        $status->save();
        return redirect()->route('status.index')->with('success_message', 'Successfuly added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        return view('status.show', ['status' => $status]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        return view('status.edit', ['status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(),
        [
           'status_name' => ['required', 'min:2', 'max:16'],
        ],
        );
        if ($validator->fails()) {
           $request->flash();
           return redirect()->back()->withErrors($validator);
        }
        $log = new Log;
        $log->timestamp = date('Y-m-d H:i:s');
        $log->category = 'STATUS';
        $log->type = 'UPDATED';
        $log->info = "status_name|$request->status_name";
        if ($status->name != $request->status_name){
            $log->info .= " to $request->status_name";
        }
        $log->save();

        $status->name = $request->status_name;
        $status->save();
        return redirect()->route('status.index')->with('success_message', 'Successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        if($status->getTasks->count()){
            return redirect()->route('status.index')->with('info_message', 'Can\'t delete while tasks asigned to status');
        }
        $log = new Log;
        $log->timestamp = date('Y-m-d H:i:s');
        $log->category = 'STATUS';
        $log->type = 'DELETED';
        $log->info = "status_name|$status->name";
        $log->save();
        $status->delete();
        return redirect()->route('status.index')->with('success_message', 'Successfully deleted');
    }
}