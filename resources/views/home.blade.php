@extends('layouts.main')

@section('content')
    <header class="container-fluid bg-light">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <p class="display-5">hotel</p>
                </div>
                <div class="col-2"></div>
                @if(session('role') == 'visitor')
                    <div class="col-md-3" style="cursor: pointer;">
                        <p class="mt-4">Booking</p>
                    </div>
                    <div class="col-md-3" style="cursor: pointer;" onclick="log_out()">
                        <p class="mt-4">Log out</p>
                    </div>
                @else
                    <div class="col-md-3" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalLog">
                        <p class="mt-4">Log in</p>
                    </div>
                    <div class="col-3" style="cursor: pointer;" onclick="$('#modalReg input').val(''); $('#pModalRegErr').text(''); $('#modalReg').modal('show');">
                        <p class="mt-4">Register</p>
                    </div>
                @endif
            </div>
        </div>
    </header>
@endsection

@section('modals')
    <div class="modal fade" id="modalLog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Log in</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="text" class="form-control" id="inpModalLogMail">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control" id="inpModalLogPass">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="log_in()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalReg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Register</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Your name:</label>
                        <input type="text" class="form-control" id="inpModalRegName">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="text" class="form-control" id="inpModalRegMail">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control" id="inpModalRegPass">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Repeat password:</label>
                        <input type="password" class="form-control" id="inpModalRegRePass">
                    </div>
                </div>
                <div class="modal-footer">
                    <p style="color: red" id="pModalRegErr"></p>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="register()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {});

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        function register() {
            let newUser = {
                name: $('#inpModalRegName').val(),
                mail: $('#inpModalRegMail').val(),
                pass: $('#inpModalRegPass').val(),
                repass: $('#inpModalRegRePass').val()
            }
            $.ajax({
                type: 'POST',
                url:'/register',
                dataType: 'json',
                data: {
                    user: newUser
                },
                success:function(data) {
                    switch(data['code']) {
                        case 101:
                            $('#pModalRegErr').text('required fields are not filled');
                            break;
                        case 102: 
                            $('#pModalRegErr').text('passwords don`t match');
                            break;
                        case 103: 
                            $('#pModalRegErr').text('incorrect email');
                            break;
                        case 120: 
                            alert('Success! You can log in your account');
                            break;
                    }
                    console.log(data);
                }
            });
        }

        function log_out() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url:'/log_out',
                success:function(data) {
                    window.location.reload();
                }
            });
        }
    </script>
@endsection