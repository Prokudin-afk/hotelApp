@extends('layouts.main')

@section('content')
    <header class="container-fluid bg-light">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <a href="/" style="text-decoration: none;"><p class="display-5">hotel</p></a>
                </div>
                <div class="col-2"></div>
                @if(session('role') == 'visitor')
                    <div class="col-md-3" style="cursor: pointer;" onclick="show_orders()">
                        <p class="mt-4">My orders</p>
                    </div>
                    <div class="col-md-3" style="cursor: pointer;" onclick="log_out()">
                        <p class="mt-4">Log out</p>
                    </div>
                @elseif(session('role') == 'operator')
                    <div class="col-md-3" style="cursor: pointer;">
                        <a href="/control_panel" style="text-decoration: none; color: black;"><p class="mt-4">Control panel</p></a>
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
                        <button type="button" class="btn btn-primary" onclick="search_rooms()">Find available rooms</button>
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
    <!--Log in modal-->
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

    <!--Register modal-->
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

    <!--Avaliable rooms modal-->
    <div class="modal fade" id="modalShowAvRooms" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Avaliable rooms</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <ul class="list-group" id="ulSwitchRoomType">
                                <li class="list-group-item active">Standard</li>
                                <li class="list-group-item">Average</li>
                                <li class="list-group-item">Half luxury</li>
                                <li class="list-group-item">Luxury</li>
                            </ul>
                        </div>
                        <div class="col-10">
                            <div id="dvShowRooms"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p style="color: red" id="pModalRegErr"></p>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--Orders modal-->
    <div class="modal fade" id="modalShowOrders" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">My orders</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Created:</label>
                    <table class="table table-primary" style="vertical-align: middle;">
                        <thead>
                            <th>№</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Room</th>
                            <th>Cost</th>
                            <th>Persons</th>
                            <th>Type</th>
                            <th></th>
                        </thead>
                        <tbody id="tblShowCreatedOrders"></tbody>
                    </table>

                    <label class="form-label">Active:</label>
                    <table class="table table-success" style="vertical-align: middle;">
                        <thead>
                            <th>№</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Room</th>
                            <th>Cost</th>
                            <th>Persons</th>
                            <th>Type</th>
                            <th></th>
                        </thead>
                        <tbody id="tblShowActiveOrders"></tbody>
                    </table>

                    <label class="form-label">Archive:</label>
                    <table class="table table-secondary" style="vertical-align: middle;">
                        <thead>
                            <th>№</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Room</th>
                            <th>Cost</th>
                            <th>Persons</th>
                            <th>Type</th>
                            <th></th>
                        </thead>
                        <tbody id="tblShowArchiveOrders"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

            //selecting type of room for booking
            $(document).on('click', '#ulSwitchRoomType li', function() {
                $(this).addClass('active').siblings().removeClass('active');
            });
        });

        let exampleRoomImages = [
            'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80',
            'https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1548&q=80',
            'https://images.unsplash.com/photo-1631049035581-bec13f40dfff?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80'
        ];

        let visitorOrderActions = '<div class="dropstart">\
            <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown" aria-expanded="false">\
                <i class="fa-solid fa-bars"></i>\
            </button>\
            <ul class="dropdown-menu">\
                <li><a class="dropdown-item" onclick="deleteOrder(this)">Delete</a></li>\
            </ul>\
        </div>';

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
                    switch(data['code']) {
                        case 101:
                            alert('required fields are not filled');
                            break;
                        case 102:
                            alert('incorrect date');
                            break;
                        case 103: 
                            alert('incorrect count of visitors');
                            break;
                        case 120: 
                            if(!data['rooms'].length) {
                                alert('no results');
                                return;
                            }

                            $('#dvShowRooms').empty();
                            $('#ulSwitchRoomType').empty();
                            $.each(data['types'], function(ind, elem) {
                                let page = '<div id="page' + elem['name'] + 'Rooms" style="display: none;"></div>';
                                $('#dvShowRooms').append(page);

                                let btn = '<li class="list-group-item" onclick="$(\'#page' + elem['name'] + 'Rooms\').show().siblings().hide()">';
                                btn += elem['name'] + '</li>';
                                $('#ulSwitchRoomType').append(btn);
                            });

                            $.each(data['rooms'], function(ind, elem) {
                                let temp = '';
                                temp += '<div class="card" style="width: 18rem;">';
                                    temp += '<img src="' + exampleRoomImages[randomNum(0, 3)] + '" class="card-img-top" alt="room">';
                                    temp += '<div class="card-body">';
                                        temp += '<h5 class="card-title">№ ' + elem['real_num'] + '</h5>';
                                        temp += '<p class="card-text">Per night: ' + elem['cost_per_night'] + ' ₽</p>';
                                        temp += '<p class="card-text">Visitors: ' + elem['max_visitors_count'] + '</p>';
                                        temp += '<a class="btn btn-primary" style="width: 100%;" onclick="make_booking(' + elem['id'] + ', ' + elem['real_num'] + ')">Booking</a>';
                                    temp += '</div>';
                                temp += '</div>';

                                $('#page' + elem['name'] + 'Rooms').append(temp);
                            });
                            $('#modalShowAvRooms').modal('show');
                            break;
                    }
                }
            });
        }

        function make_booking(roomId, roomNum) {
            if(confirm('Really want to booking room № ' + roomNum + '?')) {
                let bookingData = {
                    room_id: roomId,
                    start: $('#inpBookingStart').val(),
                    end: $('#inpBookingEnd').val(),
                    count: $('#slcBookingVisitors').val()
                }

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url:'/make_booking',
                    data: {
                        data: bookingData
                    },
                    success:function(data) {
                        switch(data['code']) {
                            case 101:
                                alert('log in first');
                                break;
                            case 102: 
                                alert('the room has already been booked by someone else');
                            case 120: 
                                alert('success');
                                $('#modalShowAvRooms').modal('hide');
                                show_orders();
                                break;
                        }
                    }
                });
            }
        }

        function show_orders() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url:'/show_orders',
                success:function(data) {
                    $('#tblShowCreatedOrders').empty();
                    $('#tblShowActiveOrders').empty();
                    $('#tblShowArchiveOrders').empty();

                    $.each(data['orders'], function(ind, elem) {
                        let outStr = '<tr>';
                            outStr += '<td>' + elem['id'] + '</td>';
                            outStr += '<td>' + elem['date_start'] + '</td>';
                            outStr += '<td>' + elem['date_end'] + '</td>';
                            outStr += '<td>' + elem['real_num'] + '</td>';
                            outStr += '<td>' + elem['cost_per_night'] + '</td>';
                            outStr += '<td>' + elem['max_visitors_count'] + '</td>';
                            outStr += '<td>' + elem['name'] + '</td>';
                            outStr += '<td data-order="' + elem['id'] + '">' + visitorOrderActions + '</td>';
                        outStr += '</tr>';

                        switch(elem['status']) {
                            case 1: 
                                $('#tblShowCreatedOrders').append(outStr);
                                break;
                            case 2: 
                                $('#tblShowActiveOrders').append(outStr);
                                break;
                            case 3: 
                                $('#tblShowArchiveOrders').append(outStr);
                                break;
                        }
                    });
                    $('#modalShowOrders').modal('show');
                }
            });
        }

        function deleteOrder(order) {
            let orderId = $(order).parent().parent().parent().parent().data('order');
            let bookingData = {
                id: orderId,
                action: 'delete'
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url:'/delete_booking',
                data: {
                    data: bookingData
                },
                success:function(data) {
                    switch(data['code']) {
                        case 102:
                            alert('you can delee only created orders');
                            break;
                        case 120: 
                            show_orders();
                            break;
                    }
                } 
            })
        } 

        function editOrder() {

        }

        function randomNum(min, max) { 
            return Math.floor(Math.random() * (max - min + 1) + min)
        }
    </script>
@endsection