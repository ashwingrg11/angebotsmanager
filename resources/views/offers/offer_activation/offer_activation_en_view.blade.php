@extends('microsites.microsite_layout')
@section('page-title')
  <title>Your Offer Tracking Manager - Activation Status</title>
@endsection
@section('link-css')
  <link rel="stylesheet" href="{{ asset('assets/microsite/css/style.css') }}" type="text/css">
@endsection
@section('page-content')
  <!--container starts-->
  <div class="container">
    <!--header starts-->
    <header>
      <!--main title-->
      <div class="row">
        @if($offer_details[0]->projects->logo)
          <div class="image hidden-xs">
            <span><img src="{{ asset('images/projects').'/'.$offer_details[0]->projects->logo }}" class="img-responsive"></span>
          </div>
        @endif
        <h4>{{$offer_details[0]->projects->name  }}</h4>
      </div><!--main title ends-->
      @if($offer_details[0]->projects->logo)
      <div class="row">
        <div class="visible-xs image2">
        <span><img src="{{ asset('images/projects').'/'.$offer_details[0]->projects->logo }}" class="img-responsive"></span>
        </div>
      </div>
      @endif
    </header><!--header ends here-->
    <!--body content-->
    <div class="row maincontent">
      <!--welcome user-->
      <h4>Welcome <span class="name">{{ $offer_details[0]->contacts->first_name.' '.$offer_details[0]->contacts->last_name }}</span></h4>
      <!--welcome user-->
      @include('microsites.shared.notifications')
      <!--Offer activation status-->
      <div class="col-md-4 feedback">
        <h4 class="columntitle">Your Offer Tracking Manager - Activation Status</h4>
        <p class="color1">
          <span style="color:#002846;"><strong>{{$notice}}</strong></span>
        </p>
      </div><!--Offer Activation status ends here-->
      <!--details column-->
      <div class="col-md-7 col-md-offset-1 offerdetails">
        <h4 class="columntitle">Your Offer Details</h4>
        <div class="row">
          <h3 class="title" style="padding-left:1%;">{{ $offer_details[0]->title_en }}</h3>
          <div class="row">
            <p><span class="color2">Offer valid from: </span><span>{{ Carbon\Carbon::createFromFormat('d/m/Y', $offer_details[0]->valid_from)->format('Y-m-d')}}</span></p>
            <p><span class="color2">Offer valid until: </span><span>{{ Carbon\Carbon::createFromFormat('d/m/Y', $offer_details[0]->valid_until)->format('Y-m-d')}}</span></p>
            @if($offer_details[0]->offer_en)
              <p><span class="color2">Offer: </span><span>{!! nl2br(e($offer_details[0]->offer_en)) !!}</span></p>
            @endif
            @if($offer_details[0]->benefit_en)
              <p><span class="color2">Privilege / Benefit: </span><span>{!! nl2br(e($offer_details[0]->benefit_en)) !!}</span></p>
            @endif
            @if($offer_details[0]->price_en)
              <p><span class="color2">Price: </span><span>{!! nl2br(e($offer_details[0]->price_en)) !!}</span></p>
            @endif
            @if($offer_details[0]->further_information_en || $offer_details[0]->how_to_book_en)
              <h3 class="title" style="padding-left:1%;">Further Information and How to Book</h3>
              @if($offer_details[0]->further_information_en)
                <p>{!! nl2br(e($offer_details[0]->further_information_en)) !!}</p>
              @endif
              @if($offer_details[0]->how_to_book_en)
                <p>{!! nl2br(e($offer_details[0]->how_to_book_en)) !!}</p>
              @endif
            @endif
          </div>
        </div>
      </div><!--details column ends here-->
      <div class="clearfix"></div><br><br>
    </div><!--body content ends here-->
  </div><!--.container ends here-->
@endsection