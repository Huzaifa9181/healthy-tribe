@extends('admin.layout.layout')
@section('content')
@section('title' , $title)
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">List Trianer Video </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Duration</th>
                  <th>Video</th>
                  <th>Workout Plan</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Duration</th>
                  <th>Video</th>
                  <th>Workout Plan</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>

    @section('script')
    <script>
        var userColumns = [
            { data: 'id', name: 'id' }, // Replace 'column1' with your actual column names
            { data: 'title', name: 'title' },
            { data: 'duration', name: 'duration' },
            { data: 'path', name: 'video' },
            { data: 'plan', name: 'plan' },
            { data: 'action', name: 'action', orderable: true, searchable: true },
        ];
        initializeDataTable("{{ route('plan_video.show') }}", userColumns);
    </script> 
    @endsection

  @endsection