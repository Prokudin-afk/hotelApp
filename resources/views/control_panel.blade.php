@extends('layouts.main')

@section('content')
    <header class="container-fluid bg-light">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <a href="/" style="text-decoration: none;"><p class="display-5">hotel</p></a>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <ul class="list-group mt-2" id="ulSwitchOper">
                        <li class="list-group-item active" data-table="1">Created bookings</li>
                        <li class="list-group-item" data-table="2">Active bookings</li>
                        <li class="list-group-item" data-table="3">Archive bookings</li>
                        <li class="list-group-item" data-table="4">Visitors</li>
                    </ul>
                </div>
                <div class="col-10">
                    <div class="row">
                        <div class="col-12">
                            <input type="text" id="inpSearchOper" class="form-control mt-2" placeholder="Search by id...">
                        </div>
                    </div>
                    <div class="row">
                        <div id="dvOperShowCreated">
                            <table id="tblOperShowCreated" class="table">
                                <thead>
                                    <th>id</th>
                                    <th>start</th>
                                    <th>end</th>
                                    <th>r_num</th>
                                    <th>cost</th>
                                    <th>visitors</th>
                                    <th>type</th>
                                    <th>u_id</th>
                                    <th>name</th>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div id="dvOperShowActive" style="display: none;">
                            <table id="tblOperShowActive" class="table">
                                <thead>
                                    <th>id</th>
                                    <th>start</th>
                                    <th>end</th>
                                    <th>r_num</th>
                                    <th>cost</th>
                                    <th>visitors</th>
                                    <th>type</th>
                                    <th>u_id</th>
                                    <th>name</th>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div id="dvOperShowArchive" style="display: none;">
                            <table id="tblOperShowArchive" class="table">
                                <thead>
                                    <th>id</th>
                                    <th>start</th>
                                    <th>end</th>
                                    <th>r_num</th>
                                    <th>cost</th>
                                    <th>visitors</th>
                                    <th>type</th>
                                    <th>u_id</th>
                                    <th>name</th>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div id="dvOperShowVisitors" style="display: none;">
                            <table id="tblOperShowVisitors" class="table">
                                <thead>
                                    <th>id</th>
                                    <th>name</th>
                                    <th>mail</th>
                                    <th>role</th>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
    
                    <div class="row">
                        <nav>
                            <ul class="pagination justify-content-center unselectable" style="cursor: pointer;" id="ulPagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modals')
