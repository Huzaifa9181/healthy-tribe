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
                    <form action="{{ route('question.store') }}" id="createWorkoutForm" method="POST">
                        @csrf
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Question</label>
                                <input type="text" name="question" class="form-control" required id="exampleInputName"
                                    placeholder="Enter Question">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Addiction Type</label>
                                <select name="addiction" class="form-control" required>
                                    <option value="">--Select Addiction Type--</option>
                                    @if ($addiction)
                                        @foreach ($addiction as $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>--No Addiction Found--</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputName">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">--Select Type--</option>
                                    <option value="radio">Radio</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mt-4">
                                <button id="addButton" class="btn btn-primary">+</button>
                                <button id="removeButton" class="btn btn-danger mx-3">-</button>
                            </div>
                        </div>
                        <div id="inputForm" class="card-body row"></div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
    var counter = 0;

    $("#addButton").click(function(e) {
        e.preventDefault();
        counter++;
        var newInputId = 'inputOption' + counter; // Ensure unique ID for each input

        // Append the new elements to the inputForm
        $("#inputForm").append('<div class="form-group col-md-6" id="div' + newInputId +
            '"><label for="' + newInputId + '">Option ' + counter +
            '</label><input type="text" class="form-control" name="option[]" id="' + newInputId + '" required /></div>');
    });

    $("#removeButton").click(function(e) {
        e.preventDefault();
        if (counter > 0) {
            $("#divinputOption" + counter).remove(); // Use the new ID with "div" prefix to remove the div
            counter--;
        }
    });
});

</script>
@endsection
