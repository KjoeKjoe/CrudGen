@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex">
                        {{modelName}} Generated Create Page
                        <div class="ml-auto d-flex">
                            <a href="{{createRoute}}" class="btn btn-primary">create</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse(${{modelNameSingularLowerCase}} as ${{modelNameSingularLowerCase}})
                            {{${{modelNameSingularLowerCase}}}}<a href="{{showRouteStart}},${{modelNameSingularLowerCase}}->id)}}" class="btn btn-success">Show Me</a>
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