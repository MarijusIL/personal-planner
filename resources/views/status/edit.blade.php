@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Edit Status</h1>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('status.update', [$status]) }}">
                            <div class="form-group"><label>Name: </label><input type="text" class="form-control"
                                    name="status_name" value="{{ old('status_name', $status->name) }}">
                            </div>
                            @csrf
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('title') Edit Status @endsection
