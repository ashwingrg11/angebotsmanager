{{-- <!DOCTYPE html> --}}
{{-- <html lang="en"> --}}
<html>
<head>
  {{-- <meta charset="UTF-8"> --}}
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Offers & Placements</title>
  <link rel="stylesheet" href="{{ asset('assets/css/offers_reporting_export.css') }}">
</head>
<body>
  <table border="1" cellspacing="0">
    <tbody>
      <!--reporting file heading-->
      <tr>
        <td width="30" height="25" style="font-size: 18px;" valign="middle">Timeline for Start and End Dates of Offers and their Placements</td>
      </tr>
      <tr></tr>
      <tr>
        <td width="30" height="20" valign="middle"><strong>Project:</strong></td>
        <td width="30" valign="middle" style="wrap-text: false">{{ $user_data['project_info'][0]->name }}</td>
      </tr>
      <tr>
        <td width="30" height="20" valign="middle"><strong>Placement(s):</strong></td>
        <td width="30" valign="middle" style="wrap-text: false">
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
      <!--reporting table titles-->
      <tr class="bordered">
        <td><strong>Offer Title</strong></td>
        <td><strong>Offer Start Date</strong></td>
        <td><strong>Offer End Date</strong></td>
        <td width="30"><strong>Channel</strong></td>
        <td width="30"><strong>Placement</strong></td>
        <td width="30"><strong>Placement Start Date</strong></td>
        <td width="30"><strong>Placement End Date</strong></td>
        <!--months columns-->
        <?php
          if(isset($data['min_start'])){
            $new_min_start = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
            while ($new_min_start <= \Carbon\Carbon::createFromFormat('d/m/Y', $data['max_end'])){
        ?>
          <td colspan="4" align="center"><strong>{{$new_min_start->format('M')}}  {{$new_min_start->format('Y')}}</strong></td>
        <?php $new_min_start->modify('first day of next month'); }}
        else { ?>
          <td colspan="4" align="center"><strong>N/A</strong></td>
        <?php } ?>
        <!--months columns-->
        <td width="16" align="center"><strong>Validity Period</strong></td>
      </tr><!--reporting table titles end-->
      <?php
        $month_date = null;
        $flag_month_date = null;
        $offer_flag = 1;
      ?>
      @if ($offers->isEmpty())
        <tr><td>No Offers Available.</td></tr>
      @else
      @foreach($offers as $offer)
        <?php
          $colored = '';
          $offer_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
          $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        ?>
        <!--offer-->
        <tr class="bordered">
          <td class="bg-lightgreen"><strong>{{$offer->title_en or $offer->title_de}}</strong></td>
          <!--offer valid from row-->
          <td class="bg-lightgreen">{{ \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from)->format('Y-m-d') }}</td>
          <!--offer valid from row-->
          <!--offer valid until row-->
          <td class="bg-lightgreen">{{ \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until)->format('Y-m-d') }}</td>
          <!--offer valid until row-->
          <td class="bg-lightgreen"></td>
          <td class="bg-lightgreen"></td>
          <td class="bg-lightgreen"></td>
          <td class="bg-lightgreen"></td>
          <!--months-->
          <?php $quarter_flag = 0; for($i=1;$i<=$data['no_months']*4; $i++){ ?>
            <?php
              $quarter_flag = $quarter_flag + 1;
              if($i==1){
                $month_date = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
                $flag_month_date = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
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
                  // $quarter = Helper::quarterOfMonth($offer_from->format('d'));
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
          <!--months end here-->
          <td class="bg-lightgreen"></td><!--validity period td-->
        </tr><!--offer row ends-->
        <?php $offer_flag++; ?>
        @foreach($offer->placements as $placement)
          <?php
            $colored2 = '';
            // echo $placement->pivot->placement_start_date;
            $placement_from = \Carbon\Carbon::createFromFormat('Y-m-d', $placement->pivot->placement_start_date);
            $placement_until = \Carbon\Carbon::createFromFormat('Y-m-d', $placement->pivot->placement_end_date);
          ?>
          <!--offer placements-channels-->
          <tr class="bordered">
            <td>{{$offer->title_en or $offer->title_de}}</td>
            <td></td>
            <td></td>
            <td>{{$placement->channels->name}}</td>
            <td class="bg-yellow">{{$placement->title}}</td>
            <td class="bg-yellow">{{ $placement_from->format('Y-m-d') }}</td>
            <td class="bg-yellow">{{ $placement_until->format('Y-m-d') }}</td>
            <!--months-->
            <?php $quarter_flag2 = 0; for($i2=1;$i2<=$data['no_months']*4; $i2++){ ?>
            <?php
              $quarter_flag2 = $quarter_flag2 + 1;
              if($i2==1){
                $month_date2 = \Carbon\Carbon::createFromFormat('d/m/Y', $data['min_start']);
                // $flag_month_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['min_start']);
              }
              else{
                $t2 = $i2-1;
                if($t2 == 4 || $t2 % 4 == 0){
                  // $month_date2->addMonth();
                  $month_date2->modify('first day of next month');
                }
              }
              if($quarter_flag2 > 4){
                $quarter_flag2 = 1;
              }
              if(($placement_from->format('Y') == $month_date2->format('Y')) && ($placement_from->format('m') == $month_date2->format('m'))){
                $quarter2 = Helper::quarterOfMonth($placement_from->format('d'));
                if($quarter2 == $quarter_flag2){
                  $colored2 = "yes";
                }
              }
              else{
                if($month_date2 > $placement_until){
                  if($month_date2->format('Y') != $placement_until->format('Y') || $month_date2->format('m') != $placement_until->format('m')){
                    $colored2 = "no";
                  }
                }
              }
              if(($placement_until->format('Y') == $month_date2->format('Y')) && ($placement_until->format('m') == $month_date2->format('m'))){
                $quarter2 = Helper::quarterOfMonth($placement_until->format('d'));
                if($quarter2 >= $quarter_flag2){
                  if($quarter2 == $quarter_flag2){
                    $colored2 = "yes";
                  }
                }else{
                  $colored2 = "no";
                }
              }
            ?>
            <td width="3"
            @if($colored2 == "yes")
              class="bg-yellow"
            @endif
            ></td>
            <?php } ?>
            <!--months end here-->
            <td
              @if ($placement_from < $offer_from && $placement_until->format('Y-m-d') == $offer_until->format('Y-m-d'))
                class="bg-darkyellow"
              @elseif ($placement_from < $offer_from && $placement_until < $offer_until)
                class="bg-light-orange"
              @elseif ($placement_from->format('Y-m-d') == $offer_from->format('Y-m-d') && $placement_until->format('Y-m-d') == $offer_until->format('Y-m-d'))
                class="bg-green"
              @elseif ($placement_from->format('Y-m-d') == $offer_from->format('Y-m-d') && $placement_until < $offer_until)
                class="bg-light-red"
              @elseif ($placement_from > $offer_from && $placement_until->format('Y-m-d') == $offer_until->format('Y-m-d'))
                class="bg-lightblue"
              @elseif ($placement_from > $offer_from && $placement_until < $offer_until)
                class="bg-purple"
              @endif
            ><?php //echo $placement_from, $offer_from;die; ?></td>
          </tr><!--offer placements-channels end-->
        @endforeach
      @endforeach <?php //die; ?>
      @endif
      <!--reporting offers&placements data ends-->
      <tr></tr>
      <tr></tr>
      <!--excel footer remarks content-->
      <tr>
        <td colspan="3" height="25" valign="middle"><strong>Legend: Validity Period</strong></td>
      </tr>
      <tr>
        <td class="bg-lightgreen" colspan="3">Offer validity period</td>
      </tr>
      <tr>
        <td class="bg-darkyellow" colspan="3">Placements can start earlier than the offer start date and end with its end date</td>
      </tr>
      <tr>
        <td colspan="3" class="bg-light-orange">Placements can start earlier than the offer start date and end earlier than its end date</td>
      </tr>
      <tr>
        <td class="bg-green" colspan="3">Placements can start with the offer start date and end with the offer end date</td>
      </tr>
      <tr>
        <td colspan="3" class="bg-light-red">Placement can start with the offer start date and end earlier than its end date</td>
      </tr>
      <tr>
        <td class="bg-lightblue" colspan="3">Placements can start later than an offer start date and end with its end date</td>
      </tr>
      <tr>
        <td class="bg-purple" colspan="3">Placements can start later than the offer start date and end earlier than its end date</td>
      </tr>
      <!--excel footer remarks content ends here-->
    </tbody>
  </table>
</body>
</html>