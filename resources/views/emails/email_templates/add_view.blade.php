<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="email-template" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Email Templates</p>
    </li>
    <li>
      <a href="#" class="active">{{ isset($template_details) ? 'Edit' : 'Add' }}</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="email_template_add_form" data-controller="email-template" data-type="multipart" data-ckeditor="yes" data-method="{{ isset($template_details) ? 'update' : 'create'}}" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>
              {{ isset($template_details) ? 'Edit' : 'Add' }}
              <span class="semi-bold">Email Template</span>
            </h4>
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                <div class="row form-row">
                  <!-- Project -->
                  <div class="col-md-6">
                    <label for="project_id">Select Project</label>
                    <select name="project_id" id="project_id" class="select2 form-control"  >
                      <option value="">Select Below</option>
                      @if(isset($projects))
                        @foreach ($projects as $project)
                          <option value="{{ $project->id }}"
                          @if(isset($template_details))
                            @if($template_details[0]->project_id == $project->id)
                              selected="selected"
                            @endif
                          @else
                            @if(old('project_id') == $project->id)
                              selected="selected"
                            @endif
                          @endif
                          >{{ $project->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <span class="error">
                      <label for="project_id" class="error">{{ $errors->first('project_id') }}</label>
                    </span>
                  </div><!--Project-->
                  <!-- Type -->
                  <div class="col-md-6">
                    <label for="email_template_type">Select Email Type</label>
                    <select name="type" id="email_template_type" class="select2 form-control">
                      <option value="">Select Below</option>
                      <option value="activation"
                      @if(isset($template_details))
                        @if($template_details[0]->type == 'activation')
                          selected="selected"
                        @endif
                      @else
                        @if(old('type') == "activation")
                          selected="selected"
                        @endif
                      @endif
                      >Offer Activation</option>
                      <option value="offer_extension"
                      @if(isset($template_details))
                        @if($template_details[0]->type == 'offer_extension')
                          selected="selected"
                        @endif
                      @else
                        @if(old('type') == "offer_extension")
                          selected="selected"
                        @endif
                      @endif
                      >Offer Extension</option>
                      <option value="microsite1"
                      @if(isset($template_details))
                        @if($template_details[0]->type == 'microsite1')
                          selected="selected"
                        @endif
                      @else
                        @if(old('type') == "microsite1")
                          selected="selected"
                        @endif
                      @endif
                      >Microsite1</option>
                      <option value="microsite2_final"
                      @if(isset($template_details))
                        @if($template_details[0]->type == 'microsite2_final')
                          selected="selected"
                        @endif
                      @else
                        @if(old('type') == "microsite2_final")
                          selected="selected"
                        @endif
                      @endif
                      >Microsite2 Final</option>
                      <option value="microsite2_reminder"
                      @if(isset($template_details))
                        @if($template_details[0]->type == 'microsite2_reminder')
                          selected="selected"
                        @endif
                      @else
                        @if(old('type') == "microsite2_reminder")
                          selected="selected"
                        @endif
                      @endif
                      >Microsite2 Reminder</option>
                    </select>
                    <span class="error">
                      <label for="email_template_type" class="error">{{ $errors->first('type') }}</label>
                    </span>
                  </div><!--Type-->
                </div>
                <div class="row form-row">
                  <!-- Sender Email -->
                  <div class="col-md-6">
                    <label for="sender_email">Sender Email</label>
                    <input name="sender_email" id="sender_email" type="text"  class="form-control" placeholder="Sender Email" value="{{ $template_details[0]->email_contents->sender_email or old('sender_email') }}">
                    <span class="error">
                      {{ $errors->first('sender_email') }}
                    </span>
                  </div><!--Sender Email-->
                  <!-- Template Language -->
                  <div class="col-md-6">
                    <label for="language">Select Language</label>
                    <select name="language" id="language" class="select2 form-control"  >
                      <option value="">Select Below</option>
                      <option value="en"
                      @if(isset($template_details))
                        @if($template_details[0]->language == "en")
                          selected="selected"
                        @endif
                      @else
                        @if(old('language') == "en")
                          selected="selected"
                        @endif
                      @endif
                      >English</option>
                      <option value="de"
                      @if(isset($template_details))
                        @if($template_details[0]->language == "de")
                          selected="selected"
                        @endif
                      @else
                        @if(old('language') == "de")
                          selected="selected"
                        @endif
                      @endif
                      >German</option>
                    </select>
                    <span class="error">
                      <label for="language" class="error">{{ $errors->first('language') }}</label>
                    </span>
                  </div><!--Project-->
                </div>
                <div class="row form-row">
                  <!-- Sender Email -->
                  <div class="col-md-12">
                    <label for="subject">Subject</label>
                    <input name="subject" id="subject" type="text"  class="form-control" placeholder="Subject" value="{{ $template_details[0]->email_contents->subject or old('subject') }}">
                    <span class="error">
                      {{ $errors->first('subject') }}
                    </span>
                  </div><!--Sender Email-->
                </div>
                <!--cc email-->
                <div class="row form-row">
                  <span class="cc_emails_wrapper">
                    <!-- CC Emails -->
                    @if(old('cc_emails'))
                      <?php $sn =1; ?>
                      @foreach (old('cc_emails') as $key => $value)
                        <div class="col-md-4 cc_item">
                          @if($sn == 1)
                            <label for="cc_emails">CC &nbsp;<a class="btn btn-success btn-mini add_cc_address"><i class="fa fa-plus"></i>&nbsp;CC</a></label>
                          @else
                            <label for="cc_emails"><a class="btn btn-warning btn-mini remove_cc_address"><i class="fa fa-times"></i>&nbsp;Remove</a></label>
                          @endif
                          <div class="cc_emails_wrapper" id="cc_emails_wrapper">
                            <input name="cc_emails[]" id="" type="text"  class="form-control" placeholder="CC Email" value="{{ $value }}">
                          </div>
                        </div>
                      <?php $sn++; ?>
                      @endforeach
                    @else
                      @if(isset($template_details[0]))
                        @if(!empty($template_details[0]->email_contents->cc_emails))
                          <?php $sn =1; ?>
                          @foreach ($template_details[0]->email_contents->cc_emails as $key => $cc_email)
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
                      @else
                        <div class="col-md-4 cc_item">
                          <label for="cc_emails">CC &nbsp;<a class="btn btn-success btn-mini add_cc_address"><i class="fa fa-plus"></i>&nbsp;CC</a></label>
                          <div class="cc_emails_wrapper" id="cc_emails_wrapper">
                            <input name="cc_emails[]" id="" type="text"  class="form-control" placeholder="CC Email" value="">
                          </div>
                        </div><!--CC Emails-->
                      @endif
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
                        @if(!empty($template_details[0]->email_contents->attachments))
                          @foreach($template_details[0]->email_contents->attachments as $attachment)
                            <span class="attachment-item">{{ $attachment }}&nbsp;<a class="delete_template_attachment" style="cursor:pointer" data-id="{{ $template_details[0]->email_contents->id }}" data-attachment="{{ $attachment }}"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;&nbsp;</span>
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
                      <span >{offer_title}, {project}, {valid_from}, {valid_until}, {first_placement_start_date}, {first_placement_name}</span>
                    </label>
                    <label for="" style=""><span>Template Variables:&nbsp;</span>
                      <span class="variables_wrapper">No matching variables found.</span>
                    </label>
                    <label for="editor_email_template">Template Content</label>
                    <textarea name="content" id="editor_email_template">@if(isset($inputs['content'])){{$inputs['content']}} @else {{$template_details[0]->email_contents->content or old('content')}}@endif</textarea>
                    <span class="error">
                      <label for="editor_email_template" class="error">{{ $errors->first('content') }}</label>
                    </span>
                  </div><!--template body-->
                </div>
              </div>
            </div>
            <div class="form-actions">
              <div class="">
                @if(isset($template_details[0]))
                  <input type="hidden" name="id" value="{{ $template_details[0]->id }}">
                  <input type="hidden" name="email_content_id" value="{{ $template_details[0]->email_contents->id }}">
                @endif
                <button class="btn btn-danger btn-cons" type="submit">{{ isset($template_details)?'UPDATE':'ADD' }}</button>
                <button class="btn btn-danger btn-cons body-link" type="button" data-controller="email-template" data-mode="index">CANCEL</button>
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
  var type_value = $('#email_template_type').val();
  var activation_variables = ['{activation_link}'];
  var microsite1_variables = ['{microsite1_link_en}', '{microsite1_link_de}'];;
  var microsite2_final_variables = ['{microsite2_link_en}', '{microsite2_link_de}'];
  var microsite2_reminder_variables = ['{microsite2_link_en}', '{microsite2_link_de}'];
  if(type_value != ''){
    switch (type_value){
      case 'activation':
        $('span.variables_wrapper').html('');
        $.each(activation_variables, function(index, value){
          value = ' '+value;
          $('span.variables_wrapper').append(value);
        });
        break;
      case 'offer_extension':
        $('#sender_email').val('').attr("disabled", "disabled");
        $('span.variables_wrapper').html('No matching variables found.');
        break;
      case 'microsite1':
        $('span.variables_wrapper').html('');
        $.each(microsite1_variables, function(index, value){
          value = ' '+value;
          $('span.variables_wrapper').append(value);
        });
        break;
      case 'microsite2_final':
        $('span.variables_wrapper').html('');
        $.each(microsite2_final_variables, function(index, value){
          value = ' '+value;
          $('span.variables_wrapper').append(value);
        });
        break;
      case 'microsite2_reminder':
        $('span.variables_wrapper').html('');
        $.each(microsite2_reminder_variables, function(index, value){
          value = ' '+value;
          $('span.variables_wrapper').append(value);
        });
        break;
      default:
        $('span.variables_wrapper').html('No matching variables found.');
        break;
    }
  }else{
    $('span.variables_wrapper').html('No matching variables found.');
  }
})();
</script>
<?php
  //echo "<pre>";
  //print_r(session()->all());
 ?>