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
              <h3 class="card-title">{{$title}}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('trainer.profile_update') }}" id="createProfileForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputName">Name</label>
                    <input type="text" name="name" class="form-control" id="exampleInputName" value="{{$data->name ?? ''}}" placeholder="Enter first name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail" value="{{$data->email ?? ''}}" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword" placeholder="New password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPhoneNumber">Phone Number</label>
                    <input type="number" name="phone_number" class="form-control" id="exampleInputPhoneNumber" value="{{$data->phone_number ?? ''}}" placeholder="Enter phone number">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputDOB">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" id="exampleInputDOB" value="{{$data->dob ?? ''}}" placeholder="Enter DOB">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputCountry">Country</label>
                    <input type="text" name="country" class="form-control" id="exampleInputCountry" value="{{$data->country ?? ''}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputCountry">State</label>
                    <input type="text" name="state" class="form-control" id="exampleInputCountry" value="{{$data->state ?? ''}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputCity">City</label>
                    <input type="text" name="city" class="form-control" id="exampleInputCity" value="{{$data->city ?? ''}}">
                  </div>
                  <?php  $gender = ['male' => 'Male' , 'female' => 'Female']; ?> 
                  <div class="form-group">
                    <label for="exampleInputCity">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">--Select Gender--</option>
                        @foreach ($gender as $key => $val)
                            @if ($key === $data->gender)
                                <option value="{{$key}}" selected>{{$val}}</option>
                            @else
                                <option value="{{$key}}" >{{$val}}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputCity">Weight</label>
                    <input type="number" name="weight" class="form-control" id="exampleInputCity" value="{{$data->weight ?? ''}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputCity">Height</label>
                    <input type="number" step="any" name="height" class="form-control" id="exampleInputCity" value="{{ $data->height ?? '' }}">

                  </div>
                  <div class="form-group">
                    <label for="exampleInputCity">Age</label>
                    <input type="number" name="age" class="form-control" id="exampleInputCity" value="{{$data->age ?? ''}}">
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