<div class="modal fade" id="mdlEditBooking" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit booking №<a id="pMdlEditBooking"></a></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select class="form-select" id="slcEditBookingStatus">
                    <option value="1">Created</option>
                    <option value="2">Active</option>
                    <option value="3">Archive</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSaveBookingChanges">Save changes</button>
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

        loadTable();

        $(document).on('click', '#ulSwitchOper li', function() {
            $(this).addClass('active').siblings().removeClass('active');
            loadTable();
            $('#' + outScreens[$(this).data('table')]).show().siblings().hide();
        });

        /*PAGINATION*/
        $(document).on('click', '.page-item', function() {
            $('.page-item').removeClass('active');
            $(this).addClass('active');
            loadTable();
        });

        $(document).on('click', '.page-back', function() {
            let page = Math.max(1, (parseInt($('.active.page-link').data('page')) - 1));
            $('.page-item').removeClass('active');
            $('[data-page="' + page + '"]').addClass('active');
            loadTable();
        });

        $(document).on('click', '.page-next', function() {
            let count = $('.translate-middle.badge').data('count');
            let page = Math.min(count, (parseInt($('.active.page-link').data('page')) + 1));
            $('.page-item').removeClass('active');
            $('[data-page="' + page + '"]').addClass('active');
            loadTable();
        });
        /*PAGINATION*/

        $(document).on('click', '#btnSaveBookingChanges', function() {
            let bookingData = {
                id: $('#btnSaveBookingChanges').data('order'),
                action: 'edit',
                status: $('#slcEditBookingStatus').val()
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url:'/delete_booking',
                data: {
                    data: bookingData
                },
                success:function(data) {
                    if(data['code'] = 120) {
                        alert('edited successfully');
                        $('#mdlEditBooking').modal('hide');
                        loadTable();
                    }else {
                        alert('you can`t edit this booking')
                        $('#mdlEditBooking').modal('hide');
                    }
                }
            });
        });

        $(document).on('keyup', '#inpSearchOper', function() {
            loadTable();
        })
    });

    let outScreens = {
        1: 'dvOperShowCreated',
        2: 'dvOperShowActive',
        3: 'dvOperShowArchive',
        4: 'dvOperShowVisitors'
    }

    let operatorOrderActions = '<div class="dropstart">\
        <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown" aria-expanded="false">\
            <i class="fa-solid fa-bars"></i>\
        </button>\
        <ul class="dropdown-menu">\
            <li><a class="dropdown-item" onclick="loadEditOrder(this)">Edit</a></li>\
            <li><a class="dropdown-item" onclick="deleteOrder(this)">Delete</a></li>\
        </ul>\
    </div>';

    let operatorUserActions = '<div class="dropstart">\
        <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown" aria-expanded="false">\
            <i class="fa-solid fa-bars"></i>\
        </button>\
        <ul class="dropdown-menu">\
            <li><a class="dropdown-item" onclick="show_orders(this)">Show visitor bookings</a></li>\
        </ul>\
    </div>';

    function loadTable() {
        let page = $('.active.page-link').data('page')??1;
        let table = $('#ulSwitchOper .active').data('table');

        let loadData = {
            page: page,
            table: table,
            search: $('#inpSearchOper').val()
        }
        $.ajax({
            type: 'POST',
            url:'/load_table',
            dataType: 'json',
            data: {
                data: loadData
            },
            success:function(data) {
                loadPagination(data['count'], $('.active.page-link').data('page')??1);
                if((table == 1)||(table == 2)||(table==3)) {
                    let strTable = '';
                    $.each(data['table'], function(ind, elem) {
                        strTable += '<tr data-order="' + elem['b_id'] + '">';
                        strTable += '<td>' + elem['b_id'] + '</td>';
                        strTable += '<td>' + elem['date_start'] + '</td>';
                        strTable += '<td>' + elem['date_end'] + '</td>';
                        strTable += '<td>' + elem['real_num'] + '</td>';
                        strTable += '<td>' + elem['cost_per_night'] + '</td>';
                        strTable += '<td>' + elem['max_visitors_count'] + '</td>';
                        strTable += '<td>' + elem['name'] + '</td>';
                        strTable += '<td>' + elem['u_id'] + '</td>';
                        strTable += '<td>' + elem['u_name'] + '</td>';
                        strTable += '<td>' + operatorOrderActions + '</td>';
                        strTable += '</tr>';
                    });
                    $('#' + outScreens[table] + ' tbody').empty().append(strTable);
                }else if(table == 4) {
                    let strTable = '';
                    $.each(data['table'], function(ind, elem) {
                        strTable += '<tr data-user="' + elem['id'] + '">';
                        strTable += '<td>' + elem['id'] + '</td>';
                        strTable += '<td>' + elem['name'] + '</td>';
                        strTable += '<td>' + elem['mail'] + '</td>';
                        strTable += '<td>' + elem['role'] + '</td>';
                        strTable += '<td>' + operatorUserActions + '</td>';
                        strTable += '</tr>';
                    });
                    $('#' + outScreens[table] + ' tbody').empty().append(strTable);
                }
            }
        });
    }

    function loadPagination(count, active) {
        count = parseInt(count);
        active = parseInt(active);
        let str = '<li class="page-back page-link">Back</li>';
        if ((count == active) && (count > 2)) {
            str += '<li class="page-item page-link" data-page="' + (active - 2) + '">' + (active - 2) + '</li>';
        }
        for (let i = Math.max(1, (active - 1)); i <= Math.min(count, (active + 1)); i++) {
            str += '<li class="page-item page-link" data-page="' + i + '">' + i + '</li>';
        }
        if ((active == 1) && (count > 2)) {
            str += '<li class="page-item page-link" data-page="' + (active + 2) + '">' + (active + 2) + '</li>';
        }
        str += '<li class="position-relative page-next page-link">Forward';
        str += '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"';
        str += 'data-count="' + count + '">' + Math.max(0, (count - active)) + '</span></li>';

        $('#ulPagination').empty().append(str);
        $('[data-page="' + active + '"]').addClass('active');
    }

    function deleteOrder(order) {
        let orderId = $(order).parent().parent().parent().parent().parent().data('order');
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
                    case 120:
                        alert('successfully deleted');
                        loadTable();
                }
                loadTable();
            } 
        })
    } 

    function loadEditOrder(order) {
        let orderId = $(order).parent().parent().parent().parent().parent().data('order');
        $('#pMdlEditBooking').empty().append(orderId);
        $('#btnSaveBookingChanges').data('order', orderId);
        $('#mdlEditBooking').modal('show');
    }

    function show_orders(user) {
        let userId = $(user).parent().parent().parent().parent().parent().data('user');
        let data = {
           id: userId
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url:'/show_orders',
            data: {
                data: data
            },
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
</script>
@endsection