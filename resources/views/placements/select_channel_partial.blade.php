@if(isset($channels_by_project))
  @if($channels_by_project->isEmpty())
    <option value="">Select Below</option>
  @else
    @foreach($channels_by_project as $channel)
      <option value="{{ $channel->id }}"
      @if(isset($placement_details))
        @if($placement_details[0]->channels->id == $channel->id)
          selected="selected"
        @endif
      @else
        @if(isset($old_channel_id))
          @if($old_channel_id === $channel->id)
            selected="selected"
          @endif
        @endif
      @endif
      >{{ $channel->name }}</option>
    @endforeach
  @endif
@else
  <option value="">Select Below</option>
@endif
