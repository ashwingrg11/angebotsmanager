<!-- Offer Type Form -->
<form class="form-no-horizontal-spacing" id="offer_type_form" data-controller="offer_type" data-type="normal" data-method="{{ isset($offer_type_details) ? 'update' : 'create'}}">
  <div class="row form-row">
    <!-- Project -->
    <div class="col-md-3">
      <label for="">Select Project</label>
      <select name="project" id="project" class="select2 form-control">
        <option value="">Select Below</option>
        @foreach ($projects as $project)
          <option value="{{ $project->id }}"
          @if(isset($offer_type_details))
            @if($offer_type_details[0]->project_id == $project->id)
              selected="selected"
            @endif
          @else
            @if(old('project') == $project->id)
              selected="selected"
            @endif
          @endif
          >{{ $project->name }}</option>
        @endforeach
      </select>
      <span class="error">
        <label for="project" class="error">
          {{ $errors->first('project') }}
        </label>
      </span>
    </div>
    <!-- Project -->
    <!-- Type -->
    <div class="col-md-3">
      <label for="">Select Offer Type</label>
      <select name="type" id="type" class="select2 form-control">
        <option value="">Select Below</option>
        <option value="general"
          @if(isset($offer_type_details))
            @if($offer_type_details[1]['type'] == 'general')
              selected="selected"
            @endif
          @else
            @if(old('type') == 'general')
              selected="selected"
            @endif
          @endif
        >General Offer Type</option>
        <option value="detailed"
          @if(isset($offer_type_details))
            @if($offer_type_details[1]['type'] == 'detailed')
              selected="selected"
            @endif
          @else
            @if(old('type') == 'detailed')
              selected="selected"
            @endif
          @endif
        >Detailed Offer Type</option>
        <option value="type_category"
          @if(isset($offer_type_details))
            @if($offer_type_details[1]['type'] == 'type_category')
              selected="selected"
            @endif
          @else
            @if(old('type') == 'type_category')
              selected="selected"
            @endif
          @endif
        >Offer Type Category</option>
      </select>
      <span class="error">
        <label for="type" class="error">
          {{ $errors->first('type') }}
        </label>
      </span>
    </div>
    <!--Type-->
    <!-- Offer Type Name -->
    <div class="col-md-3">
      <label for="name">Offer Type Name</label>
      <input name="name" id="name" type="text" class="form-control" placeholder="Offer Type Name" value="{{ $offer_type_details[0]->label or old('name') }}">
      <span class="error">
        <label for="name" class="error">
          {{ $errors->first('name') }}
        </label>
      </span>
    </div>
    <!-- End Offer Type Name -->
    <div class="col-md-3">
      @if(isset($offer_type_details))
        <input type="hidden" name="offer_type_id" value="{{ $offer_type_details[0]->id }}">
        <input type="hidden" name="old_type" value="{{ $offer_type_details[1]['type'] }}">
      @endif
      <label for="name" style="visibility: hidden">{{ isset($offer_type_details) ? 'Edit' : 'New'}}Offer Type</label>
      <button class="btn btn-danger btn-cons" name="country_submit" type="submit">{{ isset($offer_type_details) ? 'Update' : 'Create'}}</button>
      @if(isset($offer_type_details))
        <button class="btn btn-danger btn-cons body-link" name="country_submit" type="button" data-controller="offer_type" data-mode="index">Cancel</button>
      @endif
    </div>
  </div>
</form><!--Offer TYpe form ends--><hr>