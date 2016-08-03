@if(!isset($placements))
  @if(Auth::user()->user_type != "admin")
    <option value="" disabled="disabled">N/A</option>
  @endif
@else
  @if($placements->isEmpty() && Auth::user()->user_type != "admin")
    <option value="" disabled="disabled">N/A</option>
  @else
    @foreach ($placements as $placement)
      <option value="{{ $placement->id }}"
      @if(session('placement_ids'))
        @if(in_array($placement->id, session('placement_ids')))
          selected="selected"
        @endif
      @endif
      >{{ $placement->title }}</option>
    @endforeach
  @endif
@endif
@if(Auth::user()->user_type == "admin")
  <option value="n_a"
  @if(session('placement_ids'))
    @if(in_array('n_a', session('placement_ids')))
      selected="selected"
    @endif
  @endif
  >N/A</option>
@endif