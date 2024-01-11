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
            <form action="{{ route('general_setting.store') }}" id="createWorkoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body row" >
                    <div class="form-group col-md-6">
                        <label for="exampleInputName">Title</label>
                        <input type="text" name="title" class="form-control" required  id="exampleInputName" placeholder="Enter Title">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="image">Image</label>
                      <input type="file" name="image" class="form-control"  required style="padding: 3px !important;" id="image">
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Create</button>
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
