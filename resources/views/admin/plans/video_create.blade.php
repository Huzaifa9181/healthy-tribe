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
            <form action="{{ route('plan_video.store') }}" id="createWorkoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body row" >
                    <div class="form-group col-md-6">
                        <label for="exampleInputName">Title</label>
                        <input type="text" name="title" class="form-control" id="exampleInputName" placeholder="Enter Title">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="image">Video</label>
                      <input type="file" name="video" class="form-control" required style="padding: 3px !important;" id="videoInput">
                      <video controls id="videoPlayer" style="display: none;"></video>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="image">Workout Plan</label>
                      <select name="plan_id" class="form-control">
                        <option value="">--Select Workout Plan--</option>
                        @if ($plan)
                          @foreach ($plan as $val)
                              <option value="{{$val->id}}">{{$val->Title}}</option>
                          @endforeach
                        @else
                          <option value="" disabled>--No Workout Plan Found--</option>
                        @endif
                      </select>
                    </div>
                    <input type="hidden" name="duration" class="form-control" id="duration">
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
  <script>
    let videoInput = document.getElementById("videoInput");
    let videoPlayer = document.getElementById("videoPlayer");
    let hiddenInput = document.getElementById("duration");

    videoInput.addEventListener("change", function() {
        if (this.files && this.files[0]) {
            let selectedVideo = this.files[0];
            
            // Set the selected video as the source for the video player
            videoPlayer.src = URL.createObjectURL(selectedVideo);

            // Once the video metadata is loaded, you can access its duration
            videoPlayer.addEventListener("loadedmetadata", function() {
              hiddenInput.value = this.duration
            });
        }
    });
</script> 
  @endsection
