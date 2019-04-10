@extends('layouts.main')

@section('title','Normal Delivery Fee')

@section('breadcrumb')
    <li class="active"><a href="/{{ $url['normal']['index'] }}">Normal Delivery Fee</a></li>
@endsection

@section('header_script')
@endsection

@section('content')
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title font-bold">Normal Delivery Fee Configuration</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-border-gray" style="border-top: 1px solid #dddddd;">
                        <col width="90%" />
                        <col width="10%" />
                        <thead>
                            <tr>
                                <th class="font-bold border-gray">Delivery Fee and Condition</th>
                                <th class="font-bold border-gray text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="label-block margin-bottom-10">
                                        <span class="label label-primary">TH</span>
                                    </div>
                                    <ul>
                                    @foreach($normal_fee['th'] as $normal_fee_th)
                                        <li>{{ $normal_fee_th }}</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td class="border-gray vertical-top" rowspan="2">
                                    <p class="text-center">
                                        <a id="btn_edit" href="/delivery_fee/normal/edit" data-toggle="tooltip" title="edit"><i class="glyphicon glyphicon-pencil"></i></a>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="label-block margin-bottom-10">
                                        <span class="label label-primary">EN</span>
                                    </div>
                                    <ul>
                                    @foreach($normal_fee['en'] as $normal_fee_th)
                                        <li>{{ $normal_fee_th }}</li>
                                    @endforeach
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_script')
@endsection