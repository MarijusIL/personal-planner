@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Task List</h1>
                        <form action="{{ route('task.index') }}" method="get">
                            <fieldset>
                                <legend>Sort</legend>
                                <div class="block">
                                    <button type="submit" name="sort" value="add_date" class="btn btn-info">
                                        By creation date
                                    </button>
                                    <button type="submit" name="sort" value="completed_date" class="btn btn-info">
                                        By deadline
                                    </button>
                                    <button type="submit" name="sort" value="name" class="btn btn-info">
                                        By name
                                    </button>
                                </div>
                                <div class="block">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort_dir" id="_1" value="asc"
                                            @if ('desc' != $sortDirection) checked @endif>
                                        <label class="form-check-label" for="_1">
                                            Ascending order
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort_dir" id="_2" value="desc"
                                            @if ('desc' == $sortDirection) checked @endif>
                                        <label class="form-check-label" for="_2">
                                            Descending order
                                        </label>
                                    </div>
                                </div>
                                <div class="block">
                                    <a href="{{ route('task.index') }}" type="submit" class="btn btn-warning">
                                        Reset
                                    </a>
                                </div>
                            </fieldset>
                        </form>
                        <form action="{{ route('task.index') }}" method="get">
                            <fieldset>
                                <legend>Filter</legend>
                                <div class="block">
                                    <div class="form-group">
                                        <select name="status_id" class="form-control">
                                            <option value="0" disabled selected>Select status</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}" @if ($status_id == $status->id) selected @endif>
                                                    {{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Select status from the list</small>
                                    </div>
                                </div>
                                <div class="block">
                                    <button type="submit" name="filter" value="status" class="btn btn-info">
                                        Filter
                                    </button>
                                    <a href="{{ route('task.index') }}" type="submit" class="btn btn-warning">
                                        Reset
                                    </a>
                                </div>
                            </fieldset>
                        </form>
                        <form action="{{ route('task.index') }}" method="get">
                            <fieldset>
                                <legend>Search</legend>
                                <div class="block">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Search" name="s"
                                            value="{{ $s }}">
                                        <small class="form-text text-muted">Search for tasks</small>
                                    </div>
                                </div>
                                <div class="block">
                                    <button type="submit" name="search" value="all" class="btn btn-info">
                                        Search
                                    </button>
                                    <a href="{{ route('task.index') }}" type="submit" class="btn btn-warning">
                                        Reset
                                    </a>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            {{ $tasks->links() }}
                        </div>
                        <ul class="list-group">
                            @foreach ($tasks as $task)
                                <li class="list-group-item">
                                    <div class="list-block">
                                        <div class="list-block__content">
                                            <span>{{ $task->name }}</span><small>Added on: {{ $task->add_date }};
                                                Deadline:
                                                {{ $task->completed_date }};
                                                Task status: {{ $task->getStatus->name }}</small>
                                        </div>
                                        <div class="list-block__buttons">
                                            <a href="{{ route('task.edit', [$task]) }}" class="btn btn-info">Edit</a>
                                            <a href="{{ route('task.show', [$task]) }}" class="btn btn-warning">View</a>
                                            <form method="POST" action="{{ route('task.destroy', [$task]) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            {{ $tasks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('title') Task list @endsection
