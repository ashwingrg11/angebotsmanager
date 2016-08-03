@extends('microsites.microsite_layout')
@section('page-title')
  <title>Ihr Angebotsmanager - Aktivierungsstatus<</title>
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
        <h4>{{ $offer_details[0]->projects->name  }}</h4>
      </div><!--main title ends-->
      @if($offer_details[0]->projects->logo)
      <div class="row">
        <div class="visible-xs image2">
        <span><img src="{{ asset('images/projects').'/'.$offer_details[0]->projects->logo }}" class="img-responsive"></span>
        </div>
      </div>
      @endif
    </header><!--header ends here-->
    <?php //$this->load->view('notification');?>
    <!--body content-->
    <div class="row maincontent">
      <!--welcome user-->
      <h4>Willkommen <span class="name">{{ $offer_details[0]->contacts->first_name.' '.$offer_details[0]->contacts->last_name }}</span></h4>
      <!--welcome user-->
      @include('microsites.shared.notifications')
      <!--feedback column-->
      <div class="col-md-4 feedback">
        <h4 class="columntitle">Ihr Angebotsmanager - Aktivierungsstatus</h4>
        <p class="color1">
          <span style="color:#002846;"><strong>{{$notice}}</strong></span>
          <p>{{--$offer_details[0]->contacts->language_id--}}</p>
        </p>
      </div><!--feedback column ends here-->
      <!--details column-->
      <div class="col-md-7 col-md-offset-1 offerdetails">
        <h4 class="columntitle">Ihr Angebot im Überblick</h4>
        <div class="row">
          <h3 class="title" style="padding-left:1%;">{{ $offer_details[0]->title_de }}</h3>
          <div class="row">
            <p><span class="color2">Angebot gültig ab: </span><span>{{Carbon\Carbon::createFromFormat('d/m/Y', $offer_details[0]->valid_from)->format('Y-m-d')}}</span></p>
            <p><span class="color2">Angebot gültig bis: </span><span>{{Carbon\Carbon::createFromFormat('d/m/Y', $offer_details[0]->valid_until)->format('Y-m-d')}}</span></p>
            @if($offer_details[0]->offer_de)
              <p><span class="color2">Angebot: </span><span>{!! nl2br(e($offer_details[0]->offer_de)) !!}</span></p>
            @endif
            @if($offer_details[0]->benefit_de)
              <p><span class="color2">Vorteil: </span><span>{!! nl2br(e($offer_details[0]->benefit_de)) !!}</span></p>
            @endif
            @if($offer_details[0]->price_de)
              <p><span class="color2">Preis: </span><span>{!! nl2br(e($offer_details[0]->price_de)) !!}</span></p>
            @endif
            @if($offer_details[0]->further_information_de || $offer_details[0]->how_to_book_de)
              <h3 class="title" style="padding-left:1%;">Weitere Informationen und Inanspruchnahme</h3>
              @if($offer_details[0]->further_information_de)
                <p>{!! nl2br(e($offer_details[0]->further_information_de)) !!}</p>
              @endif
              @if($offer_details[0]->how_to_book_de)
                <p>{!! nl2br(e($offer_details[0]->how_to_book_de)) !!}</p>
              @endif
            @endif
          </div>
        </div>
      </div><!--details column ends here-->
      <div class="clearfix"></div><br><br>
    </div><!--body content ends here-->
  </div><!--.container ends here-->
@endsection