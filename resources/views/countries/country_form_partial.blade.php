<!-- Country Form -->
<form class="form-no-horizontal-spacing" id="country_form" data-controller="country" data-type="normal" data-method="{{ isset($country_details) ? 'update' : 'create'}}">
  <div class="row form-row">
    <!-- Country Label -->
    <div class="col-md-4">
      <label for="label">Country Name</label>
      <input name="label" id="label" type="text" class="form-control" placeholder="Country Name" value="{{ $country_details[0]->label or old('label') }}">
      <span class="error">
        <label for="label" class="error">
          {{ $errors->first('label') }}
        </label>
      </span>
    </div>
    <!-- End Country label -->
    <div class="col-md-4">
      @if(isset($country_details))
        <input type="hidden" name="country_id" value="{{ $country_details[0]->id }}">
      @endif
      <label for="name" style="visibility: hidden">{{ isset($country_details) ? 'Edit' : 'New'}}Country</label>
      <button class="btn btn-danger btn-cons" type="submit">{{ isset($country_details) ? 'Update' : 'Create'}} Country</button>
      @if(isset($country_details))
        <button class="btn btn-danger btn-cons body-link" type="button" data-controller="country" data-mode="index">Cancel</button>
      @endif
    </div>
  </div>
</form><!--Country form ends--><hr>