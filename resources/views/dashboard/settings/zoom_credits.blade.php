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
              <h3 class="card-title">{{__("site.settings")}}</h3>
            </div>
            <!-- /.card-header -->
            <form id="fileForm" action="{{route("dashboard.settings.zoom_credits.import")}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">

                <div class="form-group">
                    <label class="form-label" for="customFile">{{__("site.file")}}</label>
                    <input type="file" required class="form-control" name="credits_csv" id="customFile" />
                  </div>

                  <div class="form-group">
                    <!-- Progress bar -->
                        <div style="display: none" class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <!-- Display upload status -->
               <div id="uploadStatus"></div>
               </div>
              

                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{__('site.import')}}</button>
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
    $(document).ready(function(){
         // File upload via Ajax
         $("#fileForm").on('submit', function(e){
             e.preventDefault();
             $.ajax({
                 xhr: function() {
                     var xhr = new window.XMLHttpRequest();
                     $(".progress").show();
                     xhr.upload.addEventListener("progress", function(evt) {
                         if (evt.lengthComputable) {
                             var percentComplete = ((evt.loaded / evt.total) * 100);
                             $(".progress-bar").width(percentComplete + '%');
                             $(".progress-bar").html(percentComplete+'%');
                         }
                     }, false);
                     return xhr;
                 },
                 type: 'POST',
                 url: $("#fileForm").attr("action"),
                 data: new FormData(this),
                 contentType: false,
                 cache: false,
                 processData:false,
                 beforeSend: function(){
                     $(".progress-bar").width('0%');
                     $('#uploadStatus').html('<center><img width="100" height="100" src="{{asset("pics/loading.gif")}}"/></center>');
                 },
                 error:function(response){
                    var errors = response.responseJSON.errors;
                    if(errors.credits_csv.length > 0){
                      $('#uploadStatus').html('<p style="color:#EA4335;">'+errors.credits_csv[0]+'.</p>');
                    }else{
                      $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                    }
                 },
                 success: function(response){
                     if(response == 'imported'){
                         $('#fileForm')[0].reset();
                         $('#uploadStatus').html('<p style="color:#28A74B;">{{__("site.added_successfully")}}!</p>');
                         $(".progress").css("display",'none');
                     }
                 }
             });
         });
         
   });
 </script>

@endsection