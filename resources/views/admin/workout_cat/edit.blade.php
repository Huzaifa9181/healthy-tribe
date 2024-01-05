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
              <h3 class="card-title">Update Workout Categories Details</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('workout_cat.update') }}" id="updateWorkoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                  <div class="card-body row">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName">Name</label>
                      <input type="text" name="name" class="form-control" value="{{ $data->name ?? '' }}" id="exampleInputName" placeholder="Enter category name">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="image">Image</label>
                      <input type="file" name="image" class="form-control" style="padding: 3px !important;" id="image">
                    </div>
                    @if($data->image)
                        <div>
                            <strong>Current Image:</strong>
                            <img src="{{ asset('public/'.$data->image) }}" alt="Workout Cat Image" style="max-width: 100px;">
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
