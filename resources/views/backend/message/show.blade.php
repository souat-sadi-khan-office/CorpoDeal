<div class="modal-header">
    <h5 class="modal-title">View Contact Message Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
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
                    <td>{{ $model->email }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $model->phone }}</td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td>{{ $model->subject }}</td>
                </tr>
                <tr>
                    <td colspan="2">Message</td>
                </tr>
                <tr>
                    <td colspan="2">{!! $model->message !!}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-12 mt-3 text-end">
            
        </div>
    </div>
</div>