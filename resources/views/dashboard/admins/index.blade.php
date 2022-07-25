@extends('layout.master')

@section('page')
 {{__("site.admins")}}
@endsection

@section('link')
{{route('dashboard.admins.index')}}
@endsection

@section('content')

     <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header card-header-style">
                <h3 class="card-title" style="float: left">{{__("site.show_admins")}}</h3>
                <a href="{{ route("dashboard.admins.create") }}"><button style="float:right" class="btn btn-primary" title="{{ __("site.create_admins") }}"><i class="fa fa-plus"></i></button></a>
              <div class="clearfix"></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form id="searchForm" action="{{route('dashboard.admins.index')}}" method="GET">
                @csrf
                <button style="margin-bottom: 10px" type="button" class="btn btn-warning" id="showFilters">Filters</button>
                <div class="row" id="filters" style="display: none">
                  <div class="form-group col-md-4" style="border:1px solid #ddd">
                    {{-- <label for="registeration_date">Registration Date</label> --}}
                    <div class="form-group">
                      <label for="from">{{__("site.from")}}:</label>
                      <input type="datetime-local" id="from" name="from_to[]" value="{{ isset(request("from_to")[0]) ? request("from_to")[0] :"" }}" class="form-control">
                      <label for="to">{{__("site.to")}}:</label>
                      <input type="datetime-local" id="to" name="from_to[]" value="{{ isset(request("from_to")[1]) ? request("from_to")[1] : "" }}" class="form-control">
                    </div>
                  </div>
                  @role('owner')
                  {{--  <div class="form-group col-md-4" style="border:1px solid #ddd">
                    <div class="form-group">
                      <label for="organization">{{__("site.organization")}}</label>
                      <select id="organization" name="organization" class="form-control">
                        <option value="">~~{{__("site.choose")}}~~</option>
                        @foreach (selections(\App\Models\Organization::class) as $organization)
                          <option {{ request("organization") ? (request('organization') == $organization->id ? 'selected' : '') : "" }} value="{{$organization->id}}">{{$organization->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>  --}}
                  @endrole
                </div>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" value="{{ request("search") ?: "" }}" name="search" placeholder="{{__("site.search")}}">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">{{__("site.search")}}</button>
                  </div>
                </div>
              </form>
            <div class="table-container">
              <table id="manageAdminsTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__("site.name")}}</th>
                        <th>{{__("site.username")}}</th>
                        <th>{{__("site.email")}}</th>
                        <th>{{ __("site.activity") }}</th>
                        <th>{{__("site.options")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                    <tr>

                        <td>{{$loop->iteration}}</td>
                        <td>{{$admin->name}}</td>
                        <td>{{$admin->username}}</td>
                        <td>{{$admin->email}}</td>

                        <td>
                            @if(\Cache::has($admin->id.'_is_online'))
                                <span class="text-success">Online</span>
                            @else
                                <span class="text-secondary">Offline</span>
                            @endif
                        </td>

                        <td>
                          @if (auth()->user()->hasPermission("update_admins"))
                          <a href="{{route("dashboard.admins.edit", $admin->id)}}"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                          @else
                          <a href="#"><button class="btn btn-info disabled"><i class="fa fa-edit"></i></button></a>
                          @endif


                          @if (auth()->user()->hasPermission("delete_admins"))
                          <form action="{{route('dashboard.admins.destroy',$admin->id)}}" method="POST" style="display:inline-block">
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-danger delete" type="submit"><i class="fa fa-trash"></i></button>
                          </form>
                          @else
                          <button class="btn btn-danger disabled" type="button"><i class="fa fa-trash"></i></button>
                          @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>

            </div>
            <!-- /.card-body -->
           <div class="card-footer clearfix">
              {{$admins->appends(request()->query())->links()}}
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection


@section('js')
<script>
  $(".delete").on("click",function(e){
    e.preventDefault();
    var btn = $(this);
    Swal.fire({
      title: '{{__("site.confirm_delete")}}',
      text: '{{__("site.confirm_delete")}}',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: '{{__("site.confirm")}}',
      cancelButtonText: '{{__("site.cancel")}}'
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          btn.parent('form').submit();
        }
      })
     });
</script>

<script src="{{ asset("js/custom/admins.js") }}" defer></script>

@endsection
