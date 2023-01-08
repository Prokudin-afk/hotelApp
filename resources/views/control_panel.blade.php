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
                        <div id="dvOperShowCreated"></div>
                        <div id="dvOperShowActive" style="display: none;"></div>
                        <div id="dvOperShowArchive" style="display: none;"></div>
                        <div id="dvOperShowVisitors" style="display: none;"></div>
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

    function loadTable() {
        let loadData = {
            page: $('.active.page-link').data('page')??1,
            table: $('#ulSwitchOper .active').data('table'),
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
                console.log(data);
                loadPagination(data['count'], $('.active.page-link').data('page')??1);
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
        str += '<li class="position-relative page-next page-link">Forward</li>';
        $('#ulPagination').empty().append(str);
        $('[data-page="' + active + '"]').addClass('active');
    }

</script>
@endsection