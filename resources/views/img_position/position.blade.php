@extends('layouts.main')

@section('title', $title)

@section('breadcrumb')
    <li><a href="/{{ $page }}">{{ $title }}</a></li>
    @if($referUrl != '')
        <li><a href="{{ $referUrl }}">{{ $referLabel }}</a></li>
    @endif
    <li class="active">Position</li>
@endsection

@section('header_script')
@endsection

@section('content')

<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Position</h6>
    </div>
    <div class="panel-body">
    @foreach ($imgUrl as $url)
        <div class="row">
            <div class="col-lg-12">
                <img style="max-width: 100%" src="{{ $url }}"></img>
            </div>
        </div>
    @endforeach
    </div>
</div>

@endsection