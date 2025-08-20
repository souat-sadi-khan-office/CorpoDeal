@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'Installment Plans Management')

@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Installment Plans Management</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('installment-plans.create'))
                    <div class="col-sm-6 text-end">
                        <a id="content_management" href="javascript:;"
                        data-url="{{ route('admin.installment.plan.create') }}" class="btn btn-soft-success">
                            <i class="bi bi-plus"></i>
                            Create New
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Name</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Length</th>
                            <th scope="col">Extra Charge</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <th scope="row">
                                    {{$loop->iteration}}
                                </th>
                                <td>
                                    {{$plan->name}}
                                </td>
                                <td>
                                    {{$plan->admin->name}}

                                </td>
                                <td>
                                    {{$plan->length}} Months
                                </td>
                                <td>{{$plan->extra_charge_percent}}%</td>

                                <td>
                                    @if (Auth::guard('admin')->user()->hasPermissionTo('installment-plans.update'))
                                        <div class="form-check form-switch">
                                            <input
                                                data-url="{{route('admin.installment.plan.status', $plan->id)}}"
                                                class="form-check-input"
                                                type="checkbox"
                                                role="switch"
                                                name="status"
                                                id="status{{$plan->id}}"
                                                {{ $plan->status ? 'checked' : '' }}
                                                data-id="{{$plan->id}}"
                                            >
                                        </div>
                                    @else
                                        <span class="badge text-white bg-{{ $plan->status == 1 ? 'success' : 'warning' }}">
                                            {{ $plan->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{get_system_date($plan->created_at)}} {{get_system_time($plan->created_at)}}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    @include('frontend.components.paginate',['products'=>$plans])
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>

        $(function () {

            _statusUpdate();
            _componentRemoteModalLoadAfterAjax();

        });
    </script>
@endpush
