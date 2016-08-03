<!-- Channel Form -->
<form class="form-no-horizontal-spacing" id="channel_form" data-controller="channel" data-type="normal" data-method="{{ isset($channel_details) ? 'update' : 'create'}}">
  <div class="row form-row">
    <!-- Project -->
    <div class="col-md-4">
      <label for="">Select Project</label>
      <select name="project" id="project" class="select2 form-control">
        <option value="">Select Below</option>
        @foreach ($projects as $project)
          <option value="{{ $project->id }}"
          @if(isset($channel_details))
            @if($channel_details[0]->project_id == $project->id)
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
    <!-- Project ends -->
    <!-- Channel Name -->
    <div class="col-md-4">
      <label for="name">Channel Name</label>
      <input name="name" id="name" type="text" class="form-control" placeholder="Channel Name" value="{{ $channel_details[0]->name or old('name') }}">
      <span class="error">
        <label for="name" class="error">
          {{ $errors->first('name') }}
        </label>
      </span>
    </div>
    <!-- End Channel Name -->
    <div class="col-md-4">
      @if(isset($channel_details))
        <input type="hidden" name="channel_id" value="{{ $channel_details[0]->id }}">
      @endif
      <label for="name" style="visibility: hidden">{{ isset($channel_details) ? 'Edit' : 'New'}}Channel</label>
      <button class="btn btn-danger btn-cons" name="country_submit" type="submit">{{ isset($channel_details) ? 'Update' : 'Create'}} Channel</button>
      @if(isset($channel_details))
        <button class="btn btn-danger btn-cons body-link" name="country_submit" type="button" data-controller="channel" data-mode="index">Cancel</button>
      @endif
    </div>
  </div>
</form><!--channel form ends--><hr>