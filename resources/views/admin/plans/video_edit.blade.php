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
            <form action="{{ route('plan_video.update') }}" id="createWorkoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body row" >
                    <div class="form-group col-md-6">
                        <label for="exampleInputName">Title</label>
                        <input type="text" name="title" class="form-control" id="exampleInputName" value="{{$data->title ?? ''}}" placeholder="Enter Title">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="image">Video</label>
                      <input type="file" name="video" class="form-control" style="padding: 3px !important;" id="image">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="image">Duration</label>
                        <input type="number" name="duration" class="form-control" id="exampleInputName" value="{{$data->duration ?? ''}}" placeholder="Enter Duration">
                    </div>
                    <input type="hidden" name="id" value="{{$data->id}}">
                    @if($data->path)
                        <div class="form-group col-md-6">
                          <strong>Current Video:</strong>
                          <video controls style="max-width: 84%;">
                              <source src="{{ asset('public/'.$data->path) }}" type="video/mp4" >
                              Your browser does not support the video tag.
                          </video>
                          
                        </div>
                    @endif
                    <input type="hidden" name="hidden_video" value="{{ $data->path ?? '' }}">
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
