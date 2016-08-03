<?php
	$reported_requests_total = [];
	$reported_reservations_total = [];
	$reported_participants_total = [];
?>
<html>
	<head>
	  {{-- <meta charset="UTF-8"> --}}
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <title>Partner Reporting</title>
	  <link rel="stylesheet" href="{{ asset('assets/css/offers_reporting_export.css') }}">
	</head>
	<body>
		<table border="1">
		  <!--reporting file heading-->
      <tr>
        <td width="30" height="25" style="font-size: 18px;" valign="middle">Partner Reporting on Offer Success</td>
      </tr>
      <tr></tr>
      <tr>
        <td width="30" height="20" valign="middle"><strong>Project:</strong></td>
        <td width="30" valign="middle" style="wrap-text: false">{{ $user_data['project_info'][0]->name }}</td>
      </tr>
      <tr>
        <td width="30" height="20" valign="middle"><strong>Placement(s):</strong></td>
        <td valign="middle" style="wrap-text: false">
          <?php $placement_sn = 1; ?>
          @if(isset($user_data['placements']))
            @foreach ($user_data['placements'] as $placement)
              @if($placement_sn != 1) | @endif {{ $placement }}
              <?php $placement_sn++; ?>
            @endforeach
          @else
            N/A
          @endif
        </td>
      </tr>
      <tr>
        <td width="30" height="20" valign="middle"><strong>Reporting Timeframe:</strong></td>
        <td width="30" valign="middle">
          @if(isset($user_data['date_from']))
            {{ \Carbon\Carbon::createFromFormat('m-Y', $user_data['date_from'])->format('M Y').' - '.\Carbon\Carbon::createFromFormat('m-Y', $user_data['date_until'])->format('M Y') }}
          @else
            N/A
          @endif
        </td>
      </tr>
      <tr>
        <td width="30" height="20" valign="middle"><strong>Report Date:</strong></td>
        <td width="30" valign="middle">{{ \Carbon\Carbon::now()->format('Y-m-d h:ia') }}</td>
      </tr>
      <tr></tr>
      <!--reporting file heading ends-->
		  <tr>
		  	<td width="50" class="bg-grey" colspan="2"><strong>Offer validity periods</strong></td>
		  </tr>
		  <tr></tr>
		  <tr class="bordered">
		  	<td width="50"><strong>Offer Title</strong></td>
		  	<td width="30"><strong>Communication Package</strong></td>
		  	@if(!$offers->isEmpty())
		  		<?php
		  	  if(isset($data['min_start'])){
		  	    // $new_min_start = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
		  	    $new_min_start = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
		  	    while ($new_min_start <= \Carbon\Carbon::createFromFormat('d/m/Y', $data['max_end'])){
			  	?>
				  	<td colspan="4" align="center"><strong>{{$new_min_start->format('M')}}  {{$new_min_start->format('Y')}}</strong></td>
        	<?php $new_min_start->modify('first day of next month'); }} ?>
		  	@endif
		  </tr>
		  @if($offers->isEmpty())
				<tr><td>No Valid Offers.</td></tr>
		  @else
			  <!--offers-->
			  <?php $month_date = \Carbon\Carbon::createFromFormat('d/m/Y',$data['min_start']); $amt_offers = 0; ?>
		  	@foreach($offers as $offer)
		  	<?php
		  	  $colored = '';
		  	  $offer_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
		  	  $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
		  	?>
			  <tr class="bordered">
			  	<td>{{$offer->title_en or $offer->title_de}}</td>
			  	<td align="center" style="vertical-align: middle;">{{ ucfirst($offer->has_communication_package) }}</td>
			  	<?php $quarter_flag = 0;for($i=1;$i<=$data['no_months']*4; $i++){ ?>
			  	<?php
			  	  $quarter_flag = $quarter_flag + 1;
			  	  if($i==1){
			  	    // $month_date = \Carbon\Carbon::createFromFormat('m-Y', $data['min_start']);
			  	    $month_date = \Carbon\Carbon::createFromFormat('d/m/Y',$data['min_start']);
			  	  }
			  	  else{
			  	    $t = $i-1;
			  	    if($t == 4 || $t % 4 == 0){
			  	      // $month_date->addMonth();
			  	      $month_date->modify('first day of next month');
			  	    }
			  	  }
			  	  if($quarter_flag > 4){
			  	    $quarter_flag = 1;
			  	  }
			  	  if(($offer_from->format('Y') == $month_date->format('Y')) && ($offer_from->format('m') == $month_date->format('m'))){
			  	    $quarter = Helper::quarterOfMonth($offer_from->format('d'));
			  	    if($quarter == $quarter_flag){
			  	      $colored = "yes";
			  	    }
			  	  }
			  	  else{
			  	    if($month_date > $offer_until){
			  	      if($month_date->format('Y') != $offer_until->format('Y') || $month_date->format('m') != $offer_until->format('m')){
			  	        $colored = "no";
			  	      }
			  	    }
			  	  }
			  	  if(($offer_until->format('Y') == $month_date->format('Y')) && ($offer_until->format('m') == $month_date->format('m'))){
			  	    $quarter = Helper::quarterOfMonth($offer_until->format('d'));
			  	    if($quarter >= $quarter_flag){
			  	      // $colored = "yes";
			  	      if($quarter == $quarter_flag){
			  	        $colored = "yes";
			  	      }
			  	    }else{
			  	      $colored = "no";
			  	    }
			  	  }
			  	?>
			  	<td width="3"
					@if($colored == "yes")
					  class="bg-lightgreen"
					@endif
			  	></td>
			  	<?php } ?>
			  </tr>
		  	@endforeach
		  	<!--sample of offer, offers end-->
			  <tr></tr>
			  <tr>
			  	<td class="bg-grey" height="25" colspan="2"><strong>Offer status report (expressing above chart in numbers)</strong></td>
			  </tr>
			  <tr></tr>
			  <!--offers valid within the month-->
			  <tr class="bordered">
			  	<td width="50" height="25" colspan="2">Number of offers valid within the respective month (some only part of the month)</td>
		  		<?php for($i=1;$i<=$data['no_months']; $i++){ ?>
			  	  	<td colspan="4" align="center" style="vertical-align: middle;">{{$data['valid_offers'][$i-1]}}</td>
			  	<?php } ?>
			  </tr><!--offers valid within the month-->
			  <!--Part of the month offers-->
			  <tr class="bordered">
			  	<td width="50" height="25" colspan="2">Number of offers valid for only part of the respective month</td>
			  	<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  	  	<td colspan="4" align="center">{{$data['part_offers'][$i-1]}}</td>
			  	<?php } ?>
			  </tr><!--Part of the month offers-->
			  <!--Emails nos.-->
			  <tr class="bordered">
			  	<td width="50" height="25" colspan="2">Number of Microsite 1 Emails that will be sent requesting feedback for this month</td>
			  	<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
			  	  <td colspan="4" align="center" style="vertical-align: middle;">{{$data['msone_emails'][$i-1]}}</td>
			  	<?php } ?>
			  </tr><!--Emails nos.-->
			  <!--Offers expired-->
			  <tr class="bordered">
			  	<td width="50" height="25" colspan="2">Number of Microsite 2 Emails that will be sent requesting feedback for this month</td>
			  	<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
			  	  <td colspan="4" align="center" style="vertical-align: middle;">{{$data['mstwo_emails'][$i-1]}}</td>
			  	<?php } ?>
			  </tr><!--Expired offers end here-->
			  <tr></tr>
			  <tr></tr>
			  <tr></tr>
			  <tr>
			  	<td height="35" class="bg-blue" colspan="2" class="bg-blue"><strong>Reported requests for the respective month <br>(reporting can only happen during the subsequent month)</strong></td>
			  </tr>
			  <tr></tr>
			  <!--requests reported-->
				<tr class="bordered">
			  	<td width="50"><strong>Offer Title</strong></td>
			  	<td width="30"><strong>Communication Package</strong></td>
			  	@if(!$offers->isEmpty())
			  		<?php
			  	  if(isset($data['min_start'])){
			  	    $new_min_start = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
			  	    while ($new_min_start <= \Carbon\Carbon::createFromFormat('d/m/Y', $data['max_end'])){
				  	?>
			  	  		<td colspan="4" align="center"><strong>{{$new_min_start->format('M')}}  {{$new_min_start->format('Y')}}</strong></td>
	        	<?php $new_min_start->modify('first day of next month'); }} ?>
			  	@endif
			  </tr>
			  <?php $no_req_offer_flag = 0; ?>
		  	@foreach($offers as $offer)
			  <tr class="bordered">
			  	<td>{{$offer->title_en or $offer->title_de}}</td>
			  	<td align="center" style="vertical-align: middle;" @if($offer->has_communication_package == "no") class="bg-light-orange" @endif>{{ ucfirst($offer->has_communication_package) }}</td>
			  	<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if ($data['reported_requests'][$no_req_offer_flag][$i-1]['flag'] == "default")
							class="bg-grey"
						@elseif ($data['reported_requests'][$no_req_offer_flag][$i-1]['flag'] == "data_valid")
							class="bg-lightgreen"
						@elseif ($data['reported_requests'][$no_req_offer_flag][$i-1]['flag'] == "value_null")
							class="bg-yellow"
						@elseif ($data['reported_requests'][$no_req_offer_flag][$i-1]['flag'] == "commn_no")
							class="bg-light-orange"
						@elseif ($data['reported_requests'][$no_req_offer_flag][$i-1]['flag'] == "value_pending")
							class="bg-light-red"
						@endif
		  		  >
		  		  	{{ $data['reported_requests'][$no_req_offer_flag][$i-1]['data'] }}
							@if($data['reported_requests'][$no_req_offer_flag][$i-1]['flag'] == "data_valid")
								<?php
									if(!isset($reported_requests_total[$i-1])){
				  		  		$reported_requests_total[$i-1] = 0;
									}
			  		  		$reported_requests_total[$i-1] = $reported_requests_total[$i-1] + $data['reported_requests'][$no_req_offer_flag][$i-1]['data'];
			  		  	?>
			  		  @else
		  		  		<?php
		  		  		if(!isset($reported_requests_total[$i-1])){
			  		  		$reported_requests_total[$i-1] = 0;
								}else{
		  		  		$reported_requests_total[$i-1] = $reported_requests_total[$i-1] + 0;} ?>
							@endif
		  		  </td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
			  </tr>
			  <?php $no_req_offer_flag++; ?>
				@endforeach
				<!--requests reported-->
				<tr></tr>
				<!--aggregate reporting on requests-->
				<tr>
			  	<td height="35" class="bg-blue" colspan="2"><strong>Aggregate reporting on requests for the respective month <br>(for all valid offers)</strong></td>
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers listed in this report for that month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;">{{$offers->count()}}</td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers without validity in the respective month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;" class="bg-grey">{{$offers->count()-$data['valid_offers'][$i-1]}}</td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers in the respective month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if ($data['valid_offers'][$i-1] != 0)
							class="bg-lightgreen"
						@endif
						><strong>{{$data['valid_offers'][$i-1]}}</strong></td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers without communication package</td>
					<!--offers without commn package-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['offers_commn_no'][$i-1] == 0)
							class="bg-grey"
						@else
							class="bg-light-orange"
						@endif
		  		  >
		  		  	@if($data['offers_commn_no'][$i-1] != 0)
			  		  	{{$data['offers_commn_no'][$i-1]}}
		  		  	@else
								n/a
		  		  	@endif
		  		  </td>
		  		<?php } ?>
					<!--offers without commn package-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers with communication package</td>
					<!--offers with commn package-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['offers_commn_yes'][$i-1] == 0)
							class="bg-grey"
						@else
							class="bg-lightgreen"
						@endif
		  		  >
		  		  	@if($data['offers_commn_yes'][$i-1] != 0)
			  		  	<strong>{{$data['offers_commn_yes'][$i-1]}}</strong>
		  		  	@else
			  		  	<strong>n/a</strong>
		  		  	@endif
		  		  </td>
		  		<?php } ?>
					<!--offers with commn package-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers without partner feedback (despite validity and communication package)</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
		  		  @if($data['reported_requests_feedbacks'][$i-1]['without_feedback'] === 'n/a')
							class="bg-grey"
						@elseif($data['reported_requests_feedbacks'][$i-1]['without_feedback'] >= 1)
							class="bg-yellow"
						@elseif($data['reported_requests_feedbacks'][$i-1]['without_feedback'] === 0)
							class="bg-white"
						@else
							class="bg-white"
						@endif
		  		  >
							{{ $data['reported_requests_feedbacks'][$i-1]['without_feedback'] }}
		  		  </td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers with partner feedback</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['reported_requests_feedbacks'][$i-1]['with_feedback'] === 'n/a')
							class="bg-grey"
						@elseif($data['reported_requests_feedbacks'][$i-1]['with_feedback'] >= 1)
							class="bg-lightgreen"
						@elseif($data['reported_requests_feedbacks'][$i-1]['with_feedback'] === 0)
							class="bg-white"
						@else
							class="bg-white"
						@endif
		  		  >
			  		  <strong>{{ $data['reported_requests_feedbacks'][$i-1]['with_feedback'] }}</strong>
		  		  </td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Aggregate number of requests</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($reported_requests_total[$i-1] === 'n/a')
							class="bg-grey"
						@elseif($reported_requests_total[$i-1] >= 1)
							class="bg-lightgreen"
						@else
							class="bg-white"
						@endif
						><strong>{{$reported_requests_total[$i-1]}}</strong></td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<!--aggregate reporting on requests-->

				<tr></tr>
			  <tr></tr>
			  <tr></tr>
			  <tr>
			  	<td height="40" class="bg-grey" colspan="2"><strong>Reported reservations / bookings / purchase checkouts for the respective month <br>(reporting can only happen during the subsequent month)</strong></td>
			  </tr>
			  <tr></tr>
			  <!--requests reported-->
				<tr class="bordered">
			  	<td width="50"><strong>Offer Title</strong></td>
			  	<td width="30"><strong>Communication Package</strong></td>
			  	@if(!$offers->isEmpty())
			  		<?php
			  	  if(isset($data['min_start'])){
			  	    $new_min_start = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
			  	    while ($new_min_start <= \Carbon\Carbon::createFromFormat('d/m/Y', $data['max_end'])){
				  	?>
				  	  <td colspan="4" align="center"><strong>{{$new_min_start->format('M')}}  {{$new_min_start->format('Y')}}</strong></td>
	        	<?php $new_min_start->modify('first day of next month'); }} ?>
			  	 @endif
			  </tr>
			  <!--reported reservations-->
		  	<?php $no_req_offer_flag = 0; ?>
		  	@foreach($offers as $offer)
			  <tr class="bordered">
			  	<td>{{$offer->title_en or $offer->title_de}}</td>
			  	<td align="center" style="vertical-align: middle;" @if($offer->has_communication_package == "no") class="bg-light-orange" @endif>{{ ucfirst($offer->has_communication_package) }}</td>
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if ($data['reported_reservations'][$no_req_offer_flag][$i-1]['flag'] == "default")
							class="bg-grey"
						@elseif ($data['reported_reservations'][$no_req_offer_flag][$i-1]['flag'] == "data_valid")
							class="bg-lightgreen"
						@elseif ($data['reported_reservations'][$no_req_offer_flag][$i-1]['flag'] == "value_null")
							class="bg-yellow"
						@elseif ($data['reported_reservations'][$no_req_offer_flag][$i-1]['flag'] == "commn_no")
							class="bg-light-orange"
						@elseif ($data['reported_reservations'][$no_req_offer_flag][$i-1]['flag'] == "value_pending")
							class="bg-light-red"
						@endif
		  		  >
		  		  	{{ $data['reported_reservations'][$no_req_offer_flag][$i-1]['data'] }}
		  		  	@if($data['reported_reservations'][$no_req_offer_flag][$i-1]['flag'] == "data_valid")
								<?php
									if(!isset($reported_reservations_total[$i-1])){
				  		  		$reported_reservations_total[$i-1] = 0;
									}
			  		  		$reported_reservations_total[$i-1] = $reported_reservations_total[$i-1] + $data['reported_reservations'][$no_req_offer_flag][$i-1]['data'];
			  		  	?>
			  		  @else
		  		  		<?php
		  		  		if(!isset($reported_reservations_total[$i-1])){
			  		  		$reported_reservations_total[$i-1] = 0;
								}else{
		  		  		$reported_reservations_total[$i-1] = $reported_reservations_total[$i-1] + 0;} ?>
							@endif
		  		  </td>
		  		<?php } ?>
			  </tr>
			  <?php $no_req_offer_flag++; ?>
			  @endforeach
				<!--reported reservations-->
				<tr></tr>
				<!--reported reservations-->
				<tr>
			  	<td height="45" class="bg-grey" colspan="2"><strong>Aggregate reporting on reservations / bookings / purchase checkouts for the respective month <br>(for all valid offers)</strong></td>
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers listed in this report for that month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;">{{$offers->count()}}</td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers without validity in the respective month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;" class="bg-grey">{{$offers->count()-$data['valid_offers'][$i-1]}}</td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers in the respective month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if ($data['valid_offers'][$i-1] != 0)
							class="bg-lightgreen"
						@endif
						><strong>{{$data['valid_offers'][$i-1]}}</strong></td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers without communication package</td>
					<!--offers without commn package-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['offers_commn_no'][$i-1] == 0)
							class="bg-grey"
						@else
							class="bg-light-orange"
						@endif
		  		  >
		  		  	@if($data['offers_commn_no'][$i-1] != 0)
			  		  	{{$data['offers_commn_no'][$i-1]}}
		  		  	@else
								n/a
		  		  	@endif
		  		  </td>
		  		<?php } ?>
					<!--offers without commn package-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers with communication package</td>
					<!--offers with commn package-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['offers_commn_yes'][$i-1] == 0)
							class="bg-grey"
						@else
							class="bg-lightgreen"
						@endif
		  		  >
		  		  	@if($data['offers_commn_yes'][$i-1] != 0)
		  		  	<strong>{{$data['offers_commn_yes'][$i-1]}}</strong>
		  		  	@else
			  		  	<strong>n/a</strong>
		  		  	@endif
		  		  </td>
		  		<?php } ?>
					<!--offers with commn package-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers without partner feedback (despite validity and communication package)</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['reported_reservations_feedbacks'][$i-1]['without_feedback'] === 'n/a')
							class="bg-grey"
						@elseif($data['reported_reservations_feedbacks'][$i-1]['without_feedback'] >= 1)
							class="bg-yellow"
						@elseif($data['reported_reservations_feedbacks'][$i-1]['without_feedback'] === 0)
							class="bg-white"
						@else
							class="bg-white"
						@endif
		  		  >
							{{ $data['reported_reservations_feedbacks'][$i-1]['without_feedback'] }}
		  		  </td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers with partner feedback</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['reported_reservations_feedbacks'][$i-1]['with_feedback'] === 'n/a')
							class="bg-grey"
						@elseif($data['reported_reservations_feedbacks'][$i-1]['with_feedback'] >= 1)
							class="bg-lightgreen"
						@elseif($data['reported_reservations_feedbacks'][$i-1]['with_feedback'] === 0)
							class="bg-white"
						@else
							class="bg-white"
						@endif
		  		  >
			  		  <strong>{{ $data['reported_reservations_feedbacks'][$i-1]['with_feedback'] }}</strong>
		  		  </td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Aggregate number of reservations / bookings / purchase checkouts</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($reported_reservations_total[$i-1] === 'n/a')
							class="bg-grey"
						@elseif($reported_reservations_total[$i-1] >= 1)
							class="bg-lightgreen"
						@else
							class="bg-white"
						@endif
						><strong>{{$reported_reservations_total[$i-1]}}</strong></td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<!--reported reservations-->


				<tr></tr>
			  <tr></tr>
			  <tr></tr>
			  <tr>
			  	<td height="45" class="bg-new-orange" colspan="2"><strong>Reported participants for reservations & bookings / items upon checkout (if applicable) for the respective month <br>(reporting can only happen during the subsequent month)</strong></td>
			  </tr>
			  <tr></tr>
			  <!--requests reported-->
				<tr class="bordered">
			  	<td width="50"><strong>Offer Title</strong></td>
			  	<td width="30"><strong>Communication Package</strong></td>
			  	@if(!$offers->isEmpty())
			  		<?php
			  	  if(isset($data['min_start'])){
			  	    $new_min_start = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
			  	    while ($new_min_start <= \Carbon\Carbon::createFromFormat('d/m/Y', $data['max_end'])){
				  	?>
					  	  <td colspan="4" align="center"><strong>{{$new_min_start->format('M')}}  {{$new_min_start->format('Y')}}</strong></td>
	        	<?php $new_min_start->modify('first day of next month'); }} ?>
			  	 @endif
			  </tr>
		  	<!--reported participants-->
		  	<?php $no_req_offer_flag = 0; ?>
		  	@foreach($offers as $offer)
			  <tr class="bordered">
			  	<td>{{$offer->title_en or $offer->title_de}}</td>
			  	<td align="center" style="vertical-align: middle;" @if($offer->has_communication_package == "no") class="bg-light-orange" @endif>{{ ucfirst($offer->has_communication_package) }}</td>
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if ($data['reported_participants'][$no_req_offer_flag][$i-1]['flag'] == "default")
							class="bg-grey"
						@elseif ($data['reported_participants'][$no_req_offer_flag][$i-1]['flag'] == "data_valid")
							class="bg-lightgreen"
						@elseif ($data['reported_participants'][$no_req_offer_flag][$i-1]['flag'] == "value_null")
							class="bg-yellow"
						@elseif ($data['reported_participants'][$no_req_offer_flag][$i-1]['flag'] == "commn_no")
							class="bg-light-orange"
						@elseif ($data['reported_participants'][$no_req_offer_flag][$i-1]['flag'] == "value_pending")
							class="bg-light-red"
						@endif
		  		  >
		  		  	{{ $data['reported_participants'][$no_req_offer_flag][$i-1]['data'] }}
		  		  	@if($data['reported_participants'][$no_req_offer_flag][$i-1]['flag'] == "data_valid")
								<?php
									if(!isset($reported_participants_total[$i-1])){
				  		  		$reported_participants_total[$i-1] = 0;
									}
			  		  		$reported_participants_total[$i-1] = $reported_participants_total[$i-1] + $data['reported_participants'][$no_req_offer_flag][$i-1]['data'];
			  		  	?>
			  		  @else
		  		  		<?php
		  		  		if(!isset($reported_participants_total[$i-1])){
			  		  		$reported_participants_total[$i-1] = 0;
								}else{
		  		  		$reported_participants_total[$i-1] = $reported_participants_total[$i-1] + 0;} ?>
							@endif
		  		  </td>
		  		<?php } ?>
			  </tr>
			  <?php $no_req_offer_flag++; ?>
			  @endforeach
				<!--reported participants-->
				<!--requests reported-->
				<tr></tr>
				<!--partictipants for reservations-->
				<tr>
			  	<td height="45" class="bg-new-orange" colspan="2"><strong>Aggregate reporting on participants for reservations & bookings / items upon checkout (if applicable) for the respective month <br>(for all valid offers)</strong></td>
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers listed in this report for that month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;">{{$offers->count()}}</td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers without validity in the respective month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;" class="bg-grey">{{$offers->count()-$data['valid_offers'][$i-1]}}</td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers in the respective month</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['valid_offers'][$i-1] != 0)
							class="bg-lightgreen"
						@endif
						><strong>{{$data['valid_offers'][$i-1]}}</strong></td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers without communication package</td>
					<!--offers without commn package-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['offers_commn_no'][$i-1] == 0)
							class="bg-grey"
						@else
							class="bg-light-orange"
						@endif
		  		  >
		  		  	@if($data['offers_commn_no'][$i-1] != 0)
			  		  	{{$data['offers_commn_no'][$i-1]}}
		  		  	@else
								n/a
		  		  	@endif
		  		  </td>
		  		<?php } ?>
					<!--offers without commn package-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of valid offers with communication package</td>
					<!--offers with commn package-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['offers_commn_yes'][$i-1] == 0)
							class="bg-grey"
						@else
							class="bg-lightgreen"
						@endif
		  		  >
		  		  	@if($data['offers_commn_yes'][$i-1] != 0)
			  		  	<strong>{{$data['offers_commn_yes'][$i-1]}}</strong>
		  		  	@else
			  		  	<strong>n/a</strong>
		  		  	@endif
		  		  </td>
		  		<?php } ?>
					<!--offers with commn package-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers without partner feedback (despite validity and communication package)</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['reported_participants_feedbacks'][$i-1]['without_feedback'] === 'n/a')
							class="bg-grey"
						@elseif($data['reported_participants_feedbacks'][$i-1]['without_feedback'] >= 1)
							class="bg-yellow"
						@elseif($data['reported_participants_feedbacks'][$i-1]['without_feedback'] === 0)
							class="bg-white"
						@else
							class="bg-white"
						@endif
		  		  >
							{{ $data['reported_participants_feedbacks'][$i-1]['without_feedback'] }}
		  		  </td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Number of offers with partner feedback</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($data['reported_participants_feedbacks'][$i-1]['with_feedback'] === 'n/a')
							class="bg-grey"
						@elseif($data['reported_participants_feedbacks'][$i-1]['with_feedback'] >= 1)
							class="bg-lightgreen"
						@elseif($data['reported_participants_feedbacks'][$i-1]['with_feedback'] === 0)
							class="bg-white"
						@else
							class="bg-white"
						@endif
		  		  >
			  		  <strong>{{ $data['reported_participants_feedbacks'][$i-1]['with_feedback'] }}</strong>
		  		  </td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<tr class="bordered">
			  	<td height="25" colspan="2">Aggregate number of participants for reservations & bookings / items upon checkout (if applicable)</td>
					<!--Requests data-->
		  		<?php for($i=1; $i<=$data['no_months']; $i++){ ?>
		  		  <td colspan="4" align="center" style="vertical-align: middle;"
						@if($reported_participants_total[$i-1] === 'n/a')
							class="bg-grey"
						@elseif($reported_participants_total[$i-1] >= 1)
							class="bg-lightgreen"
						@else
							class="bg-white"
						@endif
						><strong>{{$reported_participants_total[$i-1]}}</strong></td>
		  		<?php } ?>
			  	<!--Reqeusts data-->
				</tr>
				<!--participants for reservations-->
			@endif
		</table>
	</body>
</html>