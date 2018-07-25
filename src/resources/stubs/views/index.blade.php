@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <a href="{{createRoute}}" class="btn btn-primary w-100">create</a>
                    <div class="card-header">
                        {{modelName}} Generated Create Page
                    </div>
                    <div class="card-body d-flex">
                        @forelse(${{modelNameSingularLowerCase}} as ${{modelNameSingularLowerCase}})

                            <div class="item">{{${{modelNameSingularLowerCase}}}}</div>

                            <a href="{{showRouteStart}},${{modelNameSingularLowerCase}}->id)}}" class="btn btn-success">Show Me</a>
                            <a href="{{editRouteStart}},${{modelNameSingularLowerCase}}->id)}}" class="btn btn-primary">Edit Me</a>
                            <form action="{{deleteRouteStart}},${{modelNameSingularLowerCase}}->id)}}" method="POST">
                                {{method_field('DELETE')}}
                                {{csrf_field()}}
                                <button type="submit" class="btn btn-danger">delete me!</button>
                            </form>
                        @empty
                            No data found
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection