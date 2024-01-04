@extends('admin.layout.layout')
@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- jquery validation -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Create User Details</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.store') }}" id="createProfileForm" method="POST">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputName">Name</label>
                    <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter first name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword" placeholder="New password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPhoneNumber">Phone Number</label>
                    <input type="tel" name="phone_number" class="form-control" id="exampleInputPhoneNumber" placeholder="Enter phone number">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputDOB">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" id="exampleInputDOB" placeholder="Enter DOB">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputCountry">Date of Birth</label>
                    <input type="country" name="country" class="form-control" id="exampleInputCountry">
                  </div>
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
