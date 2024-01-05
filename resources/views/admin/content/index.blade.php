@extends('admin.layout.layout')
@section('content')
@section('title' , $title)
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Privacy Policy
              </h3>
            </div>
            <!-- /.card-header -->
            <form action="{{ route('content.update') }}" id="updateContentForm" method="POST">
              @csrf
              <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
              <div class="card-body">
                <textarea id="summernote" name="privacy" placeholder="Enter Here">
                  {{ $data ? unserialize($data->privacy) : '' }}
                </textarea>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /.col-->
      </div>
    </section>
    <!-- /.content -->
    @section('script')
    <script>
      $(function () {
        // Summernote
        $('#summernote').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
          mode: "htmlmixed",
          theme: "monokai"
        });
      })
    </script>
    @endsection
@endsection
