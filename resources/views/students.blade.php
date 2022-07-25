@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @include('header')
				
                <div class="card-body">
					<a class="btn btn-primary btn-add" href="{{ route('add-student') }}">{{ __('Add Student') }}</a>
					
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					
					<table id="data-table" class="table table-striped" style="width:100%">
						<thead>
							<tr>
								<th>#</th>
								<th>{{ __('Name') }}</th>
								<th>{{ __('School') }}</th>
								<th>{{ __('Order') }}</th>
								<th>{{ __('Edit') }}</th>
								<th>{{ __('Delete') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($resources as $key => $resource)
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $resource->name }}</td>
									<td>@if($resource->school) {{ $resource->school->name }} @endif</td>
									<td>{{ $resource->order }}</td>
									<td><a class="btn btn-success" href="{{ route('edit-student', ['id'=>$resource->id]) }}">{{ __('Edit') }}</a></td>
									<td>
										{{ Form::open(array('route' => array('delete-student', $resource->id), 'method' => 'delete')) }}
											{{ Form::token() }}
											{{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
										{{ Form::close() }}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
