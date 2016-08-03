<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="email" data-mode="queue-emails">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Queue Emails</p>
    </li>
    <li>
      <a href="#" class="active">Edit</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="edit_queue_email_form" data-controller="email" data-type="multipart" data-ckeditor="yes" data-method="update" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>
              Edit <span class="semi-bold">Queue Email</span>
            </h4>
          </div>
          <div class="grid-title">
            <h5 style="margin-top: 0px;"><span class="semi-bold">Partner :</span> {{ $email_details[0]->offers->partners->partner_name }}</h5>
            <h5><span class="semi-bold">Primary Partner Contact :</span> {{ $email_details[0]->offers->contacts->first_name.' '.$email_details[0]->offers->contacts->last_name }}</h5>
            @if($email_details[0]->offers->title_en)
              <h5><span class="semi-bold">Offer Title :</span> {{ $email_details[0]->offers->title_en }}</h5>
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
                    <a href="microsite/en/1/{{ $email_details[0]->offer_reports->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">2(EN)</a>
                  </li>
                  <li class="table-li">
                    <a href="microsite/de/1/{{ $email_details[0]->offer_reports->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">2(DE)</a>
                  </li>
                @endif
              </ul>
              @endif
            @endif
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                <div class="row form-row">
                  <!-- Sender Email -->
                  <div class="col-md-6">
                    <label for="sender_email">Sender Email</label>
                    <input name="sender_email" id="sender_email" type="text"  class="form-control" placeholder="Sender Email" value="{{ $email_details[0]->email_contents->sender_email or old('sender_email') }}">
                    <span class="error">
                      {{ $errors->first('sender_email') }}
                    </span>
                  </div><!--Sender Email-->
                  <!-- Email Send date -->
                  <div class="col-md-6">
                    <label for="send_date">Email Send Date</label>
                    <input name="send_date" id="send_date" type="text"  class="form-control date" placeholder="Sender Email" value="{{ Carbon\Carbon::createFromFormat('d/m/Y', $email_details[0]->send_date)->format('Y-m-d') }}">
                    <span class="error">
                      {{ $errors->first('send_date') }}
                    </span>
                  </div><!--Email send date-->
                </div>
                <div class="row form-row">
                  <!-- Sender Email -->
                  <div class="col-md-12">
                    <label for="subject">Subject</label>
                    {{-- <input name="subject" id="subject" type="text"  class="form-control" placeholder="Subject" value="{{ $email_details[0]->email_contents->subject or old('subject') }}"> --}}
                    <input name="subject" id="subject" type="text"  class="form-control" placeholder="Subject" value="{{ $email_details[0]->email_contents->subject or old('subject') }}">
                    <span class="error">
                      {{ $errors->first('subject') }}
                    </span>
                  </div><!--Sender Email-->
                </div>
                <!--cc email-->
                <div class="row form-row">
                  <span class="cc_emails_wrapper">
                    <!-- CC Emails -->
                    @if(!empty($email_details[0]->email_contents->cc_emails))
                      <?php $sn = 1; ?>
                      @foreach ($email_details[0]->email_contents->cc_emails as $key => $cc_email)
                        <div class="col-md-4 cc_item">
                          @if($sn == 1)
                            <label for="cc_emails">CC &nbsp;<a class="btn btn-success btn-mini add_cc_address"><i class="fa fa-plus"></i>&nbsp;CC</a></label>
                          @else
                            <label for="cc_emails"><a class="btn btn-warning btn-mini remove_cc_address"><i class="fa fa-times"></i>&nbsp;Remove</a></label>
                          @endif
                          <div class="cc_emails_wrapper" id="cc_emails_wrapper">
                            <input name="cc_emails[]" id="" type="text"  class="form-control" placeholder="CC Email" value="{{ $cc_email }}">
                          </div>
                        </div>
                      <?php $sn++; ?>
                      @endforeach
                    @else
                      <div class="col-md-4 cc_item">
                        <label for="cc_emails">CC &nbsp;<a class="btn btn-success btn-mini add_cc_address"><i class="fa fa-plus"></i>&nbsp;CC</a></label>
                        <div class="cc_emails_wrapper" id="cc_emails_wrapper">
                          <input name="cc_emails[]" id="" type="text"  class="form-control" placeholder="CC Email" value="">
                        </div>
                      </div><!--CC Emails-->
                    @endif
                  </span>
                  <div class="row form-row"></div>
                  <div class="row" style="margin-left:15px;">
                    <span class="error">
                      {{ $errors->first('cc_emails') }}
                    </span>
                  </div>
                </div><!--cc email row ends-->
                <div class="row form-row">
                  <!-- Attachments -->
                  <div class="col-md-12">
                    <label for="" class="attachment_main_wrapper">
                      <a class="email_template_attach" id="email_template_attach" style="cursor:pointer;"><i class="fa fa-paperclip"></i>&nbsp;Attach a file&nbsp;</a>
                      <span class="attachment-content-wrapper">
                        @if(!empty($email_details[0]->email_contents->attachments))
                          @foreach($email_details[0]->email_contents->attachments as $attachment)
                            <span class="attachment-item">{{ $attachment }}&nbsp;<a class="delete_template_attachment" style="cursor:pointer" data-id="{{ $email_details[0]->email_contents->id }}" data-attachment="{{ $attachment }}"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;&nbsp;</span>
                          @endforeach
                        @endif
                      </span>
                      <input type="file" name="attachments[]" id="" style="display:none;" class="template_add_attachment empty_file">
                    </label>
                    <span class="error">
                      <label for="editor_email_template" class="error">
                        @if(session('file_count'))
                          @for ($i = 0; $i < session('file_count'); $i++)
                            {{ $errors->first('attachments.'.$i) }}
                          @endfor
                        @endif
                      </label>
                    </span>
                  </div><!--Attachments-->
                </div>
                <div class="row form-row">
                  <!--template body-->
                  <div class="col-md-12">
                    <label for="" style=""><span>Contact Variables:&nbsp;</span>
                      <span >{greeting}, {first_name}, {last_name}, {company}</span>
                    </label>
                    <label for="" style=""><span>Offer Variables:&nbsp;</span>
                      <span>{offer_title}, {project}, {valid_from}, {valid_until}, {first_placement_start_date}, {first_placement_name}</span>
                    </label>
                    <label for="" style=""><span>Link Variables:&nbsp;</span><span class="variables_wrapper">
                    @if($email_details[0]->type == "activation")
                      {activation_link}
                    @elseif($email_details[0]->type == "microsite1")
                      {microsite1_link_en} {microsite1_link_de}
                    @elseif($email_details[0]->type == "microsite2_final")
                      {microsite2_link_en} {microsite2_link_de}
                    @elseif($email_details[0]->type == "microsite2_reminder")
                      {microsite2_link_en} {microsite2_link_de}
                    @endif
                    </span></label>
                    <label for="editor_email_template">Template Content</label>
                    <textarea name="content" id="editor_email_template">@if(isset($inputs['content'])){{$inputs['content']}} @else {{$email_details[0]->email_contents->content}} @endif</textarea>
                    <span class="error">
                      <label for="editor_email_template" class="error">{{ $errors->first('content') }}</label>
                    </span>
                  </div><!--template body-->
                </div>
              </div>
            </div>
            <div class="form-actions">
              <div class="">
                @if(isset($email_details[0]))
                  <input type="hidden" name="id" value="{{ $email_details[0]->id }}">
                  <input type="hidden" name="email_content_id" value="{{ $email_details[0]->email_contents->id }}">
                @endif
                <button class="btn btn-danger btn-cons" name="addplacement_submit" type="submit">UPDATE</button>
                <button class="btn btn-danger btn-cons body-link" type="button" data-controller="email" data-mode="queue-emails">CANCEL</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--form ends-->
</div>
<script type="text/javascript">
(function(){
  CKEDITOR.replace('editor_email_template');
})();
</script>
<?php
  //echo "<pre>";
  //print_r(session()->all());
 ?>