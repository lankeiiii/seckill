@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('测试') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('seckill_index') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="product_id" class="col-md-4 col-form-label text-md-end">{{ __('test') }}</label>

                            <div class="col-md-6">
                                <input id="product_id"  class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('发送') }}
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
