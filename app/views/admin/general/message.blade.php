@if($type == 1)
<div class="alert alert-success" role="alert">{{ $message }}</div>
@elseif($type == 2)
<div class="alert alert-warning" role="alert">{{ $message }}</div>
@else
<div class="alert alert-danger" role="alert">{{ $message }}</div>
@endif