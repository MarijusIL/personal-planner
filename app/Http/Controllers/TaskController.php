<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Models\Log;
use Validator;


class TaskController extends Controller
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
    public function index(Request $request)
    {
        $statuses = Status::orderBy('name')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
        if ($request->sort) {
            if ('name' == $request->sort && 'asc' == $request->sort_dir) {
                $tasks = Task::orderBy('name')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
            } elseif ('name' == $request->sort && 'desc' == $request->sort_dir) {
                $tasks = Task::orderBy('name', 'desc')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
            } elseif ('add_date' == $request->sort && 'asc' == $request->sort_dir) {
                $tasks = Task::orderBy('add_date')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
            } elseif ('add_date' == $request->sort && 'desc' == $request->sort_dir) {
                $tasks = Task::orderBy('add_date', 'desc')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
            } elseif ('completed_date' == $request->sort && 'asc' == $request->sort_dir) {
                $tasks = Task::orderBy('completed_date')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
            } elseif ('completed_date' == $request->sort && 'desc' == $request->sort_dir) {
                $tasks = Task::orderBy('completed_date', 'desc')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
            } else {
                $tasks = Task::paginate(self::RESULTS_IN_PAGE)->withQueryString();
            }
        } elseif ($request->filter && 'status' == $request->filter) {
            $tasks = Task::where('status_id', $request->status_id )->paginate(self::RESULTS_IN_PAGE)->withQueryString();
        } elseif ($request->search && 'all' == $request->search) {
            $tasks = Task::where('description', 'like', '%'.$request->s.'%')->
            orWhere('name', 'like', '%'.$request->s.'%')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
        } else {
            $tasks = Task::paginate(self::RESULTS_IN_PAGE)->withQueryString();
        }
        return view('task.index', [
            'tasks' => $tasks,
            'sortDirection' => $request->sort_dir ?? 'asc',
            'statuses' => $statuses,
            'status_id' => $request->status_id ?? '0',
            's' => $request->s ?? '',

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Status::orderBy('name')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
        return view('task.create', ['statuses' => $statuses]);
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
           'task_name' => ['required', 'min:3', 'max:128'],
           'task_description' => ['required', 'min:3'],
           'task_completed_date' => ['required', 'date', 'after:-1 second'],
        ],
        );
        if ($validator->fails()) {
           $request->flash();
           return redirect()->back()->withErrors($validator);
        }

        $log = new Log;
        $log->timestamp = date('Y-m-d H:i:s');
        $log->category = 'TASK';
        $log->type = 'CREATED';
        $log->info = "task_name|$request->task_name,task_description|$request->task_description,task_completed_date|$request->task_completed_date,task_status|".(DB::table('statuses')->where('id', (int)$request->status_id)->get())[0]->name;
        $log->save();


        $task = new Task;
        $task->name = $request->task_name;
        $task->description = $request->task_description;
        $task->add_date = $request->task_add_date;
        $task->completed_date = $request->task_completed_date;
        $task->status_id = $request->status_id;
        $task->save();
        return redirect()->route('task.index')->with('success_message', 'Successfuly added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('task.show', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $statuses = Status::orderBy('name')->paginate(self::RESULTS_IN_PAGE)->withQueryString();
        return view('task.edit', ['task' => $task, 'statuses' => $statuses]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(),
        [
           'task_name' => ['required', 'min:3', 'max:128'],
           'task_description' => ['required', 'min:3'],
           'task_completed_date' => ['required', 'date', 'after:-1 second'],
        ],
        );
        if ($validator->fails()) {
           $request->flash();
           return redirect()->back()->withErrors($validator);
        }

        $log = new Log;
        $log->timestamp = date('Y-m-d H:i:s');
        $log->category = 'TASK';
        $log->type = 'UPDATED';
        $log->info = '';
        if ($task->name == $request->task_name){
            $log->info .= "task_name|$request->task_name,";
        } else {
            $log->info .= "task_name|$task->name to $request->task_name,";
        }

        if ($task->description == $request->task_description){
            $log->info .= "task_description|$request->task_description,";
        } else {
            $log->info .= "task_description|$task->description to $request->task_description,";
        }

        if ($task->completed_date == $request->task_completed_date){
            $log->info .= "task_completed_date|$request->task_completed_date,";
        } else {
            $log->info .= "task_completed_date|$task->completed_date to $request->task_completed_date,";
        }

        if ($task->status == $request->task_status){
            $log->info .= 'task_status|'.(DB::table('statuses')->where('id', (int)$request->status_id)->get())[0]->name;
        } else {
            $log->info .= 'task_status|'.(DB::table('statuses')->where('id', (int)$task->status_id)->get())[0]->name.' to '.(DB::table('statuses')->where('id', (int)$request->status_id)->get())[0]->name;
        }

        $log->save();

        $task->name = $request->task_name;
        $task->description = $request->task_description;
        $task->completed_date = $request->task_completed_date;
        $task->status_id = $request->status_id;
        $task->save();
        return redirect()->route('task.index')->with('success_message', 'Successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $log = new Log;
        $log->timestamp = date('Y-m-d H:i:s');
        $log->category = 'TASK';
        $log->type = 'DELETED';
        $log->info = "task_name|$task->name";
        $log->save();
        $task->delete();
        return redirect()->route('task.index')->with('success_message', 'Successfully deleted.');
    }
}