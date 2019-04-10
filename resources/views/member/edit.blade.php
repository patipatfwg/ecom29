<?php
$scripts = [
    'select2',
    'datetimepicker',
    'sweetalert'
];
?>

@extends('layouts.main')

@section('title', 'Member Detail')

@section('breadcrumb')
<li><a href="{{ url('/member') }}">Member</a></li>
<li class="active"><?php echo array_get($result, 'online_customer_id', ''); ?></li>
@endsection

@section('header_script')@endsection

@section('content')

<div class="panel panel-flat">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-profile"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Online Customer ID</h6>
                        <?php echo array_get($result, 'online_customer_id', ''); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-profile"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Member Number</h6>
                        <?php echo array_get($result, 'makro_member_card', ''); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-profile"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Registered Store</h6>
                        <?php echo array_get($result, 'makro_register_store_name.th', ''); ?>
                        <?php if(isset($result['makro_register_store_id']) && $result['makro_register_store_id']!="") { echo "(".$result['makro_register_store_id'].")"; } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-calendar2"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Date Registered At Store</h6>
                        {{ !empty($result['makro_register_date'])? convertDateTime($result['makro_register_date'], 'Y-m-d', 'd/m/Y') : '' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-profile"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Customer Type</h6>
                        <?php echo array_get($result, 'business.shop_type', ''); ?>
                        <?php if(isset($result['business']['shop_type_id']) && $result['business']['shop_type_id']!="") { echo "(".$result['business']['shop_type_id'].")"; } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-user"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Username</h6>
                        <?php echo !empty($result['username']) ? $result['username'] : ''; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-calendar2"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Registration Date</h6>
                        {{ !empty($result['created_at'])? convertDateTime($result['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '' }}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-20 mt-20">
                <div class="media">
                    <div class="media-left">
                        <div class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded btn-sm legitRipple">
                            <i class="icon-calendar2"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-semibold">Last Login Date</h6>
                        {{ !empty($result['last_login_date'])? convertDateTime($result['last_login_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @include('member.form._form_profile')
    @include('member.form._form_address')
</div>

@endsection

@section('footer_script')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('\App\Http\Requests\ProfileRequest', '#form-profile') !!}
{!! JsValidator::formRequest('\App\Http\Requests\ProfileAddressRequest', '#form-profile-address') !!}
{!! JsValidator::formRequest('\App\Http\Requests\BusinessRequest', '#form-tax') !!}
{!! JsValidator::formRequest('\App\Http\Requests\BusinessAddressRequest', '#form-bill') !!}
{{ Html::script('js/members/form.js') }}

@include('common._call_ajax')
@include('common._datetime_script', [
    'refer' => '#birth_day',
    'format' => 'd/m/Y'
])

@endsection