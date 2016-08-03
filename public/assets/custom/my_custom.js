$(function(){
  $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });
})
$(document).ready(function () {
  var App = {
    manageDynamicLoader: function(mode){
      if( mode == 'show'){
        $('.loaderbg').removeClass('hide');
      }
      else{
        $('.loaderbg').addClass('hide');
      }
    },
    registerGlobalDomFunctions: function(){
      /** insert datepicker **/
      $('.page-content .date').each(function(){
        $(this).datepicker();
      })
      /** hide tools menu **/
      $('.tools').each(function(){
        $(this).hide();
      });
      /** go back links **/
      var controller = null, mode = null; //initialize controller and mode variable to null
      //on click function for go back button
      $('.icon-custom-left, .go-back').on('click', function(e){
        App.manageDynamicLoader('show');
        if($(this).hasClass('icon-custom-left')){
          App.manageDynamicLoader('hide');
          $(this).siblings('h3').eq(0).find('span').eq(0).click();
          return false;
        }
        // App.manageDynamicLoader('show');
        array_length = links.length; //calculates length of go back links
        length = array_length-2;
        if($(this).data('from') == "search"){
          if(current_link == "default"){
            location.reload();
            return false;
          }else{
            // App.setSidebarActive(current_link[0], 'remove');
            getUrl = current_link[0]+'/'+current_link[1];
            current_link[0] = current_link[0];
            current_link[1] = current_link[1];
            if(current_link[2] != 'undefined'){
              getUrl = getUrl+'/'+current_link[2];
              current_link[2] = current_link[2];
            }
            $.get(getUrl,function(res){
              $('.page-content').html(res);
              /* Set the defaults for DataTables initialisation*/
              $('.page-content .datatable').dataTable();
              App.manageDynamicLoader('hide');
              App.registerGlobalDomFunctions();
              return false;
            })
          }
          return false;
        }
        //if back links length is 2 and data controller not exists
        if (links.length === 2){
          location.reload();// dashboard
        } else {
          if ((typeof links[length]) == "undefined"){
            location.reload();
            return false;
          }
          // App.setSidebarActive(links[length][0], 'remove');
          controller = links[length][0]; // get controller name from links array
          mode = links[length][1]; //get mode from links array
          //get parent li of ul.sub-menu having child with same data controller
          links.pop(); //removes array last element
          getUrl = controller+'/'+mode;
          current_link[0] = controller;
          current_link[1] = mode;
          App.setDefaultActionActive(controller, mode, 'normal');
          if((typeof links[length][2]) != "undefined"){
            getUrl = controller+'/'+mode+'/'+links[length][2];
            current_link[2] = links[length][2];
          }else{
            /*task_link = $('.task_link[data-controller="'+controller+'"][data-mode="'+mode+'"]');
            parent_li = task_link.parent().parent().parent('li');
            if(!parent_li.hasClass('open')){
              parent_li.find('a.main_module_link').eq(0).click();
            }
            $('.menu_bg').each(function(){
              $(this).removeClass('menu_bg');
            });
            task_link.addClass('menu_bg');*/
            // App.setDefaultActionActive(controller, mode);
          }
          $.get(getUrl,function(res){
            $('.page-content').html(res);
            /* Set the defaults for DataTables initialisation*/
            $('.page-content .datatable').dataTable();
            App.manageDynamicLoader('hide');
            App.registerGlobalDomFunctions();
          })
        }
      });

      $('#assign_contact_modal_close, #assign_contact_modal_cancel, .assign_contact_modal_back').on('click', function(e){
        e.preventDefault();
        $('#assign_offer_partners').modal('hide');
        $('#list_offer_partners').modal('show');
      });
      $('#assign_partner_contacts_id').off().on('click', function(e){
        console.log('here');
        e.preventDefault();
        project_id = $('#offer_project_select').val();
        if(project_id == ''){
          project_id = 0;
        }
        console.log(project_id);
        $.ajax({
          url: 'partner/list-by-offer/'+project_id,
          type: "GET",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Your request can not be performed at this time. Please try again later.');
            }
            else{
              $('#list_offer_partners_dialog').html(data);
            }
            App.manageDynamicLoader('hide');
            App.registerGlobalDomFunctions();
            return false;
          }
        });
      })
      /*=======================Form Handler for handling all form submit================*/
      $('form').off().on('submit', function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        form_id  = '#'+$(this).attr('id'),
        ctrl     = $(this).data('controller'),
        type     = $(this).data('type'),
        ckeditor = $(this).data('ckeditor'),
        method   = $(this).data('method'),
        is_modal = $(this).data('modal');
        if(ctrl == 'offer' && method == 'update'){
          if (!confirm('Are you sure you want to update this offer?')){
            App.manageDynamicLoader('hide');
            return false;
          }
        }
        if(ckeditor == 'yes'){
          for ( instance in CKEDITOR.instances ) {
            CKEDITOR.instances[instance].updateElement();
          }
        }
        if(type == 'multipart'){
          Controller.multipartFormHandler(ctrl, form_id, method);
        }
        else if(is_modal == "yes"){
          Controller.modalFormHandler(ctrl, form_id, method);
        }
        else{
          Controller.commonFormHandler(ctrl, form_id, method);
        }
        return false;
      });
      /*=======================Form Handler ends================*/
      /*=======================Edit-Contact/Client/Partner/Channel/OfferTypes/Project/Placement/Offer================*/
      $(".datatable").off().on("click", ".assigncontact, .editcontact, .assign_project, .editclient, .editpartner, .editchannel, .edit_general_offer_type, .edit_detailed_offer_type, .edit_offer_type_category, .editproject, .edit_placement, .edit_offer, .edit_offer_placement, .edit_email_template, .edit_email, .edit_communication_package, .edit_user, .edit_country, .edit_language, .edit_category", function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        ctrl  = $(this).data('controller'),
        id    = $(this).data('id'),
        partial_view  = $(this).data('partial'),
        method    = $(this).data('method');
        if(partial_view != undefined){
          Controller.anchorHandlerPartialType(ctrl, id, method, partial_view);
          return false;
        }
        App.registerGlobalDomFunctions();
        Controller.commonAnchorHandler(ctrl, id, method);
      });
      /*=======================Edit-Contact/Client/Partner/Channel/OfferTypes/Project/Placement/Offer================*/
      /*=======================Delete-Contact/Client/Partner/Channel/OfferTypes/Project/Placement/Offer================*/
      $(".datatable").on("click", ".deletecontact, .deleteclient, .deletepartner, .deleteproject, .deletechannel, .delete_general_offer_type, .delete_detailed_offer_type, .delete_offer_type_category, .delete_placement, .delete_offer, .delete_email_template, .delete_email, .delete_communication_package, .delete_user, .delete_country, .delete_language, .delete_category", function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        ctrl  = $(this).data('controller'),
        id    = $(this).data('id'),
        method    = $(this).data('method');
        if(method == 'delete'){
          if (!confirm('Are you sure you want to delete?')){
            App.manageDynamicLoader('hide');
            return false;
          }
        }
        Controller.commonDeleteHandler(ctrl, id, method);
      });
      /*=======================Delete-Contact/Client/Partner/Channel/OfferTypes/Project/Placement/Offer================*/
      $(".body_action_link").on("click", function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        ctrl  = $(this).data('controller'),
        id    = $(this).data('id'),
        partial_view  = $(this).data('partial'),
        method    = $(this).data('method');
        if(partial_view != undefined){
          Controller.anchorHandlerPartialType(ctrl, id, method, partial_view);
          return false;
        }
        Controller.commonAnchorHandler(ctrl, id, method);
      });
      $(".datatable").on("click", ".assign_contact_modal", function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        ctrl      = $(this).data('controller'),
        id        = $(this).data('id'),
        method    = $(this).data('method')
        selector      = $(this).data('selector');
        Controller.modalAnchorHandler(ctrl, id, method, selector)
      });
      /*=======================Contact Section================*/
      $('#contact-language').off().on('change', function(e){
        e.preventDefault();
        language_id = $(this).val();
        all_titles = $('#title_mr, #title_mrs, #title_ms, #title_herr, #title_frau');
        german_titles = $('#title_herr, #title_frau');
        english_titles = $('#title_mr, #title_mrs, #title_ms');
        if(language_id != ''){
          if(language_id == 2){
            english_titles.hide();
            german_titles.show();
            $('#contact-title').val('Select Below');
            return false;
          }else{
            german_titles.hide();
            english_titles.show();
            $('#contact-title').val('Select Below');
            return false;
          }
        }else{
          all_titles.hide();
          $('#contact-title').val('Select Below');
          return false;
        }
      });
      $(".datatable").on("click", ".contact_id", function(e){
        $this = $(this);
        var input_element_id = $this.attr('id');
        var assign_form = $this.closest('form#contact_assign_form').eq(0);
        if(!assign_form.length){
          var assign_form = $this.closest('form#contact_assign_form_modal').eq(0);
        }
        if($this.is(':checked')){
          var input = '<input type="hidden" name="assign_contact_id[]" class="'+input_element_id+'" value="'+$this.val()+'">';
          assign_form.prepend(input);
        }
        else{
          var hidden_input = $("input[type='hidden']."+input_element_id);
          hidden_input.remove();
        }
      });

      /*=======================Contact Section ends================*/
      /*=======================Address Section================*/
      $('#address_form_btn').off().on("click", function(e){
        e.preventDefault();
        $('#address_form_wrapper').slideToggle();
      })
      /*=======================Address Section ends================*/
      /*=======================Project Section ================*/
      //project logo upload
      $("#logo").off().on("change", function(){
        var fileName = this.value.split(/[\/\\]/).pop();;
        var resp = Fileupload.fileCheck($('#logo'));
        if(resp == false){
          var files = !!this.files ? this.files : [];
          if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
          if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
            reader.onloadend = function(){
              // set image data as background of div
              $("#img_preview").css("background-image", "url("+this.result+")");
              $("#img_preview").css("background-size", "165px 165px");
              $("#img_preview").css("background-repeat", "no-repeat");
              $('#logo_text').val(fileName);
              $('#project_delete_logo').show();
              $('#project_logo_img').remove();
            }
          }else{
            $("#img_preview").css("background-image", "url('assets/images/image-preview.png')");
          $('#logo_text').val('');
          $('#old_project_logo_img').show();
          $('#project_delete_logo').hide();
          }
        }
        else {
          return false;
        }
        App.registerGlobalDomFunctions();
      });
      $('#client').off().on('change', function(e){
        e.preventDefault();
        client_id = $(this).val();
        if(client_id != ''){
          $.ajax({
            url: 'client/client-contact/'+client_id,
            type: "GET",
            success: function(data){
              if(typeof(data.exc) !== 'undefined'){
                alert('Whoops! Your request can not be performed at this time. Please try again later.');
              }
              else if(data == ''){
                $('#client_contact').html('<option disabled>N/A</option>');
              }
              else{
                $('#client_contact').html(data);
              }
              App.manageDynamicLoader('hide');
              App.registerGlobalDomFunctions();
              return false;
            }
          });
        }else{
          $('#client_contact').html('<option disabled>N/A</option>');
          return false;
        }
      });
      $('#project_delete_logo').off().on('click', function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        if (!confirm('Are you sure you want to delete this project logo?')){
          App.manageDynamicLoader('hide');
          return false;
        }
        $("#img_preview").css("background-image", "url('assets/images/image-preview.png')");
        $('#logo_text').val('');
        $(this).hide();
        $('#old_project_logo_img').show();
        App.registerGlobalDomFunctions();
        App.manageDynamicLoader('hide');
        return false;
      });
      $('#show_projectlogo_delete').off().on('click', function(e){
        $this = $(this);
        App.manageDynamicLoader('show');
        var mode = $this.data('mode');
        var project_id = $this.data('id');
        if (!confirm('Are you sure you want to delete this project logo?')){
          App.manageDynamicLoader('hide');
          return false;
        }
        $.ajax({
          url: 'project/'+mode+'/'+project_id,
          type: "GET",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Requested project logo could not delete this time. Please try again later.');
            }
            else{
              App.commonViewLoad(data);
            }
            App.manageDynamicLoader('hide');
            App.registerGlobalDomFunctions();
            return false;
          }
        });
      });
      /*=======================Project Section ends================*/
      /*=======================Placement Section================*/
      $('#placement_project_select').off().on('change', function(e){
        e.preventDefault();
        project_id = $(this).val();
        if(project_id != ''){
          $.ajax({
            url: 'placement/channel-by-project/'+project_id,
            type: "GET",
            success: function(data){
              if(typeof(data.exc) !== 'undefined'){
                alert('Whoops! Your request can not be performed at this time. Please try again later.');
              }
              else{
                $('#placement_channel_select').html(data);
              }
              App.manageDynamicLoader('hide');
              App.registerGlobalDomFunctions();
              return false;
            }
          });
        }else{
          $('#placement_channel_select').html('<option value="">Select Below</option>');
          return false;
        }
      });
      /*=======================Placement Section Ends================*/
      /*=======================Offer Section================*/
      $('.offer_status_label').off().on('click', function(e){
        net = $(this).parent().next().children('input:checkbox').prop('checked', false);
      });
      $('.offer_status_na_label').off().on('click', function(e){
        net = $(this).parent().prev().children('input:checkbox').prop('checked', false);
      });
      $('#offer_project_select').off().on('change', function(e){
        e.preventDefault();
        project_id = $(this).val();
        if(project_id != ''){
          $.ajax({
            url: 'offer/form-data-by-project/'+project_id,
            type: "GET",
            success: function(data){
              if(typeof(data.exc) !== 'undefined'){
                alert('Whoops! Your request can not be performed at this time. Please try again later.');
              }
              else{
                $('#select_project_view_wrapper').html(data);
              }
              App.registerGlobalDomFunctions();
              return false;
            }
          });
          $.ajax({
            url: 'offer/project-ji-users/'+project_id,
            type: "GET",
            success: function(data){
              if(typeof(data.exc) !== 'undefined'){
                alert('Whoops! Your request can not be performed at this time. Please try again later.');
              }
              else{
                select_na = '<option value="">Select Below</option>';
                select_na_disabled = '<option value="" disabled="disabled">N/A</option>';
                $('#offer_ji_contact_select').html(select_na);
                $('#offer_secondary_jiuser_select').html('');
                var obj = jQuery.parseJSON(data);
                $.each(obj, function(key, value){
                  $('#offer_ji_contact_select').append('<option value="'+value.id+'">'+value.first_name+' '+value.last_name+"</option>");
                  $('#offer_secondary_jiuser_select').append('<option value="'+value.id+'">'+value.first_name+' '+value.last_name+"</option>");
                });

              }
              App.registerGlobalDomFunctions();
              return false;
            }
          });
        }else{
          select_na = '<option value="">Select Below</option>';
          select_na_disabled = '<option value="" disabled="disabled">N/A</option>';
          na_option = '<p class="label">No matching records found.</p>'
          $('#offer_placement_select_wrapper, #offer_partner_select_wrapper, #offer_offer_tc_select_wrapper, #offer_general_ot_select_wrapper, #offer_detailed_ot_select_wrapper, #offer_partner_contact_select_wrapper').html(na_option);
          $('#offer_ji_contact_select').html(select_na);
          $('#offer_secondary_jiuser_select').html(select_na_disabled);
        }
      });
      $('#offer_partner_select').off().on('change', function(e){
        e.preventDefault();
        partner_id = $(this).val();
        if(partner_id != ''){
          $.ajax({
            url: 'offer/partner-contacts-by-partner/'+partner_id,
            type: "GET",
            success: function(data){
              if(typeof(data.exc) !== 'undefined'){
                alert('Whoops! Your request can not be performed at this time. Please try again later.');
              }
              else{
                $('#select_primary_contact_partial_wrapper').html(data);
              }
              App.registerGlobalDomFunctions();
              return false;
            }
          });
        }else{
          no_data = '<p class="label">No matching records found.</p>'
          $('#offer_partner_contact_select_wrapper').html(no_data);
        }
      });
      /************* offer_edit_placement, placement select on change ***************/
      $('#select_offer_placement_id').off().on('change', function(e){
        e.preventDefault();
        placement_id = $(this).val();
        offer_id = $('#offer_id').val();
        post_data = { placement_id: placement_id, offer_id: offer_id };
        if(placement_id != ''){
          $.ajax({
            url: 'offer/placement-by-offer',
            data: post_data,
            type: "POST",
            success: function(data){
              if(typeof(data.exc) !== 'undefined'){
                alert('Whoops! Your request can not be performed at this time. Please try again later.');
              }
              else{
                $('#placement_start_date').val(data[0].placement_start_date);
                $('#placement_end_date').val(data[0].placement_end_date);
                $('#offer_placement_id').val(data[0].id);
                $('#placement_start_date, #placement_end_date').prop('disabled', false);
              }
              App.manageDynamicLoader('hide');
              App.registerGlobalDomFunctions();
              return false;
            }
          });
        }else{
          $('#placement_start_date, #placement_end_date').val('').attr('disabled', 'disabled');
          return false;
        }
      });
      /************* offer_ji_contact_select on change ***************/
      $('#offer_ji_contact_select').off().on('change', function(e){
        project_id = $('#offer_project_select').val();
        e.preventDefault();
        ji_contact_id = $('#offer_ji_contact_select').val();
        if(ji_contact_id == ''){
          ji_contact_id = 0;
        }
        $.ajax({
          url: 'offer/secondary-users-data/'+ji_contact_id+'/'+project_id,
          type: "GET",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Your request can not be performed at this time. Please try again later.');
            }
            else{
              $('#secondary_users_wrapper').html(data);
            }
            App.registerGlobalDomFunctions();
            return false;
          }
        });
      });
      /************* offer_ji_contact_select on change ends ***************/
      /*=======================Offer Section Ends================*/
      /*=======================Email-Template Section Starts================*/
      $('#email_template_attach').off().on('click', function(e){
        $this = $(this);
        var input_file = $this.siblings('input[type="file"].empty_file');
        input_file.click();
      });
      $('.template_add_attachment').off().on('change', function(e){
        $this = $(this);
        var input_original_value = $this.val().split('/').pop().split('\\').pop();
        var input_value = input_original_value.split('/').pop().split('\\').pop();
        var content = '<span class="attachment-item">'+input_value+'&nbsp;<a class="delete_template_attachment" style="cursor:pointer" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;&nbsp;</span>';
        var input_file = '<input type="file" name="attachments[]" id="" style="display:none;" class="template_add_attachment empty_file">';
        $this
          .removeClass('empty_file')
          .siblings('.attachment-content-wrapper')
            .append(content)
          .end()
          .parent('.attachment_main_wrapper')
            .append(input_file);
        App.registerGlobalDomFunctions();
      });
      $('.delete_template_attachment').off().on('click', function(e){
        if (!confirm('Are you sure you want to delete this attachment?')){
          return false;
        }
        var parent_item = $(this).parent();
        var index = parent_item.index();
        var total_data = $('span.attachment-item').length;
        var new_data = $('.template_add_attachment').length-1;
        var old_data = total_data-new_data;
        var delete_index = index-old_data;
        var content_id = $(this).data('id');
        if(content_id != undefined){
          var attachment = $(this).data('attachment');
          var data = {id: content_id, file: attachment};
          $.ajax({
            url: 'email-template/delete-attachment',
            data: data,
            type: "POST",
            // headers: {'X-Requested-With': 'XMLHttpRequest'},
            success: function(data){
              if(data == false){
                alert('Could not find/delete this file now. Please try again.');
              }else{
                parent_item.remove();
              }
            }
          });
        }else{
          if(total_data == new_data){
            $('.template_add_attachment').eq(index).remove();
            parent_item.remove();
          }else{
            parent_item.remove();
            $('.template_add_attachment').eq(delete_index).remove();
          }
        }
        App.registerGlobalDomFunctions();
      });
      $('#email_template_type').off().on('change', function(e){
        var type_value = $(this).val();
        var activation_variables = ['{activation_link}'];
        var microsite1_variables = ['{microsite1_link_en}', '{microsite1_link_de}'];;
        var microsite2_final_variables = ['{microsite2_link_en}', '{microsite2_link_de}'];
        var microsite2_reminder_variables = ['{microsite2_link_en}', '{microsite2_link_de}'];
        if(type_value != ''){
          switch (type_value){
            case 'activation':
              $('span.variables_wrapper').html('');
              $('#sender_email').removeAttr('disabled');
              $.each(activation_variables, function(index, value){
                value = ' '+value;
                $('span.variables_wrapper').append(value);
              });
              break;
            case 'offer_extension':
              $('span.variables_wrapper').html('No matching variables found.');
              $('#sender_email').val('').attr("disabled", "disabled");
              break;
            case 'microsite1':
              $('span.variables_wrapper').html('');
              $('#sender_email').removeAttr('disabled');
              $.each(microsite1_variables, function(index, value){
                value = ' '+value;
                $('span.variables_wrapper').append(value);
              });
              break;
            case 'microsite2_final':
              $('span.variables_wrapper').html('');
              $('#sender_email').removeAttr('disabled');
              $.each(microsite2_final_variables, function(index, value){
                value = ' '+value;
                $('span.variables_wrapper').append(value);
              });
              break;
            case 'microsite2_reminder':
              $('span.variables_wrapper').html('');
              $('#sender_email').removeAttr('disabled');
              $.each(microsite2_reminder_variables, function(index, value){
                value = ' '+value;
                $('span.variables_wrapper').append(value);
              });
              break;
            default:
              $('#sender_email').removeAttr('disabled');
              $('span.variables_wrapper').html('No matching variables found.');
              break;
          }
        }else{
          $('span.variables_wrapper').html('No matching variables found.');
        }
      });
      $('.add_cc_address').off().on('click', function(e){
        e.preventDefault();
        var item = '';
        item += '<div class="col-md-4 cc_item">';
        item += '<label for="cc_emails"><a class="btn btn-warning btn-mini remove_cc_address"><i class="fa fa-times"></i>&nbsp;Remove</a></label>';
        item += '<div class="cc_emails_wrapper" id="cc_emails_wrapper">';
        item += '<input name="cc_emails[]" id="" type="text"  class="form-control" placeholder="CC Email" value="">';
        item += '</div>';
        item += '</div>';
        $('span.cc_emails_wrapper').append(item);
        App.registerGlobalDomFunctions();
      });
      $('a.remove_cc_address').off().on('click', function(e){
        $(this).parent().parent('.cc_item').fadeOut('fast', function(){
          $(this).remove();
        });
        App.registerGlobalDomFunctions();
      });
      $('.show_delete_attachment').off().on('click', function(e){
        App.manageDynamicLoader('show');
        if (!confirm('Are you sure you want to delete this attachment?')){
          App.manageDynamicLoader('hide');
          return false;
        }
        $this = $(this);
        var attachment = $this.data('attachment');
        var content_id = $this.data('id');
        var type = $this.data('type');
        var post_data = { content_id: content_id, file: attachment, module_type: type };
        $.ajax({
          url: 'email-template/show-delete-attachment',
          data: post_data,
          type: "POST",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Your request can not be performed at this time. Please try again later.');
              App.manageDynamicLoader('hide');
            }
            else{
              App.commonViewLoad(data);
            }
          }
        });
      });
      $('.show_download_attachment').off().on('click', function(e){
        $(location).attr('href',$(this).data('href'));
      });
      /*=======================Email-Template Section Ends==================*/
      /*=======================Email Section Starts==================*/
      $(".datatable").on("click", ".confirm_send", function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        if (!confirm('Are you sure you want to change the status of this email?')){
          App.manageDynamicLoader('hide');
          return false;
        }
        if($(this).prop('checked') == true){
          Controller.commonAnchorHandler('email', $(this).data('eid'), 'enable-confirm');
        } else {
          Controller.commonAnchorHandler('email', $(this).data('eid'), 'disable-confirm');
        }
      });
      //offer reporting datepicker
      $(".month_year_date").each(function(e){
        $(this).datepicker({
          format: "mm-yyyy",
          startView: "months",
          minViewMode: "months"
        });
      });
      //email list queue calender on change event
      $("#email_queue_calender").datepicker( {
          format: "mm-yyyy",
          startView: "months",
          minViewMode: "months"
      }).on('changeDate', function(e){
        $this = $(this);
        $this.unbind('changeDate');
        App.manageDynamicLoader('show');
        var value = $(this).val();
        $.ajax({
          url: 'email/monthly-queue-emails/'+value,
          // data: post_data,
          type: "GET",
          // type: "POST",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Your request can not be performed at this time. Please try again later.');
            }
            else{
              var table = $('#emails_queue_table');
              table.dataTable().fnDestroy();
              $('#list_queue_table_body').html(data);
              table.dataTable().fnDraw();
            }
            App.manageDynamicLoader('hide');
            App.registerGlobalDomFunctions();
            return false;
          }
        });
        $this.bind('changeDate');
      });
      /*=======================Email Section Ends==================*/
      /*=======================Report Section Starts==================*/
      $('#all_contact_export_project').on("change", function(e){
        var projects_selected = $(e.target).val();
        if(projects_selected != null){
          $.ajax({
            url: "report/projects-placements",
            type: "post",
            data: {project_ids: projects_selected},
            success: function(data){
              $('#all_contacts_placement').html(data);
              return false;
            }
          });
        }
        else {
          na_option = '<option value="" disabled="disabled">N/A</option>';
          $('#all_contacts_placement').html(na_option);
        }
      });
      /*=======================Report Section Ends==================*/
      // $(".datatable").on("click", ".table-link, .table-link-search", function(e){
      $(".datatable").on("click", ".body-link, .table-link, .table-link-search", function(e){
        App.manageDynamicLoader('show');
        $this = $(this);
        var controller = $this.data('controller'),
          mode = $this.data('mode'),
          param_id = $this.data('param'),
          class_name = $this.attr("class");
        if(class_name == "table-link-search"){
        // if(class_name == "table-link-search" || class_name == "body-link"){
          // App.setSidebarActive(controller, 'remove');
          App.setDefaultActionActive(controller, mode, 'normal');
        }
        if(param_id != '' && param_id != undefined){
          postUrl = controller+'/'+mode+'/'+param_id;
        }else{
          postUrl = controller+'/'+mode;
        }
        if(controller == 'offer' && mode == 'disable'){
          if (!confirm('Are you sure you want to disbale this offer?')){
            App.manageDynamicLoader('hide');
            return false;
          }
        }
        if(class_name == "body-link" && mode == "show"){
          /** go back links **/
          current_link[0] = controller;
          current_link[1] = mode;
          current_link[2] = param_id;
          array_length = links.length-1;
          if(links[array_length][0] !== controller || links[array_length][1] !== mode){
            sublinks = []; //empty array
            sublinks[0] = controller; // set controller name
            sublinks[1] = mode; //set mode
            sublinks[2] = param_id; //set parameterid
            links.push(sublinks); // push sublinks array to links(go back links) array
            array_length = null; //set length to null
          }
          /** go back links end here **/
        }
        $.ajax({
          url: postUrl,
          type: "get",
          success: function(data){
            var search_key = $this.data('search')
            if(search_key != undefined){
              var resp = {
                view_page: data,
                search: search_key
              };
              App.commonViewLoad(resp);
            }
            else{
              App.commonViewLoad(data);
            }
          }
        });

        e.preventDefault();
      });
      $('.body-link').off().on('click',function(e){
        App.manageDynamicLoader('show');
        $this = $(this);
        var controller = $this.data('controller'),
            mode = $this.data('mode')
            param_id = $this.data('param');
        // App.setSidebarActive(controller, 'remove');
        App.setDefaultActionActive(controller, mode, 'normal');
        if(param_id != '' && param_id != undefined){
          postUrl = controller+'/'+mode+'/'+param_id;
        }else{
          postUrl = controller+'/'+mode;
        }
        if($(this).hasClass('add-back-link')){
          // links.pop();
          task_link = $('.task_link[data-controller="'+controller+'"][data-mode="'+mode+'"]');
          $('.menu_bg').each(function(){
            $(this).removeClass('menu_bg');
          });
          task_link.addClass('menu_bg');
        }
        if(mode == "show"){
          /** go back links **/
          current_link[0] = controller;
          current_link[1] = mode;
          current_link[2] = param_id;
          array_length = links.length-1;
          if(links[array_length][0] !== controller || links[array_length][1] !== mode){
            sublinks = []; //empty array
            sublinks[0] = controller; // set controller name
            sublinks[1] = mode; //set mode
            sublinks[2] = param_id; //set parameterid
            links.push(sublinks); // push sublinks array to links(go back links) array
            array_length = null; //set length to null
          }
          /** go back links end here **/
        }

        $.ajax({
          url: postUrl,
          type: "get",
          success: function(data){
            App.commonViewLoad(data);
          }
        });
        e.preventDefault();
        e.stopPropagation();
        return false;
      });
      $('span.user_add_back').on('click', function(e){
        App.manageDynamicLoader('show');
        controller = $(this).data("controller");
        mode = $(this).data("mode");
        Controller.commonLoadHandler(controller, mode);
        App.setDefaultActionActive(controller, mode, 'normal');
        return false;
      });
      /*=======================Call Center Briefing Section Starts==================*/
      $('#call_center_project').on('change', function(e){
        project_id = $(this).val();
        if(project_id != ''){
          $.ajax({
            url: 'placement/placements-call-center/'+project_id,
            type: "GET",
            success: function(data){
              if(typeof(data.exc) !== 'undefined'){
                alert('Whoops! Your request can not be performed at this time. Please try again later.');
              }
              else{
                $("#call_center_placement").html(data);
              }
              return false;
            }
          });
        }else{
          na_option = '<option value="" disabled="disabled">N/A</option>';
          $('select#call_center_placement').html(na_option);
        }
      });
      /*=======================Call Center Briefing Section Endss==================*/
      /*=====================Search Functionality===========================*/
      $('input#entire_search').on('change', function(e){
        e.preventDefault();
        App.manageDynamicLoader('show');
        Controller.commonFormHandler('dashboard', '#entire_search', 'search');
        $('.menu_bg').each(function(e){
          $(this).removeClass('menu_bg');
        });
        if($('.dashboard_menu').hasClass('active')){
          $('.dashboard_menu').removeClass('active')
                              .removeClass('dashboard_active')
                              .find('span.arrow')
                                .removeClass('open');
        }
        return false;
      });
      /*======================Search Functionality ends=======================*/
    },
    commonViewLoad: function(data){
      if(typeof(data.exc) !== 'undefined'){
        if(typeof(data.custom_message) !== 'undefined'){
          alert(data.custom_message);
        }else{
          alert('Could not perform your action now. Please try again.');
        }
      }
      else if(typeof(data.redirect) !== 'undefined'){
        window.open(data.redirect);
      }
      else if(typeof(data.view) !== 'undefined'){
        $(location).attr('href',$('#call-center-download').attr('href'));
        $(location).attr('href',$('#all_contacts_download').attr('href'));
        $(location).attr('href',$('#offer-month-download').attr('href'));
        $('.page-content').html(data.view);
      }
      else if(typeof(data.view_page) !== 'undefined'){
        $('.page-content').html(data.view_page);
        // Set the defaults for DataTables initialisation
        $('.page-content .datatable').dataTable({
          "bDestroy": true,
          "oSearch": {"sSearch": data.search}
        });
      }
      else if(typeof(data.modal) !== 'undefined'){
        if(data.selector == "#placement_add_form_modal"){
          LiveFetch.fetchPlacement();
        }
        else if(data.selector == "#partner_add_form_modal"){
          LiveFetch.fetchOfferPartner();
        }
        else if(data.selector == "#contact_assign_form_modal"){
          LiveFetch.fetchPrimaryContact();
        }
        if(data.selector == "#contact_assign_form_modal"){
          data.selector = "#partners_list_modal_row";
          var modal_dialog = $(data.selector).parents(".modal-dialog").eq(0);
          modal_dialog.html(data.data);
          $('.datatable').DataTable();
          $('#assign_offer_partners').modal('hide');
          $('#list_offer_partners').modal('show');
          App.manageDynamicLoader('hide');
          App.registerGlobalDomFunctions();
          $('body').scrollTop(0);
          return false;
        }
        var modal_dialog = $(data.selector).parents(".modal-dialog").eq(0);
        if(data.selector == "#partners_list_modal"){
          $('#list_offer_partners').modal('hide');
          $('#assign_offer_partners').modal('show');
          modal_dialog = $('#contact_assign_form_modal').parents(".modal-dialog").eq(0);
        }
        modal_dialog.html(data.data);
        $('.datatable').DataTable();
      }
      else{
        $('.page-content').html(data);
        // $('.page-content .datatable').DataTable().fnDestroy();
        $('.page-content .datatable').DataTable();
        $('input#entire_search').val('');
        $('input#entire_search').unbind('change');
      }
      App.manageDynamicLoader('hide');
      App.registerGlobalDomFunctions();
      $('body').scrollTop(0);
      return false;
    },
    clearModalFormElements: function(selector){
      $(selector).find('input,textarea,select').val('');
      return false;
    },
    setSidebarActive: function(controller, flag){
      var anc = $("a[data-controller='"+controller+"']").first();
      if(controller == "user"){
        var anc = $("a[data-controller='"+controller+"']").eq(1);
      }
      var lists = anc.parents('li');
      $.each(lists, function(key, value){
        link = $(this).children('a');
        if(link.hasClass('main_link')){
          if(!link.parent('li').hasClass('open')){
            if(flag == "remove"){
              link.removeClass('main_link');
            }
            link.click();
            links.pop();
            return false;
          }
        }
      });
    },
    setDefaultActionActive: function(controller, mode, from){
      if($('.dashboard_menu').hasClass('dashboard_active')){
          $('.dashboard_menu').removeClass('open')
                              .removeClass('active')
                              .removeClass('dashboard_active')
                              .find('span.arrow')
                                  .removeClass('open');
      }
      $('.menu_bg').each(function(){
        $(this).removeClass('menu_bg');
      });
      var task_li = $('.task_link[data-controller="'+controller+'"][data-mode="'+mode+'"]');
      task_li.addClass("menu_bg");
      if(controller == "project"){
        if($('.add_info_menu').parent('li').eq(0).hasClass('closed')){
          if(from == "normal"){
            $('.add_info_menu')
            .find('span.arrow')
              .eq(0).addClass('open')
              .end()
            .end()
            .siblings('ul')
              .eq(0).slideDown();
          }
        }
      }
      if(controller == "client" || controller == "user" || controller == "category" || controller == "channel" || controller == "country" || controller == "language" || (controller == "offer_type" && mode == "index" )){
        if($('.add_info_menu').parent('li').eq(0).hasClass('closed')){
          if(from == "normal"){
            $('.add_info_menu')
            .find('span.arrow')
              .eq(0).addClass('open')
              .end()
            .end()
            .siblings('ul')
              .eq(0).slideDown();
          }
        }
      }
      var main_task = task_li.parent('li').parent('ul').siblings('.main_module_link').eq(0);
      if(main_task.parent('li').eq(0).hasClass('closed')){
        if(from == "normal"){
          main_task
          .find('span.arrow').eq(0).addClass('open')
            .end()
          .end()
          .siblings('ul')
          .eq(0).slideDown();
        }
      }
      var sub_task = task_li.parent('li').parent('ul').siblings('.sub_task_link').eq(0);
      if(!sub_task.parent('li').eq(0).hasClass('open')){
        sub_task.click();
        var main_sub_module = sub_task.parent('li').parent('ul').parent('li');
        if(!main_sub_module.hasClass('open')){
          main_sub_module.click();
        }
      }
      return true;
    }
  }

  /*====================================Anchor Events===================================*/
  var links = ['default'];
  var current_link = ['default'];
  $('.sub-menu a.link').off().on('click',function(e){
    App.manageDynamicLoader('show');
    App.manageDynamicLoader('show');
    var controller = $(this).data('controller'),
        mode = $(this).data('mode');
    postUrl = controller+'/'+mode;
    current_link[0] = controller;
    current_link[1] = mode;
    // current_link = sub_current_link;
    /** go back links **/
    current_link[0] = controller;
    current_link[1] = mode;
    array_length = links.length-1;
    if(links[array_length][0] !== controller || links[array_length][1] !== mode){
      sublinks = []; //empty array
      sublinks[0] = controller; // set controller name
      sublinks[1] = mode; //set mode
      links.push(sublinks); // push sublinks array to links(go back links) array
      array_length = null; //set length to null
    }
    App.setDefaultActionActive(controller, mode, 'sidebar_link')
    /** go back links end here **/
    $.ajax({
      url: postUrl,
      type: "get",
      success: function(data){
        App.commonViewLoad(data);
      }
    });
    e.preventDefault();
  });
  $('.main_module_link').off().on("click", function(e){
    App.manageDynamicLoader('show');
    var controller = $(this).data('controller'),
        mode = $(this).data('mode');
    postUrl = controller+'/'+mode;
    parent_li = $(this).parent('li');
    /** go back links **/
    current_link[0] = controller;
    current_link[1] = mode;
    array_length = links.length-1;
    if(links[array_length][0] !== controller || links[array_length][1] !== mode){
      sublinks = []; //empty array
      sublinks[0] = controller; // set controller name
      sublinks[1] = mode; //set mode
      links.push(sublinks); // push sublinks array to links(go back links) array
      array_length = null; //set length to null
    }
    /** go back links end here **/
    if($(this).hasClass('main_link')){
      $.ajax({
        url: postUrl,
        type: "get",
        success: function(data){
          App.setDefaultActionActive(controller, mode, 'main_link');
          App.commonViewLoad(data);
        }
      });
    }else{
      $(this).addClass('main_link');
    }
    //User profile menu
    if($(this).hasClass('user_profile')){
      $('a.menu_bg').each(function(){
        $(this).removeClass('menu_bg');
      });
      dashboard_menu = $('.dashboard_menu');
      dashboard_menu.removeClass("active")
                    .removeClass("dashboard_active")
                    .find('.arrow')
                      .removeClass('open');
      // $('.page-sidebar li > a').parent('li').siblings('li.active').removeClass('active');
      // $('li.open .sub-menu').slideUp();
      // $('li.open').find('span.arrow.open').removeClass('open');
      // $('li.open').removeClass('open');
      // $('#user-options').click();
    }
    e.preventDefault();
    if(mode == "profile"){
      $('#user-options').click();
    }
    return false;
  });
  /*=================================Anchor Event ends=================================*/
  App.registerGlobalDomFunctions();

  var Controller = {
    commonDeleteHandler: function(ctrl, id, functionName){
      postUrl = ctrl+'/'+functionName;
      $.ajax({
        url: postUrl,
        data: "delete_id="+id,
        type: "DELETE",
        success: function(data){
          App.commonViewLoad(data);
        }
      });
    },
    commonUpdateHandler: function(ctrl, selector, functionName){
      formData = $(selector).serialize();
      postUrl = ctrl+'/'+functionName;
      $.ajax({
        url: postUrl,
        data: formData,
        type: "PUT",
        // headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function(data){
          App.commonViewLoad(data);
        }
      });
    },
    commonLoadHandler: function(ctrl, functionName){
      getUrl = ctrl+'/'+functionName;
      $.ajax({
        url: getUrl,
        type: "GET",
        success: function(data){
          App.commonViewLoad(data);
        }
      });
    },
    commonAnchorHandler: function(ctrl, id, functionName){
      postUrl = ctrl+'/'+functionName;
      $.ajax({
        url: postUrl,
        data: "post_id="+id,
        type: "POST",
        success: function(data){
          App.commonViewLoad(data);
        }
      });
    },
    anchorHandlerPartialType: function(ctrl, id, functionName, partial_view){
      postUrl = ctrl+'/'+functionName;
      sub_view = '#'+partial_view;
      $.ajax({
        url: postUrl,
        data: "post_id="+id,
        type: "POST",
        // headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function(data){
          if(typeof(data.exc) !== 'undefined'){
            alert('Whoops! Your request can not be performed at this time. Please try again later.');
          }
          else{
            $(sub_view).html(data);
          }
          App.manageDynamicLoader('hide');
          App.registerGlobalDomFunctions();
          $('body').scrollTop(0);
          return false;
        }
      });
    },
    commonFormHandler: function(ctrl, selector, functionName){
      formData = $(selector).serialize();
      postUrl = ctrl+'/'+functionName;
      $.ajax({
        url: postUrl,
        data: formData,
        type: "POST",
        success: function(data){
          App.commonViewLoad(data);
        }
      });
    },
    multipartFormHandler: function(ctrl, selector, functionName){
      formData = new FormData($(selector)[0]);
      postUrl = ctrl+'/'+functionName;
      $.ajax({
        url: postUrl,
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        type: "POST",
        success: function(data){
          App.commonViewLoad(data);
        }
      });
    },
    modalFormHandler: function(ctrl, selector, functionName){
      formData = $(selector).serialize();
      postUrl = ctrl+'/'+functionName;
      $.ajax({
        url: postUrl,
        data: formData,
        type: "POST",
        success: function(data){
          response = {data: data, modal: "yes", selector: selector};
          App.commonViewLoad(response);
        }
      });
    },
    modalAnchorHandler: function(ctrl, id, functionName, selector){
      postUrl = ctrl+'/'+functionName;
      $.ajax({
        url: postUrl,
        data: "post_id="+id,
        type: "POST",
        success: function(data){
          response = {data: data, modal: "yes", selector: selector};
          App.commonViewLoad(response);
        }
      });
    }
  };

  //file upload
  var Fileupload = {
    fileCheck: function(obj){
      var fileExtension = ['jpeg', 'jpg', 'png', 'bmp'];
      if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1){
        alert(" Invalid File Type!\nOnly '.jpeg','.jpg', '.png' formats are allowed.");
        obj.val('');
      }
      else {
        return false;
      }
    }
  };

  /**
   * Object to fetch live data.
   * @type {Object}
   */
  var LiveFetch = {
    fetchPlacement: function(){
      project_id = $('#offer_project_select').val();
      if(project_id != ''){
        $.ajax({
          url: 'placement/by-project/'+project_id,
          type: "GET",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Your request can not be performed at this time. Please try again later.');
            }
            else{
              if(!$("#offer_placement_select").length){
                var select = '<select name="placement_ids[]" id="offer_placement_select" class="select2 form-control" multiple="multiple"></select>';
                $('#offer_placement_select_wrapper').html(select);
                $('#offer_placement_select').html('');
              }else{
                $('#offer_placement_select').html('');
              }
              $.each(data.placements, function(key, value){
                var select_option = '<option value="'+value.id+'">'+value.title+'</option>';
                $('#offer_placement_select').append(select_option);
              });
            }
            App.manageDynamicLoader('hide');
            App.registerGlobalDomFunctions();
            return false;
          }
        });
      }else{
        no_data = '<p class="label">No matching records found.</p>'
        $('#offer_placement_select_wrapper').html(no_data);
        return false;
      }
    },
    fetchOfferPartner: function(){
      project_id = $('#offer_project_select').val();
      if(project_id != ''){
        $.ajax({
          url: 'partner/by-project/'+project_id,
          type: "GET",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Your request can not be performed at this time. Please try again later.');
            }
            else{
              if(!$("#offer_partner_select").length){
                var select = '<select name="partner_id" id="offer_partner_select" class="select2 form-control">'
                    select +='<option value="">Select Below</option></select>';
                $('#offer_partner_select_wrapper').html(select);
              }else{
                $('#offer_partner_select').html('<option value="">Select Below</option></select>');
              }
              $.each(data.partners, function(key, value){
                var select_option = '<option value="'+value.id+'">'+value.partner_name+'</option>';
                $('#offer_partner_select').append(select_option);
              });
            }
            App.manageDynamicLoader('hide');
            App.registerGlobalDomFunctions();
            return false;
          }
        });
      }else{
        no_data = '<p class="label">No matching records found.</p>'
        $('#offer_partner_select_wrapper').html(no_data);
        return false;
      }
    },
    fetchPrimaryContact: function(){
      partner_id = $('#offer_partner_select').val();
      if(partner_id != '' || partner_id != undefined){
        $.ajax({
          url: 'contact/by-partner/'+partner_id,
          type: "GET",
          success: function(data){
            if(typeof(data.exc) !== 'undefined'){
              alert('Whoops! Your request can not be performed at this time. Please try again later.');
            }
            else{
              if(!$("#offer_partner_contact_select").length){
                var select_none = '<select name="contact_id" id="offer_partner_contact_select" class="select2 form-control"><option value="">Select Below</option></select>';
                $('#offer_partner_contact_select_wrapper').html(select_none);
              }else{
                $('#offer_partner_contact_select').html('<option value="">Select Below</option></select>');
              }
              $.each(data.contacts, function(key, value){
                var select_option = '<option value="'+value.id+'">'+value.first_name+' '+value.last_name+'</option>';
                $('#offer_partner_contact_select').append(select_option);
              });
            }
            App.manageDynamicLoader('hide');
            App.registerGlobalDomFunctions();
            return false;
          }
        });
      }else{
        no_data = '<p class="label">No matching records found.</p>'
        $('#offer_partner_contact_select_wrapper').html(no_data);
        return false;
      }
    }
  };
});