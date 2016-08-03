<div class="row form-row" id="offer_title_wrapper">
  <!-- Offer Title English -->
  <div class="col-md-6" id="offer_title_en">
    <label for="title_en">Offer Title(EN)</label>
    <input name="title_en" id="title_en" type="text"  class="form-control" placeholder="Offer Title: EN"
      @if(Session::has('offer_details'))
        value="{{session('offer_details')[0]->title_en}}"
      @else
        @if(Session::has('offer_title_en'))
          value="{{session('offer_title_en')}}"
        @endif
      @endif
    >
    <span class="error">
      <label for="title_en" class="error">
        @if(Session::has('offer_titleen_form_error'))
          {{ session('offer_titleen_form_error') }}
        @endif
      </label>
    </span>
  </div><!--Offer Title English-->
  <!-- Offer Title DE -->
  <div class="col-md-6" id="offer_title_de">
    <label for="title_de">Offer Title(DE)</label>
    <input name="title_de" id="title_de" type="text"  class="form-control" placeholder="Offer Title: DE"
    @if(Session::has('offer_details'))
      value="{{session('offer_details')[0]->title_de}}"
    @else
      @if(Session::has('offer_title_de'))
        value="{{session('offer_title_de')}}"
      @endif
    @endif
    >
    <span class="error">
      <label for="title_de" class="error">
        @if(Session::has('offer_titlede_form_error'))
          {{ session('offer_titlede_form_error') }}
        @endif
      </label>
    </span>
  </div><!--Offer Title DE-->
</div>
<div class="row form-row">
  <!-- Placement -->
  <div class="col-md-6">
    <label for="offer_placement_select">
      Select Placement
      <a class="add_new_placement_offer btn-mini" style="cursor:pointer;" data-toggle="modal" data-target="#add_new_placement"><i class="fa fa-plus custom-add" data-toggle="tooltip" data-placement="right" title="Add New Placement" data-controller="" rel="tooltip"></i></a>
    </label>
    <div id="offer_placement_select_wrapper">
      @if(isset($project_info))
        @if(!$project_info[0]['placements']->isEmpty())
          <?php
            $project_info[0]['placements'] = Helper::sort_arr_of_obj($project_info[0]['placements'], 'title', 'asc');
          ?>
          <select name="placement_ids[]" id="offer_placement_select" class="select2 form-control" multiple="multiple">
            @foreach($project_info[0]['placements'] as $key => $placement)
              <option value="{{ $placement->id }}"
                @if(Session::has('offer_details'))
                  @foreach (session('offer_details')[0]->placements as $details_placement)
                    @if($details_placement->id == $placement->id)
                      selected="selected"
                    @endif
                  @endforeach
                @else
                  @if(session('placement_ids'))
                    @if(in_array($placement->id, session('placement_ids')))
                      selected="selected"
                    @endif
                  @endif
                @endif
              >{{ $placement->title }}</option>
            @endforeach
          </select>
        @else
          <p class="label">No matching records found.</p>
        @endif
      @else
        <p class="label">No matching records found.</p>
      @endif
    </div>
    <span class="error">
      <label for="placement_ids" class="error">{{ session('placement_ids_form_error') }}</label>
    </span>
  </div><!--Placement-->
  <!-- Project Partner-->
  <div class="col-md-6">
    <label for="offer_partner_select">
      Select Offer Partner
      <!-- <a class="add_new_offer_partner btn-mini" style="cursor:pointer;" data-toggle="modal" data-target="#add_new_partner"><i class="fa fa-plus custom-add" data-toggle="tooltip" data-placement="right" title="Add New Offer Partner" data-controller="" rel="tooltip"></i></a> -->
    </label>
    <div id="offer_partner_select_wrapper">
      @if(isset($project_info))
        @if(!$project_info[0]['partners']->isEmpty())
          <select name="partner_id" id="offer_partner_select" class="select2 form-control"  >
            <option value="">Select Below</option>
              <?php
                $project_info[0]['partners'] = Helper::sort_arr_of_obj($project_info[0]['partners'], 'partner_name', 'asc');
              ?>
              @foreach($project_info[0]['partners'] as $key => $partner)
                <option value="{{ $partner->id }}"
                @if(Session::has('offer_details'))
                  @if(session('offer_details')[0]->partners->id == $partner->id)
                    selected="selected"
                  @endif
                @else
                  @if(session('partner_id') == $partner->id)
                    selected="selected"
                  @endif
                @endif
                >{{ $partner->partner_name }}</option>
              @endforeach
          </select>
        @else
          <p class="label">No matching records found.</p>
        @endif
      @else
        <p class="label">No matching records found.</p>
      @endif
    </div>
    <span class="error">
      <label for="partner_id" class="error">{{ session('partner_id_form_error') }}</label>
    </span>
  </div><!--Project Partner-->
