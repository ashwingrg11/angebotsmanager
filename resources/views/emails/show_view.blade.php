<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Emails</p>
    </li>
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <!--content first title-->
        <div class="grid-title">
          <div class="row" style="margin:0px; padding:0px;">
            <h4><span class="semi-bold">@if($email_details[0]->status == "pending") Queued @else Sent  @endif Email Details Summary</span></h4>
          </div>
        </div><!--content first title ends-->
        <!--content second header section-->
        <div class="grid-title">
          <div class="row" style="margin:0px; padding:0px">
            <div class="col-md-12">
              <h5 style="margin-top: 0px;"><span class="semi-bold">
                Partner :</span>
                <a href="javascript:;" class="body-link" data-controller="partner" data-mode="show" data-param="{{$email_details[0]->offers->partners->id}}">{{$email_details[0]->offers->partners->partner_name}}</a>
              </h5>
              <h5>
                <span class="semi-bold">Primary Partner Contact :</span>
                <a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$email_details[0]->offers->contacts->id}}">
                  {{ $email_details[0]->offers->contacts->first_name.' '.$email_details[0]->offers->contacts->last_name }}
                </a>
              </h5>
              @if($email_details[0]->offers->title_en)
                <h5>
                  <span class="semi-bold">Offer Title :</span>
                  <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$email_details[0]->offers->id}}">{{$email_details[0]->offers->title_en or  $email_details[0]->offers->title_de}}</a>
                </h5>
              @endif
              @if($email_details[0]->offers->has_communication_package == "yes")
                @if($email_details[0]->offer_reports)
                <h5><span class="semi-bold">Microsites :</span></h5>
                <ul class="list-inline" style="padding:0px; margin:0px;">
                  @if($email_details[0]->type == 'microsite1')
                    <li class="table-li">
                      <a href="microsite/en/1/{{ $email_details[0]->offer_reports->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">1(EN)</a>
                    </li>
                    <li class="table-li">
                      <a href="microsite/de/1/{{ $email_details[0]->offer_reports->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">1(DE)</a>
                    </li>
                  @else
                    <li class="table-li">
                      <a href="microsite/en/2/{{ $email_details[0]->offer_reports->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">2(EN)</a>
                    </li>
                    <li class="table-li">
                      <a href="microsite/de/2/{{ $email_details[0]->offer_reports->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">2(DE)</a>
                    </li>
                  @endif
                </ul>
                @endif
              @endif
            </div>
          </div>
        </div><!--content second header section ends-->
        <div class="grid-body">
          <div class="row">
            <div class="col-md-12">
              <!--Email Send Date-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Send Date:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    {{ Carbon\Carbon::createFromFormat('d/m/Y', $email_details[0]->send_date)->format('Y-m-d') }}
                  </p>
                </div>
              </div><!--Email Send Date-->
              <!--Sender Email-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Sender Email:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    {{ $email_details[0]->email_contents->sender_email or 'N/A' }}
                  </p>
                </div>
              </div><!--Sender Email-->
              <!--Receiver Email-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Receiver Email:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    {{ $email_details[0]->email_contents->receiver_email or 'N/A' }}
                  </p>
                </div>
              </div><!--Receiver Email-->
              <!--CC Emails-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>CC Emails:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if(!empty($email_details[0]->email_contents->cc_emails))
                      <?php $sn = 1; ?>
                      @foreach ($email_details[0]->email_contents->cc_emails as $key => $cc_email)
                        {{ $sn.') '.$cc_email }}
                      <?php $sn++; ?>
                      @endforeach
                    @else
                      N/A
                    @endif
                  </p>
                </div>
              </div><!--CC Emails-->
              <!--Email Subject-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Subject:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    {{ $email_details[0]->email_contents->subject or 'N/A' }}
                  </p>
                </div>
              </div><!--Email Subject-->
              <!--Attachments-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Attachments:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($email_details[0]->email_contents->attachments)
                      <?php $sn = 1; ?>
                      @foreach ($email_details[0]->email_contents->attachments as $key => $attachment)
                        {{ $sn.') '.$attachment }}&nbsp;
                        @if($email_details[0]->status == "pending")
                          <a class="show_delete_attachment" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-id="{{ $email_details[0]->email_contents->id }}" data-attachment="{{ $attachment }}" data-original-title="Delete" data-type="email" rel="tooltip"><i class="fa fa-trash-o"></i></a>
                        @endif
                        <a class="show_download_attachment" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="" rel="tooltip" data-id="{{ $email_details[0]->email_contents->id }}" data-attachment="{{ $attachment }}" data-original-title="Download" data-type="email" data-href="{{URL::to('email/download-attachment/').'/'.$email_details[0]->email_contents->id.'/'.$attachment}}"><i class="fa fa-cloud-download"></i></a>
                        <br>
                        <?php $sn++; ?>
                      @endforeach
                    @else
                      N/A
                    @endif
                  </p>
                </div>
              </div><!--Attachments-->
              <!--Email Body-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Email Body:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  {!! $email_details[0]->email_contents->content or 'N/A' !!}
                </div>
              </div><!--Email Body ends-->
            </div><!--col-md-12 ends-->
          </div>
        </div><!--grid-body ends-->
        @if($email_details[0]->status == "pending")
          <div class="grid-body" style="border-top:none;">
            <button class="btn btn-danger btn-cons body_action_link" data-toggle="tooltip" data-placement="right" title="" rel="tooltip" data-original-title="Edit This Email" data-method="edit" data-controller="email" data-id="{{ $email_details[0]->id }}">EDIT</button>
          </div>
        @endif
      </div>
    </div>
  </div><!--row-fluid ends-->
</div><!--content ends-->