<!-- Main Footer -->
<footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">Healthy Tribe</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
    </div>
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ url('public/admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ url('public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ url('public/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ url('public/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('public/admin/dist/js/adminlte.js') }}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{ url('public/admin/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ url('public/admin/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ url('public/admin/plugins/chart.js/Chart.min.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<!-- Summernote -->
<script src="{{ url('public/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- CodeMirror -->
{{-- <script src="{{ url('public/admin/plugins/codemirror/codemirror.js') }}"></script>
<script src="{{ url('public/admin/plugins/codemirror/mode/css/css.js') }}"></script>
<script src="{{ url('public/admin/plugins/codemirror/mode/xml/xml.js') }}"></script>
<script src="{{ url('public/admin/plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script> --}}
<!-- AdminLTE for demo purposes -->
<script src="{{ url('public/admin/dist/js/demo.js') }}"></script>
<!-- Custom js -->
@include('admin.ajax')

@yield('script')
<script>
  function alertErrors(response) {
      var message = "";
      for (var key in response) {
          // Assuming there's only one error message per field
          message += response[key][0] + "\n"; // Each error on a new line
      }
      alert(message);
  }
  var a = {!! json_encode(session('success')) !!};
  var b = {!! json_encode(session('error')) !!};

  if (a !== null && a !== "") {
      alert(a);
  }

  if (typeof b === "object" && b !== null) { // Check if b is an object (e.g., a JSON response)
      alertErrors(b);
  } else if (b !== null && b !== "") {
      alert(b);
  }
</script>

</body>

</html>
