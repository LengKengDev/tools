@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        新規登録
                        <a href="{{ route('home') }}" class="btn btn-outline-danger btn-sm float-right"><i class="fa fa-fw fa-arrow-left"></i>戻る</a>
                    </div>

                    <div class="card-body">
                        @include('slacks._info')
                        <hr>
                        <form action="{{ route('slacks.store') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Slack token') }} <span class="text-danger">*</span></label>

                                <div class="col-md-9">
                                    <input id="email" type="text" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token" value="{{ old('token') }}" required autofocus>

                                    @if ($errors->has('token'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('token') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Auto checkin') }} <span class="text-danger">*</span></label>

                                <div class="col-md-9">
                                    <select name="checkin" id="" class="form-control {{ $errors->has('checkin') ? ' is-invalid' : '' }}">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    @if ($errors->has('checkin'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('checkin') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Auto checkout') }} <span class="text-danger">*</span></label>

                                <div class="col-md-9">
                                    <select name="checkout" id="" class="form-control {{ $errors->has('checkout') ? ' is-invalid' : '' }}">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    @if ($errors->has('checkout'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('checkout') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Custom channel') }}</label>

                                <div class="col-md-9">
                                    <input id="email" type="text" class="form-control{{ $errors->has('channel') ? ' is-invalid' : '' }}" name="channel" value="{{ old('channel') }}" placeholder="デフォルトはAutobotのChannelです。CL2SUJB29">

                                    @if ($errors->has('channel'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('channel') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Chatwork ID') }}</label>

                                <div class="col-md-9">
                                    <input id="email" type="text" class="form-control{{ $errors->has('cw') ? ' is-invalid' : '' }}" name="cw" value="{{ old('cw') }}" placeholder="例えば：「[To:3716376] 山田さん」、IDの3716376を入力してください。">

                                    @if ($errors->has('cw'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cw') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-4 offset-4">
                                    <button type="submit" class="btn btn-outline-primary btn-block">
                                        <i clasS="fa fa-fw fa-save"></i>{{ __('保存') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
