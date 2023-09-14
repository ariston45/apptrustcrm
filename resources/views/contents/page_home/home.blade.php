@extends('layout.app')
@section('title')
Home
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title">Home</h4>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Step one</a></li>
<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="empty">
  <div class="empty-img"><img src=" {{ url('static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="168" alt="ilustration image"></div>
  <p class="empty-title"></p>
  <p class="empty-subtitle text-muted">Data is empty, if you add new something data, you can click button below.</p>
  <div class="empty-action">
    <a href="{{ url('customer') }}" class="btn btn-primary">
      <i class="custom-icn ri-add-line"></i>
      Add New Data
    </a>
  </div>
</div>
@endsection
@push('style')
  
@endpush
@push('script')
  
@endpush