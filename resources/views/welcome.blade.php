<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>CRUD Example</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#itemModal" id="addItemBtn">Add
            Item</button>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="itemTableBody"></tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="itemForm">
                        @csrf
                        <input type="hidden" id="itemId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Add Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            fetchItems();

            $('#addItemBtn').on('click', function() {
                $('#itemForm')[0].reset();
                $('#itemId').val('');
                $('#itemModalLabel').text('Add Item');
                $('#saveBtn').text('Add Item');
            });

            $('#itemForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#itemId').val();
                const name = $('#name').val();
                const description = $('#description').val();

                if (id) {
                    // Update item
                    $.ajax({
                        url: '/items/' + id,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            name,
                            description
                        },
                        success: function() {
                            fetchItems();
                            $('#itemModal').modal('hide');
                            Swal.fire('Updated!', 'Your item has been updated.', 'success');
                            location.reload();
                        }
                    });
                } else {
                    // Add item
                    $.ajax({
                        url: '/items',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            name,
                            description
                        },
                        success: function() {
                            fetchItems();
                            $('#itemModal').modal('hide');
                            Swal.fire('Added!', 'Your item has been added.', 'success');
                            location.reload();
                        }
                    });
                }
            });
        });

        function fetchItems() {
            $.ajax({
                url: '/items',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    let rows = '';
                    data.forEach(item => {
                        rows += `<tr>
                                    <td>${item.id}</td>
                                    <td>${item.name}</td>
                                    <td>${item.description}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editItem(${item.id})">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(${item.id})">Delete</button>
                                    </td>
                                </tr>`;
                    });
                    $('#itemTableBody').html(rows);
                }
            });
        }

        function editItem(id) {
            $.ajax({
                url: '/items/' + id,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(item) {
                    $('#itemId').val(item.id);
                    $('#name').val(item.name);
                    $('#description').val(item.description);
                    $('#itemModalLabel').text('Edit Item');
                    $('#saveBtn').text('Update Item');
                    $('#itemModal').modal('show');
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        function deleteItem(id) {
            $.ajax({
                url: '/items/' + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    fetchItems();
                    Swal.fire('Deleted!', 'Your item has been deleted.', 'success');
                    location.reload();
                }
            });
        }
    </script>
</body>

</html>
