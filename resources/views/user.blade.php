<table class="table table-responsive">
	<thead>
		<tr>
			<th>No</th>
			<th>Name</th>
			<th>Email</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($users as $user)
		<tr>
			<td>{{ $num++ }}</td>
			<td>{{ ucwords($user->name) }}</td>
			<td>
				<a href="mailto:{{ strtolower($user->email) }}?Subject=Hello%20_dev" target="_top">
					<i>{{ strtolower($user->email) }}</i>
				</a>
			</td>
			<td>
				<a href="{{ url('edited') }}/{{ base64_encode($user->id) }}" class="btn btn-sm btn-primary">edit</a>
				<a class="btn btn-sm btn-success" onclick="edit('{{ base64_encode($user->id) }}')">edit-AJAX</a>
				<a class="btn btn-sm btn-info" onclick="del('{{ base64_encode($user->id) }}')">delete-AJAX</a>
				<a href="{{ url('deleted') }}/{{ base64_encode($user->id) }}" class="btn btn-sm btn-danger">delete</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
{{ $users->links() }}