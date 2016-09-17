@extends('layouts.app')
@section('content')
<div class="container">
	<form method="POST" action="{{ url('create') }}/{{ isset($edited) ? base64_encode($edited->id) : '' }}">
		{{ csrf_field() }}
		<div class="form-group">
			<label>Username :</label>
			<input type="text" name="name" value="{{ isset($edited) ? $edited->name : '' }}" placeholder="Username" class="form-control">
		</div>
		<div class="form-group">
			<label>Email :</label>
			<input type="email" name="email" value="{{ isset($edited) ? $edited->email : '' }}" placeholder="E-mail" class="form-control">
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-success">
		</div>
	</form>
</div>
@endsection