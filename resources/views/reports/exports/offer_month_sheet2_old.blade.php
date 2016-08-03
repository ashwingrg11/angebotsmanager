<html>
  <head>
    {{-- <meta charset="UTF-8"> --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Partner Reporting</title>
    <link rel="stylesheet" href="{{ asset('assets/css/offers_reporting_export.css') }}">
  </head>
  <body>
    <table border="1">
      <!--Sheet heading-->
      <tr>
        <td width="50" height="25" valign="middle" style="font-size: 18px;" valign="middle">Sample of Partner Reporting on Offer Success</td>
      </tr><!--Sheet heading ends-->
      <tr></tr>
      <tr>
        <td width="50" class="bg-grey"><strong>Sample of offer validity periods</strong></td>
      </tr>
      <tr></tr>
      <tr class="bordered">
        <td width="50"><strong>Offer Title</strong></td>
        <?php for($i=1; $i<=$data['no_months']; $i++){ ?>
          <td colspan="4" align="center"><strong>Month {{$i}}</strong></td>
        <?php } ?>
        <td colspan="4" align="center"><strong>Month {{$i}}</strong></td>
      </tr>
      <!--offers-->
      <?php $month_date = null; $amt_offers = 0; ?>
      @foreach($offers as $offer)
      <?php
        $colored = '';
        $offer_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
      ?>
      <tr class="bordered">
        <td>{{$offer->title_en or $offer->title_de}}</td>
        <?php $quarter_flag = 0;for($i=1;$i<=$data['no_months']*4; $i++){ ?>
        <?php
          $quarter_flag = $quarter_flag + 1;
          if($i==1){
            $month_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['min_start']);
          }
          else{
            $t = $i-1;
            if($t == 4 || $t % 4 == 0){
              $month_date->addMonth();
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
          class="bg-yellow"
        @endif
        ></td>
        <?php } ?>
        <td width="3"></td>
        <td width="3"></td>
        <td width="3"></td>
        <td width="3"></td>
      </tr>
      @endforeach
      <!--sample of offer, offers end-->
      <tr></tr>
      <tr>
        <td class="bg-grey" height="32"><strong>Offer status report (expressing above chart in numbers)</strong></td>
      </tr>
      <tr></tr>
      <!--offers valid within the month-->
      <tr>
        <td width="50" height="32">Amount of offers valid within that month (some only part of the month)</td>
        <?php for($i=1;$i<=$data['no_months']; $i++){ ?>
            <td colspan="4" align="center" style="vertical-align: middle;">{{$data['valid_offers'][$i]}}</td>
        <?php } ?>
        <td colspan="4" align="center" style="vertical-align: middle;">0</td>
      </tr><!--offers valid within the month-->
      <!--Part of the month offers-->
      <tr>
        <td width="50">Amount of part of the month offers</td>
        <?php for($i=1; $i<=$data['no_months']; $i++){ ?>
          <td colspan="4" align="center">{{$data['part_offers'][$i]}}</td>
        <?php } ?>
        <td colspan="4" align="center">0</td>
      </tr><!--Part of the month offers-->
      <!--Emails nos.-->
      <tr>
        <td width="50" height="32">Amount of emails sent soliciting feedback on still ongoing offers for the period of the previous month</td>
        <?php for($i=1; $i<=$data['no_months']; $i++){ ?>
          <td colspan="4" align="center" style="vertical-align: middle;">{{$data['feedback_emails'][$i-1]}}</td>
        <?php } ?>
        <td colspan="4" align="center" style="vertical-align: middle;">0</td>
      </tr><!--Emails nos.-->
      <!--Offers expired-->
      <tr>
        <td width="50" height="32">Amount of offers expired within the previous month (final reporting on offer is possible)</td>
        <?php for($i=1; $i<=$data['no_months']; $i++){ ?>
          <td colspan="4" align="center" style="vertical-align: middle;">{{$data['expired_offers'][$i-1]}}</td>
        <?php } ?>
        <td colspan="4" align="center" style="vertical-align: middle;">0</td>
      </tr><!--Expired offers end here-->
      <tr></tr>
      <tr></tr>
      <tr>
        <td height="32" class="bg-grey"><strong>Sample of reported bookings in the month for the respective previous month</strong></td>
      </tr>
      <tr></tr>
      <tr class="bordered">
        <td><strong>Offer Title</strong></td>
        <?php for($i=1; $i<=$data['no_months']; $i++){ ?>
          <td colspan="4" align="center"><strong>Month {{$i}}</strong></td>
        <?php } ?>
        <td colspan="4" align="center"><strong>Month {{$i}}</strong></td>
      </tr>
      <?php $offer_flag = 0; ?>
      @foreach($offers as $offer)
      <tr>
        <td>{{$offer->title_en or $offer->title_de}}</td>
        <?php for($i=1; $i<=$data['no_months']+1; $i++){ ?>
          <td colspan="4" align="center">
            @if($i==1)
              n/a
            @else
              {{$data['booking_offers'][$offer_flag][$i-1]}}
            @endif
            </td>
        <?php } ?>
      </tr>
      <?php $offer_flag++; ?>
      @endforeach
      <tr></tr>
      <!--Aggregate Reporting starts-->
      <!--Aggregate reporting heading-->
      <tr>
        <td class="bg-grey" height="32"><strong>Aggregate reporting on bookings in the months for the respective previous month</strong></td>
        <?php $offer_flag = 0; for($i=1; $i<=$data['no_months']+1; $i++){ ?>
          <td colspan="4" align="center">&nbsp;</td>
        <?php $offer_flag++; } ?>
      </tr><!--Aggregate reporting heading ends-->
      <?php $total_na = $total_no_answer = $total_report_back = array() ?>
      <!--n/a row-->
      <tr>
        <td>n/a</td>
        <?php for($i=1; $i<=$data['no_months']+1; $i++){ ?>
          <td colspan="4" align="center">
            @if($i == 1)
              {{$data['offer_nos']}}
            @else
              {{$data['aggregate_report']['n/a'][$i-1]}}
            @endif
          </td>
        <?php } ?>
      </tr><!--n/a row ends-->
      <!--no answer row-->
      <tr>
        <td>no answer</td>
        <?php for($i=1; $i<=$data['no_months']+1; $i++){ ?>
          <td colspan="4" align="center">
            @if($i == 1)
              0
            @else
              {{$data['aggregate_report']['no_answer'][$i-1]}}
            @endif
          </td>
        <?php } ?>
      </tr><!--no answer row ends-->
      <!--partners reporting row-->
      <tr>
        <td># of partners reporting back</td>
        <?php for($i=1; $i<=$data['no_months']+1; $i++){ ?>
          <td colspan="4" align="center">
            @if($i == 1)
              0
            @else
              {{$data['aggregate_report']['report_back'][$i-1]}}
            @endif
          </td>
        <?php } ?>
      </tr><!--partners reporting row ends-->
      <!--aggregate booking row-->
      <tr class="bordered">
        <td><strong>aggregate # of booking</strong></td>
        <?php for($i=1; $i<=$data['no_months']+1; $i++){ ?>
          <td colspan="4" align="center">
            @if($i==1)
              0
            @else
              {{$data['booking_offers']['total_bookings'][$i-1]}}
            @endif
          </td>
        <?php } ?>
      </tr><!--aggregate booking row ends here-->
      <!--Aggregate Reporting ends here-->
    </table>
  </body>
</html>