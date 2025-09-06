<div class="modal-header">
    <h5 class="modal-title">
        <b>View Contact Message Information</b>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>Date</td>
                    <td>{{ get_system_date($model->created_at) }} {{ get_system_time($model->created_at) }}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>{{ $model->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>
                        <a style="color:#000;" href="mailto:{{ $model->email }}" target="_blank">
                            {{ $model->email }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>
                        <a style="color:#000;" href="tel:{{ $model->phone }}">{{ $model->phone }}</a>
                    </td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td>{{ $model->subject }}</td>
                </tr>
                <tr>
                    <td colspan="2">Message</td>
                </tr>
                <tr>
                    <td colspan="2">{!! nl2br($model->message) !!}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer text-end">
    <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
        <i class="bi bi-x"></i>
        Close
    </button>
</div>