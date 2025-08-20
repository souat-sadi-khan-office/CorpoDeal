<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-9">
                <h4 class="h6 mb-3">
                    <b>User General Information</b>
                </h4>
            </div>
            <div class="col-md-3 text-end">
                <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.edit', $model->id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil-square"></i>
                </a>
            </div>
        </div>
        <table class="table table-bordered">
            <tr>
                <td>Delete User Account</td>
                <td>
                    <span class="badge bg-{{ $model->is_deleted == 1 ? 'warning' : 'success' }}">{{ $model->is_deleted == '1' ? 'Deleted' : 'Not Deleted' }}</span>
                </td>
            </tr>
            <tr>
                <td>Premium User</td>
                <td>
                    <span class="badge bg-{{ $model->is_premium == 1 ? 'warning' : 'success' }}">{{ $model->is_premium == 1 ? 'Premium User' : 'Normal User' }}</span>
                </td>
            </tr>
            @if ($model->currency)
                @if ($model->currency->country)
                    <tr>
                        <td>Country</td>
                        <td>
                            {{ $model->currency->country->name }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>Currency</td>
                    <td>{{ $model->currency->name }}</td>
                </tr>
            @endif
            <tr>
                <td>User Points</td>
                <td>
                    @if ($model->points > 0)
                        <div class="row">
                            <div class="col-md-4">
                                {{ $model->points }}
                            </div>
                            <div class="col-md-8 text-end">
                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'point-history']) }}" style="padding: 5px 10px;" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Points History" style="padding: 3px 8px; font-size: 12px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    @else    
                        {{ $model->points }}
                    @endif    
                </td>
            </tr>
            <tr>
                <td>Name</td>
                <td>{{ $model->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>
                    {{ $model->email }}
                    @if ($model->email_verified_at != null)
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle"></i>
                            Verified
                        </span>
                    @else    
                        <span class="badge bg-warning">
                            <i class="bi bi-exclamation-circle"></i>
                            Not Verified
                        </span>
                    @endif
                </td>
            </tr>
            @if ($model->email_verified_at != null)
                <tr>
                    <td>Email Verified At: </td>
                    <td>{{ get_system_date($model->email_verified_at) }} {{ get_system_time($model->email_verified_at) }}</td>
                </tr>
            @endif
        </table>
    </div>
</div>