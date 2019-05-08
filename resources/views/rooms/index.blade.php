@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Rooms active</div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Room ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rooms as $room)
                                        <tr>
                                            <td>{{ $loop->index }}</td>
                                            <td>{{ $room->room }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
