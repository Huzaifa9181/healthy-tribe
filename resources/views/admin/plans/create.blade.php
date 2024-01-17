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
                    <form action="{{ route('plan.store') }}" id="createWorkoutForm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Title</label>
                                <input type="text" name="title" required class="form-control" id="exampleInputName"
                                    placeholder="Enter Title">
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label for="image">Duration</label>
                                <input type="number" name="duration" required class="form-control" id="exampleInputName"
                                    placeholder="Enter Duration">
                            </div> --}}
                            <div class="form-group col-md-6">
                                <label for="image">Cal</label>
                                <input type="number" name="cal" required class="form-control" id="exampleInputName"
                                    placeholder="Enter Duration">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">Category</label>
                                <select name="category_id" class="form-control" required>
                                    @if ($category)
                                        <option value="">--Select category--</option>
                                        @foreach ($category as $val)
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">Image</label>
                                <input type="file" name="image" required class="form-control"
                                    style="padding: 3px !important;" id="image">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">Thumbnail Image</label>
                                <input type="file" name="thumb_image" required class="form-control"
                                    style="padding: 3px !important;" id="image">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
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
