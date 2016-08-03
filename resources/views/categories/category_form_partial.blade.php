<!-- Project Category Form -->
<form class="form-no-horizontal-spacing" id="country_form" data-controller="category" data-type="normal" data-method="{{ isset($category_details) ? 'update' : 'create'}}">
  <div class="row form-row">
    <!-- Category Label -->
    <div class="col-md-4">
      <label for="label">Category Name</label>
      <input name="label" id="label" type="text" class="form-control" placeholder="Category Name" value="{{ $category_details[0]->label or old('label') }}">
      <span class="error">
        <label for="label" class="error">
          {{ $errors->first('label') }}
        </label>
      </span>
    </div>
    <!-- End Category label -->
    <div class="col-md-4">
      @if(isset($category_details))
        <input type="hidden" name="category_id" value="{{ $category_details[0]->id }}">
      @endif
      <label for="name" style="visibility: hidden">{{ isset($category_details) ? 'Edit' : 'New'}}Category</label>
      <button class="btn btn-danger btn-cons" type="submit">{{ isset($category_details) ? 'Update' : 'Create'}} Category</button>
      @if(isset($category_details))
        <button class="btn btn-danger btn-cons body-link" type="button" data-controller="category" data-mode="index">Cancel</button>
      @endif
    </div>
  </div>
</form><!--Project Category form ends--><hr>