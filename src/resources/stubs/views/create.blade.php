@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <a href="{{indexRoute}}"  class="w-100 btn btn-success">Index</a>
                    <div class="card-header">{{modelName}} Generated Create Page</div>
                    <div class="card-body">
                        <form action="{{storeRoute}}" method="POST">
                            {{csrf_field()}}
                            @forelse($columns as $input => $type)
                                <div class="form-group form-inline">
                                    @if($type === 'int')
                                        <div class="form-group">
                                            <input type="number" name="{{$input}}" placeholder="{{$input}}" class="form-control">
                                        </div>
                                    @elseif($type === 'varchar')
                                        <div class="form-group">
                                            <input type="text" name="{{$input}}" placeholder="{{$input}}" class="form-control">
                                        </div>
                                    @endif
                                </div>
                            @empty

                            @endforelse
                            @if($columns)
                                <div class="form-group form-inline">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection