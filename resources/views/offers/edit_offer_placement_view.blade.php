<div class="content">
  @include('shared.notifications')
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="body-link" data-controller="offer" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Offers</p>
    </li>
    <li>
      <a href="#" class="active">Edit Placements</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="offer_placement_edit_form" data-controller="offer" data-type="normal" data-method="update-offer-placement" method="post">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>
              Edit
              <span class="semi-bold">Offer Placements</span>
            </h4>
          </div>
          <div class="grid-body">
            <input type="hidden" name="offer_id" value="{{ $offer_details[0]->id }}" id="offer_id">
            <div class="row column-seperation">
              <div class="col-md-12">
                <div class="row form-row">
                  <!-- Placement -->
                  <div class="col-md-6">
                    <label for="select_offer_placement_id">Select Placement {{ old('placement_id') }} <br>
                    <?php
                      /*foreach ($offer_details[0]->placements as $key => $placement) {
                        echo $placement->pivot->placement_start_date;
                      }*/
                     ?>
                    </label>
                    <select name="placement_id" id="select_offer_placement_id" class="select2 form-control"  >
                      <option value="">Select Below</option>
                      @foreach ($offer_details[0]->placements as $placement)
                        <option value="{{ $placement->id }}"
                          @if($placement->id == old('placement_id'))
                            selected="selected"
                          @endif
                        >{{ $placement->title }}</option>
                      @endforeach
                    </select>
                    <span class="error">
                      <label for="select_offer_placement_id" class="error">{{ $errors->first('placement_id') }}</label>
                    </span>
                  </div><!--Placement-->
                </div>
                <div class="row form-row">
                  <div id="placement_dates_wrapper">
                    <!-- Placement Start Date -->
                    <div class="col-md-6">
                      <label for="placement_start_date">Placement Start Date</label>
                      <input name="placement_start_date" id="placement_start_date" type="text" class="form-control date" placeholder="Start Date" value="">
                      <span class="error">
                        <label for="placement_start_date" class="error">{{ $errors->first('placement_start_date') }}</label>
                      </span>
                    </div><!--Placement Start Date-->
                    <!-- Placement End Date -->
                    <div class="col-md-6">
                      <label for="placement_end_date">Placement End Date</label>
                      <input name="placement_end_date" id="placement_end_date" type="text" class="form-control date" placeholder="End Date" value="">
                      <span class="error">
                        <label for="placement_end_date" class="error">{{ $errors->first('placement_end_date') }}</label>
                      </span>
                    </div><!--Placement End Date-->
                      <input type="hidden" name="offer_placement_id" value="" id="offer_placement_id">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-actions">
              <div class="">
                <button class="btn btn-danger btn-cons" name="addplacement_submit" type="submit">UPDATE</button>
                <button class="btn btn-danger btn-cons body-link" type="button" data-controller="offer" data-mode="index">CANCEL</button>
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
    placement_id = $('#select_offer_placement_id').val();
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
          return false;
        }
      });
    }else{
      $('#placement_start_date, #placement_end_date').val('').attr('disabled', 'disabled');
      return false;
    }
  }())
</script>
