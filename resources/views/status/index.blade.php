@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Status list</h1>
                    </div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($statuses as $status)
                                <li class="list-group-item">
                                    <div class="list-block">
                                        <div class="list-block__content">
                                            <span>{{ $status->name }}</span>
                                        </div>
                                        <div class="list-block__buttons">
                                            <a href="{{ route('status.edit', [$status]) }}" class="btn btn-info">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('status.destroy', $status) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('title') Status list @endsection
