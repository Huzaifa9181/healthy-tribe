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
                    <form action="{{ route('addiction_option.update') }}" id="createWorkoutForm" method="POST">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Option</label>
                                <input type="text" name="option" class="form-control" id="exampleInputName"
                                    placeholder="Enter Option" value="{{$data->option}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Addiction Type</label>
                                <select name="addiction_id" class="form-control">
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
                                        <option value="" disabled>--No Addiction Type Found--</option>
                                    @endif
                                </select>
                            </div>
                            <input type="hidden" name="id" value="{{$data->id}}">
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
