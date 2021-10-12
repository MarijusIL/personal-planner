@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Edit Task</h1>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('task.update', [$task]) }}">
                            <div class="form-group"><label>Name: </label><input type="text" name="task_name"
                                    class="form-control" value="{{ old('task_name', $task->name) }}"></div>
                            <div class="form-group"><label>Description: </label><textarea name="task_description"
                                    class="form-control"
                                    id="summernote">{{ old('task_description', $task->description) }}</textarea>
                            </div>
                            <div class="form-group"><label>Completion Deadline: </label><input type="text"
                                    name="task_completed_date" class="form-control"
                                    value="{{ old('task_completed_date', $task->completed_date) }}"></div>
                            {{-- <div class="form-group"><label>Completion Deadline: </label><input type="date"
                                    name="task_completed_date" class="form-control" pattern="\d{4}-\d{2}-\d{2}"
                                    value="{{ old('task_completed_date', $task->completed_date) }}"></div> --}}
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status_id" class="form-control">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}" @if (old('status_id', $status->id) == $task->status_id) selected @endif>
                                            {{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @csrf
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>
@endsection

@section('title') Edit Task @endsection
