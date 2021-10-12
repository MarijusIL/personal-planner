@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Activity Log</h1>

                    </div>
                    <ul class="list-group">
                        @foreach ($logs as $log)
                            <li class="list-group-item">
                                <div class="list-block">
                                    <div class="list-block__content">
                                        <p>{{ $log->id }}//{{ $log->timestamp }}//{{ $log->category }}//{{ $log->type }}
                                        </p>
                                        <ul>
                                            @foreach ($log->getChanges() as $key => $value)
                                                <li>{{ $key }} : {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('title') Activity Log @endsection