</div>
<div class="row form-row">
  <div id="select_primary_contact_partial_wrapper">
    @include('offers.select_primary_contact_partial')
  </div>
  <div class="col-md-6">
    <label for="offer_offer_tc_select">Offer Type Category</label>
    <div id="offer_offer_tc_select_wrapper">
      @if(isset($project_info))
        @if(!$project_info[0]['offer_type_category_masterlist']->isEMpty())
          <select name="offer_type_category_masterlist_id" id="offer_offer_tc_select" class="select2 form-control"  >
            <option value="">Select Below</option>
              @foreach($project_info[0]['offer_type_category_masterlist'] as $key => $offer_type_category)
                <option value="{{ $offer_type_category->id }}"
                @if(Session::has('offer_details'))
                  @if(session('offer_details')[0]->offer_type_category_masterlist)
                    @if(session('offer_details')[0]->offer_type_category_masterlist->id == $offer_type_category->id)
                      selected="selected"
                    @endif
                  @endif
                @else
                  @if(session('offer_type_category_masterlist_id') == $offer_type_category->id)
                    selected="selected"
                  @endif
                @endif
                >{{ $offer_type_category->label }}</option>
              @endforeach
          </select>
        @else
          <p class="label">No matching records found.</p>
        @endif
      @else
        <p class="label">No matching records found.</p>
      @endif
    </div>
    <span class="error">
      <label for="offer_type_category_masterlist_id" class="error">{{ session('offer_type_category_form_error') }}</label>
    </span>
  </div><!--Offer Type Category-->
</div>
<div class="row form-row">
  <!-- General Offer Type -->
  <div class="col-md-6">
    <label for="offer_general_ot_select">General Offer Type</label>
    <div id="offer_general_ot_select_wrapper">
      @if(isset($project_info))
        @if(!$project_info[0]['general_offer_type_masterlist']->isEmpty())
          <select name="general_offer_type_masterlist_id" id="offer_general_ot_select" class="select2 form-control"  >
            <option value="">Select Below</option>
              @foreach($project_info[0]['general_offer_type_masterlist'] as $key => $general_offer_type)
                <option value="{{ $general_offer_type->id }}"
                @if(Session::has('offer_details'))
                  @if(session('offer_details')[0]->general_offer_type_masterlist)
                    @if(session('offer_details')[0]->general_offer_type_masterlist->id == $general_offer_type->id)
                      selected="selected"
                    @endif
                  @endif
                @else
                  @if(session('general_offer_type_masterlist_id') == $general_offer_type->id)
                    selected="selected"
                  @endif
                @endif
                >{{ $general_offer_type->label }}</option>
              @endforeach
          </select>
        @else
          <p class="label">No matching records found.</p>
        @endif
      @else
        <p class="label">No matching records found.</p>
      @endif
    </div>
    <span class="error">
      <label for="general_offer_type_masterlist_id" class="error">{{ session('general_offer_type_form_error') }}</label>
    </span>
  </div><!--General Offer Type-->
  <!-- Detailed Offer Type-->
  <div class="col-md-6">
    <label for="offer_detailed_ot_select">Detailed Offer Type</label>
    <div id="offer_detailed_ot_select_wrapper">
      @if(isset($project_info))
        @if(!$project_info[0]['detailed_offer_type_masterlist']->isEmpty())
          <select name="detailed_offer_type_masterlist_id" id="offer_detailed_ot_select" class="select2 form-control"  >
            <option value="">Select Below</option>
              @foreach($project_info[0]['detailed_offer_type_masterlist'] as $key => $detailed_offer_type)
                <option value="{{ $detailed_offer_type->id }}"
                @if(Session::has('offer_details'))
                  @if(session('offer_details')[0]->detailed_offer_type_masterlist)
                    @if(session('offer_details')[0]->detailed_offer_type_masterlist->id == $detailed_offer_type->id)
                      selected="selected"
                    @endif
                  @endif
                @else
                  @if(session('detailed_offer_type_masterlist_id') == $detailed_offer_type->id)
                    selected="selected"
                  @endif
                @endif
                >{{ $detailed_offer_type->label }}</option>
              @endforeach
          </select>
        @else
          <p class="label">No matching records found.</p>
        @endif
      @else
        <p class="label">No matching records found.</p>
      @endif
    </div>
    <span class="error">
      <label for="detailed_offer_type_masterlist_id" class="error">{{ session('detailed_offer_type_form_error') }}</label>
    </span>
  </div><!--Detailed Offer Type-->
</div>
<script type="text/javascript">
  (function(){
    partner_id = $('#offer_partner_select').val();
    if(partner_id != '' && partner_id != undefined){
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
          return false;
        }
      });
    }else{
      no_data = '<p class="label">No matching records found.</p>'
      $('#offer_partner_contact_select_wrapper').html(no_data);
    }
  })();
  $(document).ready(function(){
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
  })
</script>
