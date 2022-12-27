<div class="form-group">
	<div class="col-md-3 text-right">
		<span>{!! $label !!}</span>
		@if($required) <star>*</star> @endif
	</div>
	<div class="col-{{$md_size}}">
		<textarea type="text" name="{{$name}}" cols="4" id="{{$name}}"   @if($required) required="required" @endif class="form-control">{{$default}}</textarea> 
	</div>
</div>