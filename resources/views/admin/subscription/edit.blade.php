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
              <h3 class="card-title">Update Subscription Details</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('subscription.update') }}" id="updateSubscriptionForm" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputPackage">Package</label>
                    <input type="text" name="package" class="form-control" value="{{ $data->package ?? '' }}" id="exampleInputPackage" placeholder="Enter package name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputDuration">Duration</label>
                    <input type="text" name="duration" class="form-control" value="{{ $data->duration ?? '' }}" id="exampleInputDuration" placeholder="Enter duration name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPrice">Price</label>
                    <input type="text" name="price" class="form-control" value="{{ $data->price ?? '' }}" id="exampleInputPrice" placeholder="Enter price name">
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
