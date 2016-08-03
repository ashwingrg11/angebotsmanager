<label for="offer_secondary_jiuser_select">Secondary JI user(s)</label>
<select name="ji_user_ids[]" id="offer_secondary_jiuser_select" class="select2 form-control" multiple="multiple">
  @if($ji_users->isEmpty())
    <option disabled>N/A</option>
  @else
    @foreach($ji_users as $user)
      <option value="{{ $user->id }}"
        @if(Session::has('offer_details'))
          @foreach(session('offer_details')[0]->users as $ji_user)
            @if($ji_user->id == $user->id)
              selected="selected"
            @endif
          @endforeach
        @else
          @if(Session::has('ji_user_ids'))
            @if(in_array($user->id, session('ji_user_ids')))
              selected="selected"
            @endif
          @endif
        @endif
      >{{ $user->first_name.' '.$user->last_name }}</option>
    @endforeach
  @endif
</select>