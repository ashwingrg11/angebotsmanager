<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  @if(!null == $data['date_from'] && !null == $data['date_until'])
    @if($data['language'] == "english")
      <h4>Date From: {{Carbon\Carbon::createFromFormat('Y-m-d', $data['date_from'])->format('Y-m-d')}}, Until: {{Carbon\Carbon::createFromFormat('Y-m-d', $data['date_until'])->format('Y-m-d')}}</h4>
    @else
      <h4>Datum Von: {{Carbon\Carbon::createFromFormat('Y-m-d', $data['date_from'])->format('Y-m-d')}}, Bis: {{Carbon\Carbon::createFromFormat('Y-m-d', $data['date_until'])->format('Y-m-d')}}</h4>
    @endif
  @endif
  <h4>@if($data['language'] == "english") Project: @else Projekt: @endif {{$data['project_info'][0]->name}}</h4>
  @if($offers->isEmpty())
    @if($data['language'] == "english")
      <p>There are no valid offers available as per your provided details.</p>
    @else
      <p>Es gibt keine gültigen Angebote nach Ihren vorgesehenen Angaben zur Verfügung</p>
    @endif
  @else
    @foreach ($offers as $offer)
      <hr>
      <?php /*--offer title--*/ ?>
      @if($data['language'] == "english")
        <p><strong>Offer Title: </strong>{!! nl2br(e($offer->title_en)) !!}</p>
      @else
        <p><strong>Angebotstitel: </strong>{!! nl2br(e($offer->title_de)) !!}</p>
      @endif
      <?php /*--offer title ends here--*/ ?>
      <?php /*--offer type categories --*/ ?>
      @if($offer->offer_type_category_masterlist)
        <p><strong>@if($data['language'] == "english")Offer Type Category:@else Angebotskategorie: @endif </strong>{{ $offer->offer_type_category_masterlist->label }}</p>
      @endif
       @if($offer->general_offer_type_masterlist)
        <p><strong>@if($data['language'] == "english")General Offer Type:@else Genereller Angebotstyp: @endif </strong>{{ $offer->general_offer_type_masterlist->label }}</p>
      @endif
      @if($offer->detailed_offer_type_masterlist)
        <p><strong>@if($data['language'] == "english")Detailed Offer Type:@else Detaillierter Angebotstyp: @endif </strong>{{ $offer->detailed_offer_type_masterlist->label }}</p>
      @endif
      <?php /*--offer location --*/ ?>
      @if($offer->location)
        <p><strong>@if($data['language'] == "english")Location:@else Ort: @endif </strong>{!! nl2br(e($offer->location)) !!}</p>
      @endif
      <?php /*--offer location ends --*/ ?>
      <?php /*--editorial note --*/ ?>
      @if($offer->editorial_note)
        <p><strong>@if($data['language'] == "english")Editorial Note:@else Redaktioneller Beitrag:@endif </strong></p>
        <p>{!! nl2br(e($offer->editorial_note)) !!}</p>
      @endif
      <?php /*--editorial note ends --*/ ?>
      <?php /*--offer --*/ ?>
      @if($data['language'] == "english")
        @if($offer->offer_en)
          <p><strong>Offer: </strong></p>
          <p>{!! nl2br(e($offer->offer_en)) !!}</p>
        @endif
      @else
        @if($offer->offer_de)
          <p><strong>Angebot: </strong></p>
          <p>{!! nl2br(e($offer->offer_de)) !!}</p>
        @endif
      @endif
      <?php /*--offer ends --*/ ?>
      <?php /*--privilege/benefit --*/ ?>
      @if($data['language'] == "english")
        @if($offer->benefit_en)
          <p><strong>Privilege/Benefit: </strong></p>
          <p>{!! nl2br(e($offer->benefit_en)) !!}</p>
        @endif
      @else
        @if($offer->benefit_de)
          <p><strong>Vorteil: </strong></p>
          <p>{!! nl2br(e($offer->benefit_de)) !!}</p>
        @endif
      @endif
      <?php /*--privilege/benefit ends here --*/ ?>
      <?php /*--price --*/ ?>
      @if($data['language'] == "english")
        @if($offer->price_en)
          <p><strong>Price: </strong></p>
          <p>{!! nl2br(e($offer->price_en)) !!}</p>
        @endif
      @else
        @if($offer->price_de)
          <p><strong>Preis: </strong></p>
          <p>{!! nl2br(e($offer->price_de)) !!}</p>
        @endif
      @endif
      <?php /*--price ends here --*/ ?>
      <?php /*--offer further info --*/ ?>
      @if($data['language'] == "english")
        @if($offer->further_information_en)
          <p><strong>Further Information: </strong></p>
          <p>{!! nl2br(e($offer->further_information_en)) !!}</p>
        @endif
      @else
        @if($offer->further_information_de)
          <p><strong>Weitere Informationen: </strong></p>
          <p>{!! nl2br(e($offer->further_information_de)) !!}</p>
        @endif
      @endif
      <?php /*--offer further info ends --*/ ?>
      <?php /*--how-to-book --*/ ?>
      @if($data['language'] == "english")
        @if($offer->how_to_book_en)
          <p><strong>How to book: </strong></p>
          <p>{!! nl2br(e($offer->how_to_book_en)) !!}</p>
        @endif
      @else
        @if($offer->how_to_book_de)
          <p><strong>Wie erfolgt die Buchung: </strong></p>
          <p>{!! nl2br(e($offer->how_to_book_de)) !!}</p>
        @endif
      @endif
      <?php /*--how-to-book ends --*/ ?>
      <p><strong>@if($data['language'] == "english")Valid From: @else Gültig von:@endif </strong>{{Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from)->format('Y-m-d')}}</p>
      <p><strong>@if($data['language'] == "english")Valid Until @else Gültig bis:@endif </strong>{{Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until)->format('Y-m-d')}}</p>
      @if($offer->reservation_url)
        <p><strong>@if($data['language'] == "english") Reservation URL: @else Reservierungs-URL: @endif </strong>
          <a href="{{$offer->reservation_url}}">{{$offer->reservation_url}}</a>
        </p>
      @endif
      @if($offer->reservation_telephone)
        <p><strong>@if($data['language'] == "english") Reservation Telephone: @else Reservierungs-Telefonnummer: @endif </strong>{{$offer->reservation_telephone}}</p>
      @endif
      @if($offer->reservation_email)
        <p><strong>@if($data['language'] == "english") Reservation E-Mail: @else Reservierungs-Email:  @endif </strong>{{$offer->reservation_email}}</p>
      @endif
      @if($offer->reservation_fax)
        <p><strong>@if($data['language'] == "english") Reservation Fax: @else Reservierungs-Faxnummer: @endif  </strong>{{$offer->reservation_fax}}</p>
      @endif
      <?php /*--offer note --*/ ?>
      @if($offer->note)
        <p><strong>@if($data['language'] == "english") Note: @else Notiz: @endif  </strong></p>
        <p>{!! nl2br(e($offer->note)) !!}</p>
      @endif
      <?php /*--offer note ends here --*/ ?>
      <?php /*--offer-placements --*/ ?>
      <p><strong>@if($data['language'] == "english") Placements: @else Platzierungen: @endif</strong></p>
      <?php $sn = 1; ?>
      @foreach ($offer->placements as $placement)
        <p>{{ $sn.'. '.$placement->title }}</p>
      <?php $sn++; ?>
      @endforeach
      <?php /*--offer placements --*/ ?>
      <p>
        <strong>@if($data['language'] == "english")Partner Contact Company: @else Partner-Unternehmen: @endif</strong>
        {{$offer->contacts->company}}
      </p>
      <p>
        <strong>@if($data['language'] == "english")Partner Contact Title: @else Anrede der Kontaktperson beim Partner: @endif</strong>
        {{ ucfirst($offer->contacts->title) }}
      </p>
      <p>
        <strong>@if($data['language'] == "english")Partner Contact: @else Kontaktperson beim Partner: @endif</strong>
        {{$offer->contacts->first_name.' '.$offer->contacts->last_name}}
      </p>
      <p>
        <strong>@if($data['language'] == "english")Partner Contact Email: @else Email der Kontaktperson beim Partner: @endif</strong>
        {{$offer->contacts->email}}
      </p>
      @if($offer->contacts->phone)
        <p><strong>@if($data['language'] == "english")Partner Contact Phone: @else Telefonnummer der Kontaktperson beim Partner: @endif</strong>{{$offer->contacts->phone}}</p>
      @endif
      @if($offer->contacts->mobile_phone)
        <p><strong>@if($data['language'] == "english")Partner Contact Mobile Phone: @else Mobil-Nummer der Kontaktperson beim Partner: @endif</strong>{{$offer->contacts->mobile_phone}}</p>
      @endif
    @endforeach
  @endif
</body>
</html>
