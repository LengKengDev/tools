@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 col-sm-3">
                <div class="card border-0 shadow">
                <div class="card-body">
                    <h4 class="card-title text-center">Watermark PDF</h4>
                  <form action="{{ route('watermark.store') }}" enctype="multipart/form-data" method="POST">
                      @csrf
                      <div class="form-group">
                          <label for="file"></label>
                          <input type="file" class="form-control-file" name="file"
                                 id="file" placeholder="file"
                                 aria-describedby="file">
                          <small id="file" class="form-text text-muted">
                              Chọn file PDF cần thêm watermark @if(session()->has('status')) <span class="text-danger">{{ session('status') }}</span> @endif
                          </small>
                      </div>
                      <button type="submit"
                              class="btn btn-outline-primary btn-block">
                          Watermark now
                      </button>
                  </form>
                    @if(session()->has('file'))
                        <br>
                        <div class="alert alert-success" role="alert">
                            <strong>Watermark successfully! </strong><a href="/{{ session('file') }}"
                                                             class="">Click to Download now!</a>
                        </div>
                    @endif
                </div>
                    <div class="card-footer">
                        @if($files->count() > 0)
                            <h6>Recent activities <small class="text-muted">Click to download</small></h6>
                        @endif
                        <div class="list-group">
                            @foreach($files as $file)
                                <a href="/{{ $file->name }}"
                                   class="list-group-item list-group-item-action">{{ \Illuminate\Support\Str::limit($file->origin, 50) }} <small class="text-success float-right">{{ $file->created_at->diffForHumans() }}</small></a>
                            @endforeach
                        </div>
                        <div class="mt-1">
                            {{ $files->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
