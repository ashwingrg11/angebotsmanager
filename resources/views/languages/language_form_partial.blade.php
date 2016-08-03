<!-- Language Form -->
<form class="form-no-horizontal-spacing" id="country_form" data-controller="language" data-type="normal" data-method="{{ isset($language_details) ? 'update' : 'create'}}">
  <div class="row form-row">
    <!-- Language Label -->
    <div class="col-md-4">
      <label for="label">Language Name</label>
      <input name="label" id="label" type="text" class="form-control" placeholder="Language Name" value="{{ $language_details[0]->label or old('label') }}">
      <span class="error">
        <label for="label" class="error">
          {{ $errors->first('label') }}
        </label>
      </span>
    </div>
    <!-- End Language label -->
    <div class="col-md-4">
      @if(isset($language_details))
        <input type="hidden" name="language_id" value="{{ $language_details[0]->id }}">
      @endif
      <label for="name" style="visibility: hidden">{{ isset($language_details) ? 'Edit' : 'New'}}</label>
      <button class="btn btn-danger btn-cons" type="submit">{{ isset($language_details) ? 'Update' : 'Create'}}</button>
      @if(isset($language_details))
        <button class="btn btn-danger btn-cons body-link" type="button" data-controller="language" data-mode="index">Cancel</button>
      @endif
    </div>
  </div>
</form><!--Language form ends--><hr>