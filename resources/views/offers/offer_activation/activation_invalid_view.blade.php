@extends('microsites.microsite_layout')
@section('page-title')
  <title>Offer Activation</title>
@endsection
@section('link-css')
  <link rel="stylesheet" href="{{ asset('assets/microsite/css/style.css') }}" type="text/css">
@endsection
@section('page-content')
  <header style="background: #e5e9ec;"></header>
  <!--container starts-->
  <div class="container">
    <!--body content-->
    <div class="row maincontent">
      <!--Offer activation status-->
      <div class="col-md-12 feedback">
      </div><!--Offer Activation status ends here-->
      <div class="clearfix"></div><br><br>
      <h2 class="color1">
        <span style="color:#e4284e;"><strong>{{$notice}}</strong></span>
      </h2>
    </div><!--body content ends here-->
  </div><!--.container ends here-->
@endsection