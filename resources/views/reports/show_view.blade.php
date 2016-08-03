<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Reporting</p>
    </li>
    <li><a href="#" class="active">Microsite Data Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      <div class="grid simple ">
        <div class="grid-title">
          <h3>Microsite @if($report_details[0]->report_type == "microsite1") 1 @else 2 @endif Data Summary</h3>
        </div>
        <div class="grid-body">
          <div class="row">
            <div class="col-md-12">
              @if($report_details[0]->offers->projects->language_id == 1)
                <p><strong>Offer Title(en):</strong> {{ $report_details[0]->offers->title_en }}</p>
              @elseif($report_details[0]->offers->projects->language_id == 2)
                <p><strong>Offer Title(de):</strong> {{ $report_details[0]->offers->title_de }}</p>
              @endif
              <p><strong>Partner:</strong> {{ $report_details[0]->offers->partners->partner_name }}</p>
              <p><strong>Partner Contact:</strong> {{ $report_details[0]->offers->contacts->first_name }} {{ $report_details[0]->offers->contacts->last_name or '' }}</p>
              <p><strong>Reporting Month :</strong> {{ Helper::getForMonth($report_details[0]->for_month).' '.$report_details[0]->for_year}}</p>
              <p><strong>Inquiries Nos. :</strong> {{ $report_details[0]->no_of_requests or 'n/a' }}</p>
              <p><strong>Bookings Nos. :</strong> {{ $report_details[0]->no_of_final_bookings or 'n/a' }}</p>
              <p><strong>Total Packages Sold Nos. :</strong> {{ $report_details[0]->no_of_packages_sold or 'n/a' }}</p>
              <p><strong>Comments :</strong> {{ $report_details[0]->comments or 'n/a' }}</p>
              @if($report_details[0]->report_type == "microsite2")
                <hr>
                <h4>Microsite2 Feedbacks</h4>
                <!--Feedback Question1-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Question1 Ans:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      {{ $report_details[0]->microsite2_reports->question1->flag or 'n/a' }}
                    </p>
                  </div>
                  <div class="clearfix"></div>
                  <div class="left-text"><p><strong>Question1 Comments:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if(isset($report_details[0]->microsite2_reports->question1->text))
                        @if($report_details[0]->microsite2_reports->question1->text != "")
                          {{ $report_details[0]->microsite2_reports->question1->text }}
                        @else
                          n/a
                        @endif
                      @else
                        n/a
                      @endif
                    </p>
                  </div>
                </div><!--Feedback Question1 ends-->
                <!--Feedback Question2-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Question2 Comments:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if(isset($report_details[0]->microsite2_reports->question2->text))
                        @if($report_details[0]->microsite2_reports->question2->text != "")
                          {{ $report_details[0]->microsite2_reports->question2->text }}
                        @else
                          n/a
                        @endif
                      @else
                        n/a
                      @endif
                    </p>
                  </div>
                </div><!--Feedback Question2 ends-->
                <!--Feedback Questione-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Question3 Ans:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      {{ $report_details[0]->microsite2_reports->question1->flag or 'n/a' }}
                    </p>
                  </div>
                  <div class="clearfix"></div>
                  <div class="left-text"><p><strong>Question3 Comments:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if(isset($report_details[0]->microsite2_reports->question3->text))
                        @if($report_details[0]->microsite2_reports->question3->text != "")
                          {{ $report_details[0]->microsite2_reports->question3->text }}
                        @else
                          n/a
                        @endif
                      @else
                        n/a
                      @endif
                    </p>
                  </div>
                </div><!--Feedback Questione ends-->
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>