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
                    <form action="{{ route('plan.update') }}" id="createWorkoutForm" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Title</label>
                                <input type="text" name="title" required class="form-control" id="exampleInputName"
                                    placeholder="Enter Title" value="{{$data->Title ?? ''}}">
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label for="image">Duration</label>
                                <input type="number" name="duration" required class="form-control" value="{{$data->duration ?? ''}}" id="exampleInputName"
                                    placeholder="Enter Duration">
                            </div> --}}
                            <div class="form-group col-md-6">
                                <label for="image">Cal</label>
                                <input type="number" name="cal" required class="form-control" value="{{$data->cal ?? ''}}" id="exampleInputName"
                                    placeholder="Enter Duration">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">Category</label>
                                <select name="category_id" class="form-control" required>
                                    @if ($category)
                                        <option value="">--Select category--</option>
                                        @foreach ($category as $val)
                                          @if ($val->id === $data->category_id)
                                            <option value="{{ $val->id }}" selected>{{ $val->name }}</option>
                                          @else
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                          @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="form-group col-md-6">
                                <label for="image">Image</label>
                                <input type="file" name="image"  class="form-control"
                                    style="padding: 3px !important;" id="image">
                            </div>
                            @if($data->image)
                              <div class="form-group col-md-6">
                                  <strong>Current Image:</strong><br>
                                  <img src="{{ asset('public/'.$data->image) }}" alt="Plan Image" style="max-width: 100px;">
                              </div>
                            @endif
                            <input type="hidden" name="hidden_image" value="{{ $data->image ?? '' }}">
                            <div class="form-group col-md-6">
                                <label for="image">Thumbnail Image</label>
                                <input type="file" name="thumb_image"  class="form-control"
                                    style="padding: 3px !important;" id="image">
                            </div>
                            @if($data->thumbnail)
                              <div class="form-group col-md-6">
                                  <strong>Current Image:</strong><br>
                                  <img src="{{ asset('public/'.$data->thumbnail) }}" alt="Plan thumbnail Image" style="max-width: 100px;">
                              </div>
                            @endif
                            <input type="hidden" name="hidden_thumbnail_image" value="{{ $data->thumbnail ?? '' }}">
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
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
