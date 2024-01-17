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
            <form action="{{ route('challenge.update') }}" id="createWorkoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body row" >
                    <div class="form-group col-md-6">
                        <label for="exampleInputName">Title</label>
                        <input type="text" name="title" class="form-control" required  id="exampleInputName" placeholder="Enter Title" value="{{$data->title}}">
                    </div>
                    <div class="form-group col-md-7">
                      <label for="exampleInputName">Description</label>
                      <textarea name="description" cols="30" rows="6" required  class="form-control">{{$data->description}}</textarea>
                  </div>
                  <input type="hidden" name="id" value="{{$data->id}}">
                  <div class="form-group col-md-6">
                    <label for="exampleInputName">Days</label>
                    <input type="text" name="days" class="form-control" value="{{$data->days}}" required  id="exampleInputName" placeholder="Enter Days">
                </div>
                    <div class="form-group col-md-6">
                      <label for="image">Image</label>
                      <input type="file" name="image" class="form-control"  style="padding: 3px !important;" id="image">
                    </div>
                    @if($data->image)
                        <div class="form-group col-md-6">
                            <strong>Current Image:</strong><br>
                            <img src="{{ asset('public/'.$data->image) }}" alt="Challnege Image" style="max-width: 100px;">
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
