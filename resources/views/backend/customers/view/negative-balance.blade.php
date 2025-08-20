<div class="row">
    <div class="col-md-12 table-responsive">

        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Previous Requests</b>
                </h4>
            </div>
        </div>

        @if(isset($model->negativeBalanceRequest) && count($model->negativeBalanceRequest))
            <div class="accordion" id="accordionExample">
                @foreach($model->negativeBalanceRequest as $data)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{$data->id}}">
                            <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{$data->id}}"
                                    aria-expanded="true"
                                    aria-controls="collapse{{$data->id}}">

                                        <span class="mx-2">
                                            Amount: {{$data->currency->symbol}}{{$data->amount}}
                                        </span>
                                <span class="mx-2">
                                            Installment:  {{$data->installmentPlan->name}}
                                        - {{$data->installmentPlan->length}} Months
                                        + {{$data->installmentPlan->extra_charge_percent}}%
                                        </span>

                                <span
                                    class="mx-auto badge bg-{{$data->is_declined?'danger':($data->is_approved?'success':'warning')}}">
                                        {{$data->is_declined?'Declined':($data->is_approved?'Approved':'Pending')}}
                                    </span>
                            </button>
                        </h2>
                        <div id="collapse{{$data->id}}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{$data->id}}"
                                data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Currency</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Document 1</th>
                                        <th scope="col">Document 2</th>
                                        <th scope="col">Additional Documents</th>
                                        <th scope="col">Requested At</th>
                                        <th scope="col">View</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row">{{$data->currency->code}}
                                            - {{$data->currency->symbol}}</th>
                                        <td>{{$data->amount}}</td>
                                        <td class="text-center">
                                            <a target="_blank"
                                                href="{{asset($data->document)}}">
                                                <i class="bi bi-file-earmark-break-fill"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a target="_blank"
                                                href="{{asset($data->document_2)}}">
                                                <i class="bi bi-file-earmark-break-fill"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if(json_decode($data->document_3) !== null)
                                                @foreach(json_decode($data->document_3) as $additional)
                                                    <a class="mx-auto" target="_blank"
                                                        href="{{asset($additional)}}">
                                                        <i class="bi bi-file-earmark-break-fill"></i>
                                                    </a>
                                                @endforeach
                                            @endif
                                        </td>

                                        <td>
                                            {{get_system_date($data->created_at)}} {{get_system_time($data->created_at)}}
                                        </td>
                                        <td>
                                            <a class="btn btn-fill-out btn-info" href="{{ route('admin.balance.request.view', $model->id) }}" target="_blank">
                                                <i class="bi bi-eye"></i>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <p>
                                    {{$data->description}}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @else    
            <p class="text-center">
                {!! App\CPU\Images::show('pictures/none.gif') !!} <br>
                <b>Nothing to show</b>
            </p>
        @endif


    </div>
</div>
