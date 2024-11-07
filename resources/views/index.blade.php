<html>

<head>
    <title>Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#adduser">AddUser</button>
    <table class="table" id="user">
        <thead>
            <tr>
                <th>S.no</th>
                <th>name</th>
                <th>dept</th>
                <th>Action></th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{$d->id}}</td>
                <td>{{$d->name}}</td>
                <td>{{$d->dept}}</td>
                <td>
                    <button type="button" class="btn btn-secondary btnuseredit" value="{{$d->id}}">Edit</button>
                    <button type="button" class="btn btn-danger btnuserdelete" value="{{$d->id}}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add User Modal -->
    <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addnewuser">
                    <div class="modal-body">
                        <input type="text" name="name" placeholder="enter name" required>
                        <input type="text" name="dept" placeholder="enter Department" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit user Modal -->
    <div class="modal fade" id="Edituser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="Editnewuser">
                        <input type="hidden" name="id" id="id" required>
                        <input type="text" name="name" id="name" placeholder="Enter Name" required>
                        <input type="text" name="dept" id="dept" placeholder="Enter dept" required>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#user').DataTable();
        });

        //CSRF link
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add user
        $(document).on('submit', '#addnewuser', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "/user/adduser", // neeed to change
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 200) {
                        $('#adduser').modal('hide');
                        $('#addnewuser')[0].reset();
                        $('#user').load(location.href + " #user");
                        alert("response.message")

                    } else if (response.status == 500) {
                        $('#adduser').modal('hide');
                        $('#addnewuser')[0].reset();
                        console.error("Error:", response.message);
                        alert("Something Went wrong.! try again")
                    }
                }
            });

        });

        // Delete user
        $(document).on('click', '.btnuserdelete', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to delete this data?')) {
                var user_id = $(this).val();
                $.ajax({
                    type: "DELETE",
                    url: `user/delete/${user_id}`,

                    success: function(response) {
                        if (response.status == 500) {
                            alert(response.message);
                        } else {
                            $('#user').load(location.href + " #user");
                        }
                    }
                });
            }
        });

        // Get new user
        $(document).on('click', '.btnuseredit', function(e) {
            e.preventDefault();
            var user_id = $(this).val();
            $.ajax({
                type: "GET",
                url: `/user/edit/${user_id}`,
                success: function(response) {

                    if (response.status == 500) {
                        alert(response.message);
                    } else {
                        $('#id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#dept').val(response.data.dept);
                        $('#Edituser').modal('show');
                    }
                }
            });
        });

        //Edit new user
        $(document).on('submit', '#Editnewuser', function(e) {
            e.preventDefault();
            var user_id = $('#id').val();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: `user/update/${user_id}`,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 200) {
                        $('#Edituser').modal('hide');
                        $('#Editnewuser')[0].reset();
                        $('#user').load(location.href + " #user");
                        alert(response.message)
                    } else if (response.status == 500) {
                        $('#Edituser').modal('hide');
                        $('#Editnewuser')[0].reset();
                        console.error("Error:", response.message);
                        alert("Something Went wrong.! try again")
                    }
                }
            });
        });
    </script>
</body>

</html>