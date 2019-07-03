@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ダッシュボード</div>
                <div class="card-body">
                    <div class="text-center">
                        <a href="{{ route('slacks.create') }}" class="btn btn-outline-primary"><i class="fa fa-fw fa-plus-circle"></i> ジョブカンアカウント追加</a>
                    </div>
                    <hr>
                    @if(count($slacks) == 0)
                        <div class="text-center">
                            <span clasS="text-muted">まだジョブカンアカウントを追加しません。</span>
                        </div>
                    @endif
                    <ul class="list-group">
                    @foreach($slacks as $slack)
                        <li class="list-group-item">
                            <a href="{{ route('slacks.edit', $slack) }}"><i class="fa fa-fw fa-edit"></i>Token {{ $loop->iteration }} created at {{ $slack->created_at->diffForHumans() }}</a>
                            <a href="#" class="btn btn-outline-danger btn-sm float-right" onclick="event.preventDefault();
                             r = confirm('本当に削除してよろしいですか？'); if(r == true) {document.getElementById('form-{{ $loop->iteration }}').submit();}"><i class="fa fa-fw fa-trash"></i></a>
                            <form action="{{ route('slacks.destroy', $slack) }}" method="POST" id="form-{{ $loop->iteration }}">@csrf @method('DELETE')</form>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
