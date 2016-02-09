@extends('dashboard/storyteller')
@section('title', 'Diablerie Experience')
@section('dashboard-style') 
	.hidden {
		display: none;
	}

	.button.submit-journal {
		padding: 5px 5px;
		margin: 0 auto;
	}

	.journal-grid {
	   -webkit-touch-callout: none;
	    -webkit-user-select: none;
	    -khtml-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
	}
@endsection
@section('storyteller-script')

@endsection
@section('storyteller-content')
<? $months = [];
for ($i = 8; $i >= 0; $i--) {
    $months[] = strtotime( date( 'Y-m-01' )." -$i months");
} ?>

<div class="row left">
	<h2>Diablerie Experience</h2>
	<p>	Clicking on one of buttons below will award diablerie Experience for that month. Any box with a checkmark in it has already been awarded.<br> 
		<b>Awarding diablerie Experience cannot be undone.</b></p>
	<table class="journal-grid responsive">
		<thead>
			<th></th>
			@foreach($months as $m)
			<th>{{date("m/y", $m)}}</th>
			@endforeach
			<th>Has Committed</th>
		</thead>
		<tbody>
			@foreach(Character::where(array('is_npc' => false, 'in_review' => false, 'active' => true))->where('approved_version', '>', 0)->orderBy('name')->get() as $c)
			<tr>
				<td>{{$c->name}}</td>
				@foreach($months as $m) 
				<td>
				@if(CharacterDiablerieExperience::where('character_id', $c->id)->whereRaw('MONTH(date) = ?', [date('m', $m)])->whereRaw('YEAR(date) = ?', [date('Y', $m)])->exists())
					<i class="icon-check"></i>
				@else
					<form action="/dashboard/storyteller/experience/diablerie/award" method="post">
						<input type="hidden" name="month" value="{{$m}}" />
						<input type="hidden" name="id" value="{{$c->id}}" />
						<label for="submit{{$c->id}}-{{$m}}" class="button tiny submit-journal success"><i class='icon-plus'></i></label>
						<input id="submit{{$c->id}}-{{$m}}" type="submit" value="Submit" class="hidden" />
					</form>
				@endif
				</td>
				@endforeach
				<td>{{$c->hasDiablerized() ? "Yes" : "No"}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</div>
@stop
@stop