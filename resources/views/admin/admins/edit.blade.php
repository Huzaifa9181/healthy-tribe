@extends('admin.layout.layout')
@section('content')
@section('title' , $title)
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- jquery validation -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Edit Admin Details</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.update', ['id' => $data->id]) }}" id="editProfileForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputName">Name</label>
                    <input type="text" name="name" value = "{{ $data->name ?? '' }}" class="form-control" id="exampleInputName" placeholder="Enter first name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail">Email address</label>
                    <input type="email" name="email" value = "{{ $data->email ?? '' }}" class="form-control" id="exampleInputEmail" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword">Password</label>
                    <input type="password" name="password" value="" class="form-control" id="exampleInputPassword" placeholder="New password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPhoneNumber">Phone Number</label>
                    <input type="tel" name="phone_number" value = "{{ $data->phone_number ?? '' }}" class="form-control" id="exampleInputPhoneNumber" placeholder="Enter phone number">
                  </div>
                  <div class="form-group">
                    <label for="image">Profile Image</label>
                    <input type="file" name="image" class="form-control" style="padding: 3px !important;" id="image">
                  </div>
                  @if($data->image)
                      <div class="form-group ">
                          <strong>Current Image:</strong><br><br>
                          <img src="{{ asset('public/'.$data->image) }}" alt="Profile Image" style="max-width: 100px;">
                      </div>
                  @endif
                  <input type="hidden" name="hidden_image" value="{{ $data->image ?? '' }}">
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

          </div>
          <!-- /.card -->
          </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">

        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  @endsection
