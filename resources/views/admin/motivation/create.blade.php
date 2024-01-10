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
                    <form action="{{ route('motivation.store') }}" id="createWorkoutForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control" style="padding: 3px !important;" id="image">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">Addiction Type</label>
                                <select name="addiction_id" class="form-control">
                                    <option value="">--Select Addiction Type--</option>
                                    @if ($addiction)
                                        @foreach ($addiction as $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach    
                                    @else
                                        <option value="" selected >--No Addiction Type Found--</option>
                                    @endif
                                </select>
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
