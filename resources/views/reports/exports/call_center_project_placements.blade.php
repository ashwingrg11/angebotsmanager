@if(!isset($placements))
  <option value="" disabled="disabled">N/A</option>
@else
  @if($placements->isEmpty())
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