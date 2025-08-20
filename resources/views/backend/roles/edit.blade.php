@extends('backend.layouts.app')
@section('title', 'Update role')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Update Role Information</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}">Roles & Permission</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Update Role</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('roles.update'))
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <form action="{{ route('admin.roles.update', $role->id) }}" id="content_form" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="form-group">
                                    <label for="name">Role Name</label>
                                    <input type="text" required name="name" value="{{ $role->name }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 table-responsive">
                            <div class="col-md-12">
                                <h4>Permission </h4>
                                <table class="table table-bordered">
                                    @foreach (App\CPU\Helpers::split_name($permissions) as $key => $element)
                                        <tr>
                                            <td rowspan ="{{ count($element)+1}}">{!! $key !!}</td>
                                            <td rowspan="{{ count($element)+1}}">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input select_all" id="select_all_{{ App\CPU\Helpers::tounderscore($key)}}" data-id="{{ App\CPU\Helpers::tounderscore($key)}}">
                                                    <label class="custom-control-label" for="select_all_{{ App\CPU\Helpers::tounderscore($key)}}">Select all</label>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($element as $per)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input {{ App\CPU\Helpers::tounderscore($key)}}" id="{{$per}}" multiple="multiple" name="permissions[]" value="{{$per}}" {{in_array($per, $role_permissions)?'checked':''}}>
                                                        <label class="custom-control-label" for="{{$per}}">{{ App\CPU\Helpers::toSpan($per) }}</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                            @if ($key=='Accounting')
                                            <tr>
                                                <td colspan="3" class="text-center bg-green">{{ _lang('Visible Some Section') }}</td>
                                            </tr>
                                            @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-soft-success" id="submit">
                                <i class="bi bi-send"></i>
                                Update
                            </button>

                            <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@push('script')
    <script>
        $(document).on('click','.select_all',function(){
            var id =$(this).data('id');
            if (this.checked) {
                $("."+id).prop('checked', true);
            } else{
                $("."+id).prop('checked', false);
            }
        });
    </script>
@endpush