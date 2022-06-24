@extends('base')
@section('content')
    @include('shared.growl')
    <script>
        function populateModal(userId, name, email, currentRole) {
            document.getElementById("name").value = name;
            document.getElementById("email").value = email;
            document.getElementById("currRole").value = currentRole;
            document.getElementById("userId").value = userId;
        }

        function submitForm() {
            if (validateForm()) {
                document.getElementById("updateRoleForm").submit();
            }
        }

        function validateForm() {
            form = document.getElementById("updateRoleForm");

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }

            return true;
        }
    </script>

    <div class="p-2 w-50 mx-auto d-flex flex-column">
        <div class="p-2">
            <form class="w-100" id="searchForm" action="{{ route('users.search') }}" method="post">
                <div class="input-group mb-3">
                    @method('post')
                    @csrf
                    <input id="search" name="search" type="text" class="form-control"
                        placeholder="Search user" aria-label="Search user"
                        aria-describedby="searchButton">
                    <button type="submit" class="btn btn-primary" type="button" id="searchButton">
                        <i class="fa-solid fa-magnifying-glass"></i></button>

                </div>
            </form>
        </div>
        <div class="p-2 flex-fill">
            @foreach ($users as $user)
                <div class="d-flex flex-row bd-highlight border-bottom align-items-center">
                    <div class="p-2 bd-highlight flex-grow-1 d-flex justify-content-center">
                        <?php echo $user->name; ?>
                    </div>
                    <div class="p-2 bd-highlight flex-grow-1 d-flex justify-content-center">
                        <?php echo $user->email; ?>
                    </div>
                    <div class="p-2 bd-highlight flex-grow-1 d-flex justify-content-center">
                        @foreach ($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                        @endforeach
                    </div>
                    <div class="p-2 bd-highlight flex-grow-1 d-flex justify-content-center">
                        <button type="button"
                            onclick="populateModal('<?php echo $user->id; ?>', '<?php echo $user->name; ?>', '<?php echo $user->email; ?>', '<?php echo $user->roles->isEmpty() ? null : $user->roles->first()->id; ?>')"
                            class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Edit role
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update user role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" placeholder="Name" id="name" class="form-control" name="name"
                            disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" placeholder="Email" id="email" class="form-control" name="email"
                            disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="currRole" class="form-label">Current Role</label>
                        <select class="form-select" id="currRole" name="currRole" disabled>
                            <option selected>Select role</option>
                            @foreach ($roles as $role)
                                <option value="<?php echo $role->id; ?>"><?php echo $role->name; ?></option>
                            @endforeach
                        </select>
                    </div>
                    <form class="needs-validation" id="updateRoleForm" method="POST"
                        action="{{ route('user.role.update') }}" novalidate>
                        @csrf
                        <input type="hidden" id="userId" name="userId">
                        <div class="form-group mb-3">
                            <label for="newRoleId" class="form-label">New Role</label>
                            <select class="form-select" id="newRoleId" name="newRoleId" required>
                                <option value="" selected>Select role</option>
                                @foreach ($roles as $role)
                                    <option value="<?php echo $role->id; ?>"><?php echo $role->name; ?></option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a role.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="submitForm()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    </main>
@endsection
