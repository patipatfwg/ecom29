<?php

$scripts = [
    'nestable',
    'sweetalert',
    'select2',
    'datatables',
    'datatablesFixedColumns',
    'datetimepicker'
];

//    echo 'Email Subscription';
?>

@extends('layouts.main')

@section('title', 'Email Subscription')

@section('breadcrumb')
    <li class="active">Email Subscription</li>
@endsection

@section('header_script')
@endsection


@section('content')

    <!-- Start: Search panel -->
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Search</h6>
        </div>
        <div class="panel-body">
            {!! Form::open(['autocomplete' => 'off', 'class'=> 'form-horizontal','id'=> 'search-form']) !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label>Subscription Date (From) </label><span class="text-danger">*</span>
                            {{ Form::text('date-start', session('input')['date-start'], [
                            'id' => 'date-start',
                            'class' => 'form-control pickadate-format',
                            'placeholder' => 'DD/MM/YYYY HH:MM'
                            ]) }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label>Subscription Date (To) </label><span class="text-danger">*</span>
                            {{ Form::text('date-end', session('input')['date-end'], [
                            'id' => 'date-end',
                            'class' => 'form-control pickadate-format',
                            'placeholder' => 'DD/MM/YYYY HH:MM'
                            ]) }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="clearfix"></div>
                <div class="row">

                    {{ Form::button('<i class="icon-search4"></i> Search', [
                    'type' => 'submit',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                    ]) }}

                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- End: New campaign panel -->

    <!-- Start: Campaign list panel -->

    <div class="panel">
        <div class="panel-body table-responsive">
            <table class="table table-border-gray table-striped table-hover datatable-dom-position" id="subscribe-table"
                   data-page-length="10" width="100%">
                <thead>
                <tr>
                    <th class="" width="50">No.</th>
                    <th class="" width="150">Subscription Date</th>
                    <th class="" width="150">Email</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="3" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- End: Campaign list panel -->

@endsection


@section('footer_script')


    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('\App\Http\Requests\EmailSubscriptionSearchRequest', '#search-form') !!}


    @include('common._datetime_script_subscription', [
    'format_start'  => 'd/m/Y H:i',
    'format_end'    => 'd/m/Y H:i',
    'refer_start'   => '#date-start',
    'refer_end'     => '#date-end',
    'editable'      => true,
    'timepicker'    => true,
    'timefixed'     => false
    ])

    <script type="text/javascript">
        var $table = $('#subscribe-table');
        var $thead = $table.find('thead');
        var $tbody = $table.find('tbody');

        console.log(typeof $('#date-start').val());
        //set dataTabel
        var oTable = $table.on('error.dt', function (e, settings, techNote, message) {
            new PNotify({text: 'Error connection', type: 'error'});
        }).DataTable({
            deferRender: true,
            scrollY: true,
            scrollX: true,
            scrollCollapse: true,
            scroller: true,
            lengthMenu: [10, 50, 100, 500, 1000],
            processing: false,
            serverSide: true,
            searching: false,
            retrieve: true,
            destroy: true,
            order: [[1, "desc"]],
            cache: true,
            dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
            language: {
                lengthMenu: '<span>Show :</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
            },
            ajax: {
                url: $("meta[name='root-url']").attr('content') + '/email_subscription/list',
                type: 'GET',
                data: function (d) {
                    console.log(d);
                    d.search = $('#search-form').serializeArray();
                },
                error: function (xhr, error, thrown) {
                    if (xhr.responseJSON.expired) {
                        swal({
                                title: "Error!",
                                text: 'Session Expired',
                                type: "error",
                                confirmButtonText: "OK"
                            },
                            function () {
                                location.reload();
                            });
                    } else {
                        new PNotify({text: 'Error connection', type: 'error'});
                        $tbody.children().remove();
                        $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
                    }
                }
            },
            fnServerParams: function (data) {
                data['order'].forEach(function (items, index) {
                    data['order'][index]['column'] = data['columns'][items.column]['name'];
                });
            },
            columns: [
                {data: 'number', name: 'number', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'email', name: 'email'}
            ]
        });

        $('div.datatable-header').append(`
        @include('common._print_button')&nbsp;
    `);

        $('.print-report').on('click', function (event) {
            event.preventDefault();
            var data = oTable.ajax.params();
            var search = {
                start: data.start,
                length: data.length,
                search: data.search,
                order: data.order
            };

            window.location.replace($("meta[name='root-url']").attr('content') + '/email_subscription/report?' + $.param(search));
        });

        $('#search-form').on('submit', function (e) {
            if (!$(this).find(".has-error")) {
                oTable.draw();
            }
            e.preventDefault();
        });

        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });

    </script>



@endsection


