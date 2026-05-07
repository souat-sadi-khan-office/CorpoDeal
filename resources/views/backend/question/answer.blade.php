<form action="{{ route("admin.customer.question.update") }}" method="POST" class="ajax-form">
    @method('PATCH')
    <input type="hidden" name="id" value="{{ $question->id }}">
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <b>Question Information</b>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="question">Question</label>
                <textarea readonly id="question" class="form-control" cols="30" rows="4">{{ $question->message }}</textarea>
            </div>

            <div class="col-md-12 form-group mt-3">
                <label for="answer">Answer <span class="text-danger">*</span></label>
                <textarea name="answer" id="answer" cols="30" rows="4" class="form-control" required>{{ $answer ? $answer->message : '' }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer text-end">
        <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x"></i>
            Close
        </button>
        <button class="btn btn-sm btn-dark" type="submit" id="submit">
            <i class="bi bi-send"></i>
            Update
        </button>
        <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>
    </div>
</form>