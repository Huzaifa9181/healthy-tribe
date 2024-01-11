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
                    <form action="{{ route('resource_training.update') }}" id="createWorkoutForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Addiction Type</label>
                                <select name="addiction_id" required class="form-control">
                                    <option value="">--Select Addiction Type--</option>
                                    @if ($addiction)
                                        @foreach ($addiction as $val)
                                            @if ($data->addiction_id === $val->id)
                                                <option value="{{$val->id}}" selected>{{$val->name}}</option>                                                
                                            @else
                                                <option value="{{$val->id}}">{{$val->name}}</option>
                                            @endif
                                        @endforeach
                                    @else
                                    <option value="" disabled>--No Addiction Found--</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pdf">Pdf</label>
                                <input type="file" name="pdf" class="form-control"  style="padding: 3px !important;" id="image">
                            </div>
                            @if($data->pdf)
                                <div class="form-group col-md-6">
                                    <strong>Current Pdf:</strong><br>
                                    <a href="{{ asset('public/'.$data->pdf) }}" class="btn btn-success mt-2" target="_blank"><i class="fas fa-print"></i></a>
                                </div>
                            @endif

                            <input type="hidden" name="hidden_pdf" value="{{ $data->pdf ?? '' }}">
                            <div class="form-group col-md-6">
                                <label for="pdf">Video</label>
                                <input type="file" name="video" class="form-control" style="padding: 3px !important;" id="image">
                            </div>
                            @if($data->video)
                                <div class="form-group col-md-6">
                                <strong>Current Video:</strong><br>
                                <video controls style="width: 40%;">
                                    <source src="{{ asset('public/'.$data->video) }}" type="video/mp4" >
                                    Your browser does not support the video tag.
                                </video>
                                
                                </div>
                            @endif
                            <input type="hidden" name="hidden_video" value="{{ $data->video ?? '' }}">
                        </div>
                        <input type="hidden" name="id" value="{{$data->id}}">
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
