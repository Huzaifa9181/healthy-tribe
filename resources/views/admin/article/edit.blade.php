@extends('admin.layout.layout')
@section('content')
@section('title', $title)
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('article.update') }}" id="createWorkoutForm" method="POST">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Title</label>
                                <input type="text" name="title" class="form-control" value="{{$data->title}}" id="exampleInputName"
                                    placeholder="Enter Title">
                            </div>
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="form-group col-md-7">
                                <label for="image">Description</label>
                                <textarea type="text" name="description" class="form-control"  placeholder="Enter description" cols="30"
                                    rows="6">{{$data->description}}</textarea>
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
