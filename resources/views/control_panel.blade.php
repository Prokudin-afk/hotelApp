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
                            <input type="text" id="inpSearchOper" class="form-control mt-2" placeholder="Search...">
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
            <li><a class="dropdown-item" onclick="loadEditOrder(this)">Show visitor bookings</a></li>\
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
                        strTable += '<tr>';
                        strTable += '<td>' + elem['b_id'] + '</td>';
                        strTable += '<td>' + elem['date_start'] + '</td>';
                        strTable += '<td>' + elem['date_end'] + '</td>';
                        strTable += '<td>' + elem['real_num'] + '</td>';
                        strTable += '<td>' + elem['cost_per_night'] + '</td>';
                        strTable += '<td>' + elem['max_visitors_count'] + '</td>';
                        strTable += '<td>' + elem['name'] + '</td>';
                        strTable += '<td>' + elem['u_id'] + '</td>';
                        strTable += '<td>' + elem['u_name'] + '</td>';
                        strTable += '</tr>';
                    });
                    $('#' + outScreens[table] + ' tbody').empty().append(strTable);
                }else if(table == 4) {
                    let strTable = '';
                    $.each(data['table'], function(ind, elem) {
                        strTable += '<tr>';
                        strTable += '<td>' + elem['id'] + '</td>';
                        strTable += '<td>' + elem['name'] + '</td>';
                        strTable += '<td>' + elem['mail'] + '</td>';
                        strTable += '<td>' + elem['role'] + '</td>';
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

</script>
@endsection