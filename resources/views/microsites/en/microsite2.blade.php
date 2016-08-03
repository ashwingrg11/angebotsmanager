@extends('microsites.microsite_layout')
@section('page-title')
  <title>Microsite2 EN</title>
@endsection
@section('link-css')
  <link rel="stylesheet" href="{{ asset('assets/microsite/css/style2.css') }}" type="text/css">
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
        <h4>{{ $offer_details[0]->projects->name }}</h4>
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
      @include('microsites.shared.notifications')
      <!--welcome user-->
      <h4>Welcome <span class="name">{{ $offer_details[0]->contacts->first_name.' '.$offer_details[0]->contacts->last_name }}</span></h4>
      <div class="row">
      <div class="col-md-9" style="padding:0px;">
        <p class="color1">Thank you for taking the time to complete this final questionnaire. Your feedback is extremely valuable to us. Your answers will help us to further develop the offers featured in the customer programme and to strengthen our partnership with you.</p>
      </div>
      <!--microsite 2 form starts-->
      <form action="" class="form-horizontal" method="post">
      <div class="col-md-9 feedback" style="padding:0px; margin-top:2%;">
        <label for="inquiries">Would you be interested in a future collaboration with Journal International Publishing and one of its other publications?</label>
        <?php //print_r($current_offer_report[0]->microsite2_reports->question1->flag) ?>
        <!--future collaboration-->
        <div class="form-group">
          <div class="form-yesno" style="font-size:125%;">
            <div class="row">
              <label for="yesno-radio1" class="label-yes">Yes</label>
              <div class="checkBox">
                <input type="radio" value="yes" name="future_collaboration" id="yesno-radio1" style="visibility: hidden;"
                @if($current_offer_report[0]->filled_in == "yes")
                  @if($current_offer_report[0]->microsite2_reports->question1->flag == "yes")
                    checked="checked"
                  @endif
                @else
                  @if(old('future_collaboration') == 'yes')
                    checked="checked"
                  @endif
                @endif
                >
                <label for="yesno-radio1"></label>
              </div>
            </div>
            <div class="row">
              <label for="yesno-radio2" class="label-no">No</label>
              <div class="checkBox">
                <input type="radio" value="no" name="future_collaboration" id="yesno-radio2" style="visibility: hidden;"
                @if($current_offer_report[0]->filled_in == "yes")
                  @if($current_offer_report[0]->microsite2_reports->question1->flag == "no")
                    checked="checked"
                  @endif
                @else
                  @if(old('future_collaboration') == 'no')
                    checked="checked"
                  @endif
                @endif
                >
                <label for="yesno-radio2"></label>
              </div>
            </div>
          </div>
          <div class="form-yesno-area">
            <textarea name="future_collaboration_text" class="form-control microsite-textarea" id="" rows="3" style="width:100%;" placeholder="Comments">@if($current_offer_report[0]->filled_in == "yes"){{$current_offer_report[0]->microsite2_reports->question1->text}}@else{{ old('future_collaboration_text') }}@endif</textarea>
            <label for="characters left"><span class="comment">Remaining characters: <span class="remain">1000</span></span></label>
          </div>
          <div class="float-clear"></div>
        </div><!--future collaboration ends here-->
        <label for="inquiries">Please share any collective feedback you may have received from your guests / customers regarding your offer.</label>
        <!--collective feedback-->
        <div class="form-group">
          <div class="form-yesno" style="font-size:125%; visibility: hidden;">
            <div class="row">
              <label for="yesno-radio1" class="label-yes">Yes</label>
              <div class="checkBox">
                <input type="radio" value="yes" name="future_collaboration_test" id="yesno-radio1" style="visibility: hidden;"
                >
                <label for="yesno-radio1"></label>
              </div>
            </div>
            <div class="row">
              <label for="yesno-radio2" class="label-no">No</label>
              <div class="checkBox">
                <input type="radio" value="no" name="future_collaboration_test" id="yesno-radio2" style="visibility: hidden;"
                >
                <label for="yesno-radio2"></label>
              </div>
            </div>
          </div>
          <div class="form-yesno-area">
            <textarea name="collective_feedback_text" class="form-control microsite-textarea" id="" rows="3" style="width:100%;" placeholder="Comments">@if($current_offer_report[0]->filled_in == "yes"){{$current_offer_report[0]->microsite2_reports->question2->text}}@else{{ old('collective_feedback_text') }}@endif</textarea>
            <label for="characters left"><span class="comment">Remaining characters: <span class="remain">1000</span></span></label>
          </div>
        </div><!--collective feedback ends here-->
        <label for="inquiries">Would you like to share anything else with our offer team? Do you have any suggestions how to improve future collaborations or the overall success?</label>
        <!--overall success-->
        <div class="form-group">
          <div class="form-yesno" style="font-size:125%;">
            <div class="row">
              <label for="yesno-radio3" class="label-yes">Yes</label>
              <div class="checkBox">
                <input type="radio" value="yes" name="overall_success" id="yesno-radio3" style="visibility: hidden;"
                @if($current_offer_report[0]->filled_in == "yes" && $current_offer_report[0]->microsite2_reports->question3->flag == "yes")
                  checked="checked"
                @else
                  @if(old('overall_success') == 'yes')
                    checked="checked"
                  @endif
                @endif
                >
                <label for="yesno-radio3"></label>
              </div>
            </div>
            <div class="row">
              <label for="yesno-radio4" class="label-no">No</label>
              <div class="checkBox">
                <input type="radio" value="no" name="overall_success" id="yesno-radio4" style="visibility: hidden;"
                @if($current_offer_report[0]->filled_in == "yes" && $current_offer_report[0]->microsite2_reports->question3->flag == "no")
                  checked="checked"
                @else
                  @if(old('overall_success') == 'no')
                    checked="checked"
                  @endif
                @endif
                >
                <label for="yesno-radio4"></label>
              </div>
            </div>
          </div>
          <div class="form-yesno-area">
            <textarea name="overall_success_text" class="form-control microsite-textarea" id="" rows="3" style="width:100%;" placeholder="Comments">@if($current_offer_report[0]->filled_in == "yes"){{$current_offer_report[0]->microsite2_reports->question3->text}}@else{{ old('overall_success_text') }}@endif</textarea>
            <label for="characters left"><span class="comment">Remaining characters: <span class="remain">1000</span></span></label>
          </div>
        </div><!--overall success ends here-->
        <!--extend offer-->
        <div class="form-group">
          <div class="form-yesno" style="font-size:125%;">
            <div class="row">
              <label for="" style="visibility:hidden;" class="label-yes">Yes</label>
               <div class="checkBox">
                <input type="checkbox" id="checkBox" name="extend_offer" value="yes" style="visibility: hidden;"
                @if($current_offer_report[0]->filled_in == "yes" && $current_offer_report[0]->extension_request == "yes")
                  checked="checked"
                @else
                  @if(old('extend_offer') == 'no')
                    checked="checked"
                  @endif
                @endif
                />
                <label for="checkBox"></label>
              </div>
            </div>
          </div>
          <div class="form-yesno-area">
            <label for="checkBox" style="cursor: pointer; line-height: 38px;">I am interested in extending my offer beyond the previously agreed upon timeframe.</label>
          </div>
        </div><!--extend offer ends-->
      </div>
      <div class="clearfix"></div>
      <!--welcome user-->
      <!--feedback column-->
      <div class="col-md-4 feedback mc2-feedback">
        <h4 class="columntitle">Requesting Your Feedback</h4><br>
        <p class="color1">Please update us on your success and fill in the following fields for the month of <span style="color:#002846;"><strong><?php $month = Helper::getForMonth($current_offer_report[0]->for_month); ?>{{$month}} {{$current_offer_report[0]->for_year}}</strong></span>.</p>
        <!--microsite form begins-->
        <!-- <form action="" class="form-horizontal" method="post"> -->
          <input type="hidden" name="report_code" value="{{ $current_offer_report[0]->report_code }}">
          <input type="hidden" name="microsite" value="2">
          <input type="hidden" name="offer_id" value="{{ $offer_details[0]->id }}">
          @if(!$current_offer_report->isEmpty())
            <input type="hidden" name="offer_reports_id" value="{{ $current_offer_report[0]->id }}">
          @endif
          {{ csrf_field() }}<br>
          <!--number of requests-->
          <div class="form-group">
            <div class="row">
              <div class="col-md-8 col-xs-8">
                <label for="no_of_requests">Number of requests *</label>
              </div>
              <div class="col-md-4 col-xs-4">
                <input type="text" name="no_of_requests" id="no_of_requests" class="form-control" placeholder="0"
                @if($current_offer_report[0]->filled_in == "yes")
                  value="{{ $current_offer_report[0]->no_of_requests }}"
                @else
                  value="{{ old('no_of_requests') }}"
                @endif
                >
              </div>
              <div class="col-md-12">
                <span style="color:red; font-size:10px;">
                  {{-- $errors->first('no_of_requests') --}}
                  @if($errors->first('no_of_requests'))
                    Format invalid. Please insert a whole number.
                  @endif
                </span>
              </div>
            </div>
          </div><!--number of requests <ends-->
          <!--number of final bookings-->
          <div class="form-group">
            <div class="row">
              <div class="col-md-8 col-xs-8">
                <label for="no_of_final_bookings">Number of reservations / bookings / purchase checkouts</label>
              </div>
              <div class="col-md-4 col-xs-4">
                <input type="text" name="no_of_final_bookings" id="no_of_final_bookings" class="form-control" placeholder="0"
                @if($current_offer_report[0]->filled_in == "yes")
                  value="{{ $current_offer_report[0]->no_of_final_bookings }}"
                @else
                  value="{{ old('no_of_final_bookings') }}"
                @endif
                >
              </div>
              <div class="col-md-12">
                <span style="color:red; font-size:10px;">
                  {{-- $errors->first('no_of_final_bookings') --}}
                  @if($errors->first('no_of_final_bookings'))
                    Format invalid. Please insert a whole number.
                  @endif
                </span>
              </div>
            </div>
          </div><!--number of final bookings ends-->
          <!--total number of packages sold-->
          <div class="form-group">
            <div class="row">
              <div class="col-md-8 col-xs-8">
                <label for="no_of_packages_sold">Total number of participants for reservations & bookings / items upon checkout (if applicable)</label>
              </div>
              <div class="col-md-4 col-xs-4">
                <input type="text" name="no_of_packages_sold" class="form-control" placeholder="0"
                @if($current_offer_report[0]->filled_in == "yes")
                  value="{{ $current_offer_report[0]->no_of_packages_sold }}"
                @else
                  value="{{ old('no_of_packages_sold') }}"
                @endif
                >
              </div>
              <div class="col-md-12">
                <span style="color:red; font-size:10px;">
                  {{-- $errors->first('no_of_packages_sold') --}}
                  @if($errors->first('no_of_packages_sold'))
                    Format invalid. Please insert a whole number.
                  @endif
                </span>
              </div>
            </div>
          </div><!--total number of packages sold ends-->
          <!--comments-->
          <div class="form-group">
            <div class="row">
              <label for="comments" class="col-md-12">Comments:</label>
              <div class="col-md-12">
                <textarea name="limitedtextarea" id="" cols="30" rows="8" class="form-control microsite-textarea">@if($current_offer_report[0]->filled_in == "yes"){{ $current_offer_report[0]->comments }}@else{{ old('limitedtextarea') }}@endif</textarea>
                <label for="characters left"><span class="comment">Remaining characters: <span class="remain">1000</span></span></label>
              </div>
            </div>
          </div><!--comments-->
          <div class="clearfix"></div>
          <input type="submit" class="btn btn-primary btn-lg btn-block" value="SEND">
          <label for="mandatory">* Please leave blank if unknown or not applicable.</label>
        </form><!--form ends here-->
      </div><!--feedback column ends here-->
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
      <!--booking history-->
      <div class="row history">
        <h3 class="further_info title" style="padding-left:1%;">Your Booking History</h3>
        <!--table starts-->
        <div class="table-responsive">
          <table class="table">
            <!--table headings-->
            <thead>
            <?php $total_requests = $total_final_bookings = $total_bookings_another = $total_packages_sold = ''; ?>
              <tr>
                <th width="120">Reporting Month</th>
                <th width="100">Number of requests</th>
                <th width="250">Number of reservations / bookings / purchase checkout</th>
                <th width="250">Total number of participants for reservations & bookings / items upon checkout <br> (if applicable)</th>
                <th width="500">Comment</th>
              </tr>
            </thead><!--table headings-->
            <tbody>
            @if(!$offer_reports->isEmpty())
              <?php $sn = 2; ?>
              @foreach ($offer_reports as $offer_report)
                <tr class="<?php if($sn%2 == 0){ echo "mcrow-even"; } else { echo "mcrow-odd";  } ?>">
                  <td>{{ Helper::getForMonth($offer_report->for_month).' '.$offer_report->for_year }}</td>
                  <td>
                    @if($offer_report->filled_in == "yes")
                      {{ $offer_report->no_of_requests or 'N/A' }}
                    @else
                      --
                    @endif
                  </td>
                  <td>
                    @if($offer_report->filled_in == "yes")
                      {{ $offer_report->no_of_final_bookings or 'N/A' }}
                    @else
                      --
                    @endif
                  </td>
                  <td>
                    @if($offer_report->filled_in == "yes")
                      {{ $offer_report->no_of_packages_sold or 'N/A' }}
                    @else
                      --
                    @endif
                  </td>
                  <td>
                    @if($offer_report->filled_in == "yes")
                      {{ $offer_report->comments or 'N/A' }}
                    @else
                      --
                    @endif
                  </td>
                </tr>
              <?php
                $sn++;
                $total_requests = $total_requests+$offer_report->no_of_requests;
                $total_final_bookings = $total_final_bookings+$offer_report->no_of_final_bookings;
                $total_packages_sold = $total_packages_sold+$offer_report->no_of_packages_sold;
              ?>
              @endforeach
            @else
              <tr class="mcrow-even"><td colspan="6" style="text-align: left;">No records found.</td></tr>
            @endif
            </tbody>
            <!--table footer-->
            <tfoot>
              <tr>
                <td style="vertical-align: middle;">TOTAL</td>
                <td>{{ $total_requests }}</td>
                <td>{{ $total_final_bookings }}</td>
                <td>{{ $total_packages_sold }}</td>
              </tr>
            </tfoot><!--table footer ends-->
          </table>
        </div><!--table ends here-->
      </div><!--booking history ends here-->
    </div><!--body content ends here-->
  </div><!--.container ends here-->
@endsection