@extends('layout.master')

@section('page')
 {{__("site.profile")}}
@endsection

@section('link')
{{route('dashboard.profile.index')}}
@endsection


@section('content')

     <div class="row">
        <div class="col-12">
          <div class="card">

            <div class="card-header card-header-style">
              <h3 class="card-title">{{__("site.edit_profile")}}</h3>
            </div>
            <!-- /.card-header -->
            <form action="{{route("dashboard.profile.update", $profile->id)}}" method="POST"  enctype="multipart/form-data">
              @csrf
              @method("PUT")
              <div class="card-body">

                  <div class="form-group">
                      <label for="name">{{__('site.name')}}</label>
                      <input type="text" style="text-align: center" class="form-control" value="{{$profile->user->name}}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="username">{{__('site.username')}}</label>
                    <input type="text" style="text-align: center" class="form-control" value="{{$profile->user->username}}" disabled>
                </div>
                  <hr>
                  <div class="form-group">
                    <label for="photo">{{__("site.photo")}}</label>
                    <input type="file" class="form-control imageInput" name="img" id="photo">
                  </div>

                  <div class="form-group">
                    <img src="{{$profile->pic}}" width="150" class="img-thumbnail img-preview" height="150px">
                </div>

                <div class="form-group">
                    <label for="password">{{__('site.password')}}</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="{{__('site.enter') . '  ' . __('site.password')}}">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">{{__('site.password_confirmation')}}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="{{old("password_confirmation")}}" placeholder="{{__('site.enter') . '  ' . __('site.password_confirmation')}}">
                </div>

                  <div class="form-group">
                    <label for="phone">{{__('site.phone')}}</label>
                    <input type="tel" class="form-control" name="phone" id="phone" value="{{$profile->phone}}" placeholder="{{__('site.enter') . '  ' . __('site.phone')}}">
                  </div>


                  <div class="form-group">
                    <label for="about">{{__('site.about')}}</label>
                    <textarea class="form-control" name="about" id="about" placeholder="{{__('site.enter') . '  ' . __('site.about')}}">{{$profile->about}}</textarea>
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

<script src="{{ asset("js/custom/profile.js") }}" defer></script>

@endsection
