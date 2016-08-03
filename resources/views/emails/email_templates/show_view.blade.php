<div class="content">
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <!--content first title-->
        <div class="grid-title">
          <div class="page-title"> <i class="icon-custom-left"></i>
            <h3>
              <span class="go-back">Go <span class="semi-bold">Back</span></span>
            </h3>
          </div>
          <div class="row" style="margin:0px; padding:0px;">
            <h4><span class="semi-bold">Email Template Details</span></h4>
          </div>
        </div><!--content first title ends-->
        <div class="grid-body">
          <div class="row">
            <div class="col-md-12">
              <!--Project-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Project:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$email_template_details[0]->projects->id}}">{{$email_template_details[0]->projects->name}}</a>
                  </p>
                </div>
              </div><!--Project-->
              <!--Template Language-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Template Language: </strong></p></div>
                <div class="right-text">
                  <p>
                    {{ strtoupper($email_template_details[0]->language) }}
                  </p>
                </div>
              </div>
              <!--Template Language-->
              <!--Sender Email-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Sender Email:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    {{ $email_template_details[0]->email_contents->sender_email or 'N/A' }}
                  </p>
                </div>
              </div><!--Sender Email-->
              <!--CC Emails-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>CC Emails:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if(!empty($email_template_details[0]->email_contents->cc_emails))
                      <?php $sn = 1; ?>
                      @foreach ($email_template_details[0]->email_contents->cc_emails as $key => $cc_email)
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
                    {{ $email_template_details[0]->email_contents->subject or 'N/A' }}
                  </p>
                </div>
              </div><!--Email Subject-->
              <!--Attachments-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Attachments:&nbsp; </strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($email_template_details[0]->email_contents->attachments)
                      <?php $sn = 1; ?>
                      @foreach ($email_template_details[0]->email_contents->attachments as $key => $attachment)
                        {{ $sn.') '.$attachment }}&nbsp;
                        <a class="show_delete_attachment" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-id="{{ $email_template_details[0]->email_contents->id }}" data-attachment="{{ $attachment }}" data-original-title="Delete" data-type="email_template" rel="tooltip" ><i class="fa fa-trash-o"></i></a>
                        <a class="show_download_attachment" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="" data-id="{{ $email_template_details[0]->email_contents->id }}" data-attachment="{{ $attachment }}" data-original-title="Download" data-type="email_template" rel="tooltip" data-href="{{URL::to('email/download-attachment/').'/'.$email_template_details[0]->email_contents->id.'/'.$attachment}}"><i class="fa fa-cloud-download"></i></a>
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
                  {!! $email_template_details[0]->email_contents->content or 'N/A' !!}
                </div>
              </div><!--Email Body ends-->
            </div><!--col-md-12 ends-->
          </div>
        </div><!--grid-body ends-->
      </div>
    </div>
  </div><!--row-fluid ends-->
</div><!--content ends-->