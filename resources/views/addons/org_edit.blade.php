
@php
    if(isset($org_id)){
      $org_id = $org_id;
    }else{
      $org_id = "";
    }
@endphp

@if (hasRole('owner'))
<div class="form-group">
  <label for="organization_id">{{__('site.organization')}}</label>
  <select class="form-control" name="organization_id" id="organization_id">
    <option value="">{{__("site.choose")}}</option>
    @foreach ($organizations as $organization)
        <option value="{{$organization->id}}"
          @if ($org_id == $organization->id)
            selected
          @endif  
        >{{$organization->name}}</option>
    @endforeach
  </select>
</div>    
@else
<input type="hidden" name="organization_id" id="organization_id" value="{{user_organization('id')}}">
@endif

