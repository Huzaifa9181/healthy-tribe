
	<div class="btn-group">
		<button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i> Actions</button>
		<span class="caret"></span>
		<span class="sr-only"></span>
		</button>
		<div class="dropdown-menu" role="menu">

			<a href="{{ url('/admins/'.$id.'/edit')}}" class="dropdown-item" ><i class="fas fa-edit"></i> Edit</a>
			<a href="{{ url('/admins/'.$id)}}" class="dropdown-item" ><i class="fa fa-eye"></i> Show</a>
			<div class="dropdown-divider"></div>
			<a data-toggle="modal" data-target="#delete_record{{$id}}" href="#" class="dropdown-item">
				<i class="fas fa-trash"></i>Delete</a>
		</div>
	</div>

