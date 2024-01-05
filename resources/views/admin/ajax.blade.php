<script>

    //use for data table
    function initializeDataTable(ajaxRoute, columnsConfig) {
        $(document).ready(function () {
            $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: ajaxRoute, // Replace with the actual route
                columns: columnsConfig,
                initComplete: function () {
                    //console.log(this.api().ajax.json()); // Log the response data
                }
            });
        });
    }

    // use for delete
    $(document).ready(function () {
        // Store the user ID to be deleted when the delete button is clicked
        let userIdToDelete = null;

        // Listen for a click event on delete buttons with class 'delete-user'
        $(document).on('click', '.delete-user', function () {
            userIdToDelete = $(this).data('id');
            model = $(this).data('model');
        });

        // Handle the form submission when the user confirms the deletion
        $('#deleteUserForm').on('submit', function (e) {
            e.preventDefault();
            if (userIdToDelete !== null && model !== null) {
                $.ajax({
                    url: "{{ url('/admin') }}/"+model+"/destroy", // Replace with your delete route
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': userIdToDelete
                    },
                    success: function (data) {
                        // Handle success, e.g., update the DataTable
                        $('#data_table').DataTable().ajax.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle error
                        console.error(error);
                    }
                });
            }
            // Close the modal
            $('#deleteUserModal').modal('hide');
        });
    });
</script>