<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Blatt1</title>
  <link rel="stylesheet" href="{{ asset('assets/css/offers_reporting_export.css') }}">
</head>
<body>
  <table border="1" cellspacing="0">
    <tbody>
      <!--reporting file heading-->
      <tr>
        <td width="25" height="20" style="font-size: 12px;" valign="middle"><strong>Project(s):</strong></td>
        <td>
          <?php $sn = 1; ?>
          @foreach($project_names as $project_name)
            @if($sn != 1) | @endif{{ $project_name }}
            <?php $sn++; ?>
          @endforeach
        </td>
      </tr>
      <tr>
        <td width="25" height="20" style="font-size: 12px;" valign="middle"><strong>Placement(s):</strong></td>
        <td>
          <?php $sn = 1; ?>
          @foreach($placement_names as $placement_name)
            @if($sn != 1) | @endif{{ $placement_name }}
            <?php $sn++; ?>
          @endforeach
        </td>
      </tr>
      <tr>
        <td width="25" height="20" style="font-size: 12px;" valign="middle"><strong>Report Date:</strong></td>
        <td height="20" valign="middle">{{ \Carbon\Carbon::now()->format('Y-m-d h:i A') }}</td>
      </tr>
      <tr></tr>
      <!--table headings-->
      <tr>
        <td width="25" height="20" valign="middle"><strong>Project</strong></td>
        <td width="25" height="20" valign="middle"><strong>Partner Name</strong></td>
        <td width="25" height="20" valign="middle"><strong>Language - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Title - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>First Name - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Last Name - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Greeting - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Company - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Department - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Job Title - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Email - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Phone - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Mobile Phone - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Notes - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Street 1 - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Street 2 - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Street 3 - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Postcode - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>City - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>County - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>State - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Country - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Display Address - Partner Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Title (EN)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Title (DE)</strong></td>
        <td width="25" height="20" valign="middle"><strong>General Offer Type</strong></td>
        <td width="25" height="20" valign="middle"><strong>Detailed Offer Type</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Type Category</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Location</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Valid From</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Valid Until</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Street 1</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Street 2</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Street 3</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Postal Code</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer City</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer County</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer State</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Country</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Market</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer (EN)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer (DE)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Privilege/Benefit (EN)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Privilege/Benefit (DE)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Price (EN)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Price (DE)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Further Information (EN)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Further Information (DE)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Reservation Telephone</strong></td>
        <td width="25" height="20" valign="middle"><strong>Reservation Fax</strong></td>
        <td width="25" height="20" valign="middle"><strong>Reservation Email</strong></td>
        <td width="25" height="20" valign="middle"><strong>Reservation URL</strong></td>
        <td width="25" height="20" valign="middle"><strong>How to Book (EN)</strong></td>
        <td width="25" height="20" valign="middle"><strong>How to Book (EN)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Exclusive Advantage</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Note</strong></td>
        <td width="25" height="20" valign="middle"><strong>JI Primary Contact</strong></td>
        <td width="25" height="20" valign="middle"><strong>JI Secondary User(s)</strong></td>
        <td width="25" height="20" valign="middle"><strong>Contract Status</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Status</strong></td>
        <td width="25" height="20" valign="middle"><strong>Editorial Note</strong></td>
        <td width="25" height="20" valign="middle"><strong>Communication Package</strong></td>
        <td width="25" height="20" valign="middle"><strong>Offer Activated</strong></td>
        <td width="25" height="20" valign="middle"><strong>Link to Microsite 1</strong></td>
        <td width="25" height="20" valign="middle"><strong>Link to Microsite 2</strong></td>
      </tr><!--table headings-->
      @if($offers->isEmpty() && $non_assigned_contacts->isEmpty())
        <tr>
          <td width="25" height="20" valign="middle">No data.</td>
        </tr>
      @else
        <!--offer-contacts-->
        @foreach ($offers as $offer)
          <!--table data-->
          <tr>
            <td width="25" height="20" valign="middle">{{ $offer->projects->name or 'N/A'}}</td>
            <td width="25" height="20" valign="middle">{{ $offer->partners->partner_name or 'N/A'}}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->languages->label or 'N/A'}}</td>
            <td width="25" height="20" valign="middle">
            @if($offer->contacts->title)
              {{ ucfirst($offer->contacts->title) }}
            @else
              N/A
            @endif
            </td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->first_name or 'N/A'}}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->last_name or 'N/A'}}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->greeting or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->company or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->department or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->job_title or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->email or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $offer->contacts->phone or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle"><span>{{ $offer->contacts->mobile_phone or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">
              @if($offer->contacts->notes)
                {!! nl2br(e($offer->contacts->notes)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->address->street1 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->address->street2 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->address->street3 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $offer->contacts->address->post_code or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->address->city or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $offer->contacts->address->county or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">{{ $offer->contacts->address->state or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">
              @if($offer->contacts->address->countries)
                {{ $offer->contacts->address->countries->label }}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->contacts->address->display_address)
                {!! nl2br(e($offer->contacts->address->display_address)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">{{ $offer->title_en or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->title_de or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">
              @if($offer->general_offer_type_masterlist)
                {{ $offer->general_offer_type_masterlist->label }}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->detailed_offer_type_masterlist)
                {{ $offer->detailed_offer_type_masterlist->label }}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->offer_type_category_masterlist)
                {{ $offer->offer_type_category_masterlist->label }}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">{{ $offer->location or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from)->format('Y-m-d') }}</td>
            <td width="25" height="20" valign="middle">{{ Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until)->format('Y-m-d') }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->street1 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->street2 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->street3 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $offer->postal_code or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">{{ $offer->city or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $offer->county or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">{{ $offer->state or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">
              @if($offer->countries)
                {{ $offer->countries->label }}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">{{ $offer->market or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">
              @if($offer->offer_en)
                {!! nl2br(e($offer->offer_en)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->offer_de)
                {!! nl2br(e($offer->offer_de)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->benefit_en)
                {!! nl2br(e($offer->benefit_en)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->benefit_de)
                {!! nl2br(e($offer->benefit_de)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->price_en)
                {!! nl2br(e($offer->price_en)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->price_de)
                {!! nl2br(e($offer->price_de)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->further_information_en)
                {!! nl2br(e($offer->further_information_en)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->further_information_de)
                {!! nl2br(e($offer->further_information_de)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle"><span>{{ $offer->reservation_telephone or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">{{ $offer->reservation_fax or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $offer->reservation_email or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">
              @if($offer->reservation_url)
                <a href="{{$offer->reservation_url}}">{{$offer->reservation_url}}</a>
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->how_to_book_en)
                {!! nl2br(e($offer->how_to_book_en)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->how_to_book_de)
                {!! nl2br(e($offer->how_to_book_de)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->exclusive_advantage)
                {!! nl2br(e($offer->exclusive_advantage)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->note)
                {!! nl2br(e($offer->note)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              {{ $offer->ji_contact->first_name.' '.$offer->ji_contact->last_name }}
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->users->isEmpty())
                N/A
              @else
                <?php $sn = 1; ?>
                @foreach ($offer->users as $secondary_user)
                  @if($sn != 1) | @endif{{ $secondary_user->first_name.' '.$secondary_user->last_name }}
                  <?php $sn++; ?>
                @endforeach
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->contract_status_id)
                {{ $offer->contract_status->label }}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if(!$offer->offer_status->isEmpty())
                <?php $sn = 1; ?>
                @foreach($offer->offer_status as $offer_status)
                  {{ $sn.') ' }} {{ ucfirst($offer_status->label).' --' }}@if($offer_status->pivot->type == "na") {{ "N/A" }} @else {{ "Yes" }} @endif <br>
                <?php $sn += 1; ?>
                @endforeach
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($offer->editorial_note)
                {!! nl2br(e($offer->editorial_note)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">{{ ucfirst($offer->has_communication_package) }}</td>
            <td width="25" height="20" valign="middle">
              @if($offer->activation_flag)
                Yes
              @else
                No
              @endif
            </td>
            <?php
              $language = "en";
              if($offer->contacts->language_id == 2){
                $language = "de";
              }
              $microsite1_flag = false;
            ?>
            <td width="25" height="20" valign="middle">
              @foreach ($offer->offer_reports as $offer_report)
                @if($offer_report->report_type == "microsite1")
                  @if($offer_report->for_month == Carbon\Carbon::now()->format('M'))
                    <a href="{{ URL::to('/').'/microsite/'.$language.'/1/'.$offer_report->report_code }}" target="_blank">{{URL::to('/').'/microsite/'.$language.'/1/'.$offer_report->report_code }}</a>
                    <?php $microsite1_flag = true; break; ?>
                  @endif
                @endif
              @endforeach
              @if(!$microsite1_flag)
                @foreach ($offer->offer_reports as $offer_report)
                  @if($offer_report->report_type == "microsite1")
                    <a href="{{ URL::to('/').'/microsite/'.$language.'/1/'.$offer_report->report_code }}" target="_blank">{{URL::to('/').'/microsite/'.$language.'/1/'.$offer_report->report_code }}</a>
                    <?php break; ?>
                  @endif
                @endforeach
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @foreach ($offer->offer_reports as $offer_report)
                @if($offer_report->report_type == "microsite2")
                  <a href="{{ URL::to('/').'/microsite/'.$language.'/2/'.$offer_report->report_code }}" target="_blank">{{URL::to('/').'/microsite/'.$language.'/2/'.$offer_report->report_code }}</a>
                @endif
              @endforeach
            </td>
          </tr><!--table data ends-->
        @endforeach
        <!--offer-contacts-->
      @endif
      <!--non assigned contacts-->
      @if(!$non_assigned_contacts->isEmpty())
        @foreach ($non_assigned_contacts as $non_assigned_contact)
          <tr>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->languages->label or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">
            @if($non_assigned_contact->title)
              {{ ucfirst($non_assigned_contact->title) }}
            @else
              N/A
            @endif
            </td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->first_name or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->last_name or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->greeting or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->company or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->department or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->job_title or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->email or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $non_assigned_contact->phone or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle"><span>{{ $non_assigned_contact->mobile_phone or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">
              @if($non_assigned_contact->notes)
                {!! nl2br(e($non_assigned_contact->notes)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->address->street1 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->address->street2 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->address->street3 or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $non_assigned_contact->address->post_code or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->address->city or 'N/A' }}</td>
            <td width="25" height="20" valign="middle"><span>{{ $non_assigned_contact->address->county or 'N/A' }}</span></td>
            <td width="25" height="20" valign="middle">{{ $non_assigned_contact->address->state or 'N/A' }}</td>
            <td width="25" height="20" valign="middle">
              @if($non_assigned_contact->address->countries)
                {{ $non_assigned_contact->address->countries->label }}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">
              @if($non_assigned_contact->address->display_address)
                {!! nl2br(e($non_assigned_contact->address->display_address)) !!}
              @else
                N/A
              @endif
            </td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
            <td width="25" height="20" valign="middle">N/A</td>
          </tr>
        @endforeach
      @endif
      <!--non assigned contacts-->
    </tbody>
  </table>
</body>
</html>