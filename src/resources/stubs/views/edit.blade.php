@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <a href="{{indexRoute}}"  class="w-100 btn btn-success">Index</a>
                    <div class="card-header">{{modelName}} Generated Create Page</div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection