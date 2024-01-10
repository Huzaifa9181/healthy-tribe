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
                    <form action="{{ route('question.update') }}" id="createWorkoutForm" method="POST">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Question</label>
                                <input type="text" name="question" class="form-control" required id="exampleInputName"
                                    placeholder="Enter Question" value="{{$data->question }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Addiction Type</label>
                                <select name="addiction" class="form-control" required>
                                    <option value="">--Select Addiction Type--</option>
                                    @if ($addiction)
                                        @foreach ($addiction as $val)
                                        @if ($val->id === $data->addiction_id)
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
                            <div class="form-group col-md-3 mt-4">
                                <button id="addButton" class="btn btn-primary">+</button>
                                <button id="removeButton" class="btn btn-danger mx-3">-</button>
                            </div>
                        </div>
                        <div id="inputForm" class="card-body row">
                            @if($count > 1)
                                @for ($i=1; $i <= $count; $i++ )
                                <div class="form-group col-md-6" id="input{{$i}}">
                                    <label for="exampleInputName">Option {{$i}}</label>
                                    <input type="text" class="form-control" name="option[]" required value="{{$addiction_option[$i-1] ?? ''}}" />
                                </div>
                                    
                                @endfor
                            @else
                            @endif
                        </div>
                        <input type="hidden" name="id" value="{{$data->id}}">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        var counter = <?php echo $count; ?>;

        $("#addButton").click(function(e) {
            e.preventDefault();
            counter++;
            $("#inputForm").append('<div class="form-group col-md-6" id="input' + counter +
                '"><label for="exampleInputName">Option ' + counter +
                '</label><input type="text" class="form-control" name="option[]" required /></div>');
        });

        $("#removeButton").click(function(e) {
            e.preventDefault();
            if (counter > 0) {
                $("#input" + counter).remove();
                counter--;
            }
        });
    });
</script>
@endsection
