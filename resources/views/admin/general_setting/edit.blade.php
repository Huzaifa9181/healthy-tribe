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
            <form action="{{ route('general_setting.update') }}" id="createWorkoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body row" >
                    <div class="form-group col-md-6">
                        <label for="exampleInputName">Title</label>
                        <input type="text" name="title" class="form-control" required  id="exampleInputName" placeholder="Enter Title" value="{{$data->title}}">
                    </div>
                  <input type="hidden" name="id" value="{{$data->id}}">
                    <div class="form-group col-md-6">
                      <label for="logo">Logo</label>
                      <input type="file" name="image" class="form-control"  style="padding: 3px !important;" id="logo">
                    </div>
                    @if($data->logo)
                        <div class="form-group col-md-6">
                            <strong>Current Logo:</strong><br>
                            <img src="{{ asset('public/'.$data->logo) }}" alt="Logo" style="max-width: 200px;">
                        </div>
                    @endif
                    <input type="hidden" name="hidden_image" value="{{ $data->logo ?? '' }}">
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
