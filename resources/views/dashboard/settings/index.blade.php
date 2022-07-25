@extends('layout.master')

@section('page')
 {{__("site.settings")}}   
@endsection

@section('link')
{{route('dashboard.settings.index')}}
@endsection


@section('content')

     <div class="row">
        <div class="col-12">
          <div class="card">

            <div class="card-header card-header-style">
              <h3 class="card-title">{{__("site.edit_settings")}}</h3>
            </div>
            
            <!-- /.card-header -->
            <form action="{{route("dashboard.settings.update", $setting->id)}}" method="POST"  enctype="multipart/form-data">
              @csrf
              @method("PUT")
              <div class="card-body">


                <div class="form-group">
                    <label for="developer">{{__('site.developer')}}</label>
                    <input type="text" style="text-align: center" class="form-control" disabled value="{{$setting->developer}}">
                </div>                
                
                <hr>


                  <div class="form-group">
                      <label for="app_name">{{__('site.app_name')}}</label>
                      <input type="text" style="text-align: center" class="form-control" placeholder="{{__("site.app_name")}}" value="{{$setting->app_name}}" name="app_name">
                  </div>
                  <div class="form-group">
                    <label for="email">{{__('site.email')}}</label>
                    <input type="email" style="text-align: center" class="form-control" placeholder="{{__("site.email")}}" value="{{$setting->email}}" name="email">
                  </div>

                  <div class="form-group">
                    <label for="phone">{{__('site.phone')}}</label>
                    <input type="tel" class="form-control" name="phone" id="phone" value="{{$setting->phone}}" placeholder="{{__('site.enter') . '  ' . __('site.phone')}}">
                  </div>

                  <div class="form-group">
                    <label for="address">{{__('site.address')}}</label>
                    <textarea class="form-control" name="address" id="address" placeholder="{{__('site.enter') . '  ' . __('site.address')}}">{{$setting->address}}</textarea>
                  </div>
                  
                  <div class="form-group">
                    <label for="img">{{__("site.photo")}}</label>
                    <input type="file" class="form-control imageInput" name="img" id="photo">
                  </div>

                  <div class="form-group">
                    <img src="{{$setting->logo_pic}}" width="150" class="img-thumbnail img-preview" height="150px">
                  </div>
                
                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{__('site.save')}}</button>
              </div>

              </form>


            </div>
            <!-- /.card-body -->
           
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection


@section('js')

<script>
    $("#settingsList").addClass("active");
</script>

@endsection