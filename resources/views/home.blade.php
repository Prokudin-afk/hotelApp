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
                        <p class="mt-4">My orders</p>
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
    <section class="container-fluid bg-secondary">
        <div class="container">
            <div class="row" style="text-align:center">
                <div class="col-4">
                    <div class="mt-4">
                        <label class="form-label text-white">Check-in:</label>
                        <input type="date" class="form-control" id="inpBookingStart">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mt-4">
                        <label class="form-label text-white">Check-out:</label>
                        <input type="date" class="form-control" id="inpBookingEnd">
                    </div>
                    <div class="mt-3 mb-4">
                        <button type="button" class="btn btn-primary" id="btnSearchBooking">Find available rooms</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mt-4">
                        <label class="form-label text-white">Count of visitors:</label>
                        <select class="form-select" id="slcBookingVisitors">
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                            <option value="4">Four</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                    <p style="color: red" id="pModalLogErr"></p>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="log_in()">Log in</button>
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
                    <button type="button" class="btn btn-primary" onclick="register()">Register</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            //setting token for ajax
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            $(document).on('click', '#btnSearchBooking', function() {
                search_rooms();
            })
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
                        case 104: 
                            $('#pModalRegErr').text('account with same email already exists');
                            break;
                        case 120: 
                            $('#modalReg').modal('hide');
                            alert('Success! You can log in your account');
                            break;
                    }
                }
            });
        }

        function log_in() {
            let exUser = {
                mail: $('#inpModalLogMail').val(),
                pass: $('#inpModalLogPass').val()
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url:'/log_in',
                data: {
                    user: exUser
                },
                success:function(data) {
                    switch(data['code']) {
                        case 101: 
                            $('#pModalLogErr').text('required fields are not filled');
                            break;
                        case 102: 
                            $('#pModalLogErr').text('no account with such email');
                            break;
                        case 103: 
                            $('#pModalLogErr').text('incorrect password');
                            break;
                        case 120: 
                            window.location.reload();
                            break;
                    }
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

        function search_rooms() {
            let searchData = {
                start: $('#inpBookingStart').val(),
                end: $('#inpBookingEnd').val(),
                count: $('#slcBookingVisitors').val()
            } 

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url:'/search_rooms',
                data: {
                    data: searchData
                },
                success:function(data) {
                    console.log(data);
                }
            });
        }
    </script>
@endsection