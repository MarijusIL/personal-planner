@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>{{ $task->name }}</h1>
                    </div>
                    <div class="card-body">
                        <div class="task-container">
                            <p class="task-container__description">{!! $task->description !!}</p>
                            <p class="task-container__add_date">Task added: {{ $task->add_date }}</p>
                            <p class="task-container__completed_date">Task completed: {{ $task->completed_date }}</p>
                            <p class="task-container__status">Task status: <b>{{ $task->getStatus->name }}</b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('title') {{ $task->name }} @endsection
