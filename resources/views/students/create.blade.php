@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @include('header')

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					
					@include('error')
					
					{{ Form::open(array('route' => 'store-student')) }}
						{{ Form::token() }}
						<div class="mb-3">
							{{ Form::label('name', __('Student Name'), array('class' => 'form-label')) }}
							{{ Form::text('name', old('name'), array('class' => 'form-control')) }}
						</div>
						<div class="mb-3">
							{{ Form::label('school_id', __('School Name'), array('class' => 'form-label')) }}
							{{ Form::select('school_id', $schools, old('school_id'), array('class' => 'form-control')) }}
						</div>
						{{ Form::submit('Add', array('class' => 'btn btn-success')) }}
					{{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
