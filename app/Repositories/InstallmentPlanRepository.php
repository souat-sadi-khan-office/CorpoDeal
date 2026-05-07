<?php


namespace App\Repositories;


use App\CPU\Images;
use App\Jobs\SendRequestApprovalNotification;
use App\Jobs\SendRequestDenyNotification;
use App\Models\InstallmentPlan;
use App\Models\NegativeBalanceRequest;
use App\Models\Notification;
use App\Models\UserInstallment;
use App\Models\UserNegetiveBalanceWallet;
use App\Repositories\Interface\InstallmentPlanInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class InstallmentPlanRepository implements InstallmentPlanInterface
{

    public function plansIndex($request)
    {
        return InstallmentPlan::with('admin:id,name')->latest()->paginate($request->paginate ?? 15);
    }

    public function storePlan($request)
    {
        $creatorId = Auth::guard('admin')->id();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'length' => 'required|integer|min:1',
            'extra_charge_percent' => 'nullable|integer|min:0',
        ]);

        InstallmentPlan::create([
            'name' => $validated['name'],
            'creator_id' => $creatorId,
            'length' => $validated['length'],
            'extra_charge_percent' => $validated['extra_charge_percent'] ?? 0,
            'status' => $request->status ?? 0,
        ]);

        return [
            'message' => 'Installment plan created successfully!',
            'load' => true,
            'status' => true,
        ];
    }

    public function planStatus($id)
    {
        $plan = InstallmentPlan::find($id);

        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Installment Plan not found.'], 404);
        }

        $plan->status = !$plan->status;
        $plan->save();

        return response()->json(['success' => true, 'message' => 'Installment Plan status updated successfully.']);


    }

    //Negative balance
    public function balanceRequests($request)
    {
        return NegativeBalanceRequest::with('currency:id,code,symbol', 'installmentPlan', 'admin:id,name', 'user:id,name,email')->get();
    }

    public function balanceRequest($id)
    {
        return NegativeBalanceRequest::with('currency:id,code,symbol', 'installmentPlan', 'admin:id,name', 'user:id,name,email')->where('id', $id)->first();
    }

    public function balanceRequestsDatatable($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('status', function ($model) {
                $status = $model->is_declined ? "Declined" : ($model->is_approved ? "Approved" : "Pending");
                $badgeClass = $model->is_declined ? "danger" : ($model->is_approved ? "success" : "warning");

                return '<div class="text-center">
                <span class="badge bg-' . $badgeClass . '">' . $status . '</span>
            </div>';
            })
            ->editColumn('amount', function ($model) {
                return $model->currency->code . ' ' . number_format(round($model->amount, 2), 2);
            })
            ->editColumn('date_time', function ($model) {

                $html = '<span style="font-size: 14px;">Created At: '. get_system_date($model->created_at) . ' ' . get_system_time($model->created_at). '</span>';

                if($model->created_at != $model->updated_at) {
                    $html .= '<br> <span style="font-size: 14px;"> Updated At: '. get_system_date($model->updated_at) . ' ' . get_system_time($model->updated_at) . '</span>';
                    if(isset($model->admin)) {
                        $html .= '<br><span style="font-size:14px;">Updated By: '. $model->admin->name  .'</span>';
                    }
                }

                return $html;
            })
            // ->editColumn('updated_at', function ($model) {

            //     return get_system_date($model->updated_at) . ' ' . get_system_time($model->updated_at);
            // })
            // ->editColumn('updated_by', function ($model) {

            //     return ;
            // })
            ->editColumn('installment', function ($model) {

                return isset($model->installmentPlan) ? $model->installmentPlan->name . ' (' . $model->installmentPlan->length . ' Months)' : 'N/A';
            })
            ->addColumn('customer', function ($model) {
                $html = '<b>'. $model->user->name . '</b>';

                if($model->user) {
                    $html .= '<br><a style="color:#000;" title="Send Email" href="mailto:'. $model->user->email .'" target="_blank">'. $model->user->email . '</a>';
                }

                if(isset($model->user->phones) && count($model->user->phones) > 0) {
                    $phone = $model->user->phones->first();
                    if($phone) {
                        $html .= '<br><a style="color:#000;" title="Call Us" href="tel:'. $phone->phone_number .'">'. $phone->phone_number . '</a>';
                    }
                }

                return $html;
            })
            ->addColumn('documents', function ($model) {
                $links = '';
                if ($model->document) {
                    $links .= "<a target='_blank' href='" . asset($model->document) . "'>
                              <i class='fas fa-file'></i>
                           </a> ";
                }
                if ($model->document_2) {
                    $links .= "<a target='_blank' href='" . asset($model->document_2) . "'>
                              <i class='fas fa-file'></i>
                           </a> ";
                }
                if (!empty($model->additional_documents)) {
                    foreach (json_decode($model->additional_documents) as $doc) {
                        $links .= "<a target='_blank' href='" . asset($doc) . "'>
                                  <i class='fas fa-file'></i>
                               </a> ";
                    }
                }
                return $links ?: 'N/A';
            })
            ->addColumn('action', function ($model) {
                return view('backend.balance-request.action', compact('model'));
            })
            ->rawColumns(['action', 'updated_by', 'status', 'customer', 'date_time', 'installment', 'amount', 'documents'])
            ->make(true);
    }

    public function myNegativeBalance()
    {
        return NegativeBalanceRequest::where('user_id', Auth::guard('customer')->id())
            ->with('currency:id,code,symbol', 'installmentPlan')
            ->latest()
            ->paginate(5);
    }

    public function negativeBalanceStore($request, $userId = null)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'installment_plan_id' => 'required|exists:installment_plans,id',
            'document' => 'required|file|max:512|mimes:jpg,jpeg,png,pdf,doc,docx',
            'document_2' => 'nullable|file|max:512|mimes:jpg,jpeg,png,pdf,doc,docx',
            'document_3.*' => 'nullable|file|max:512|mimes:jpg,jpeg,png,pdf,doc,docx',
            'description' => 'required|string|max:1000',
            'currency_id' => 'required|exists:currencies,id',
        ]);

        try {

            if(!$userId) {
                $userId = Auth::guard('customer')->user()->id;
            }

            DB::beginTransaction();
            $document3Paths = [];
            if ($request->hasFile('document_3')) {
                foreach ($request->file('document_3') as $file) {
                    $document3Paths[] = Images::upload('balanceRequests', $file, 'documents');
                }
            }
            NegativeBalanceRequest::create([
                'amount' => $validatedData['amount'],
                'user_id' => $userId,
                'installment_plan_id' => $validatedData['installment_plan_id'],
                'document' => $request->hasFile('document') ? Images::upload('balanceRequests', $request->document, 'documents') : null,
                'document_2' => $request->hasFile('document_2') ? Images::upload('balanceRequests', $request->document_2, 'documents') : null,
                'document_3' => json_encode($document3Paths),
                'description' => $validatedData['description'],
                'currency_id' => $validatedData['currency_id'],
                'is_approved' => false,
                'is_declined' => false,
            ]);

            Notification::create([
                'user_id' => Auth::guard('customer')->id(),
                'message' => 'Negative Balance Requested by: ' . ucwords( Auth::guard('customer')->check() ? Auth::guard('customer')->user()->name : 'Mobile APP'),
                'go_to_link' => route('admin.balance.request'),
            ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Negative Balance Request Created successfully.']);

        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);

        }


    }

    public function requestUpdate($request, $id)
    {
        $balanceREQ = NegativeBalanceRequest::find($id);
        if (!$balanceREQ) {
            return response()->json(['status' => false, 'message' => 'Negative Balance Request Not Found.']);

        }

        if (isset($request->status) && $request->status === "declined") {
            $balanceREQ->update([
                'is_declined' => true,
                'admin_id' => Auth::guard('admin')->id(),
            ]);

            // Dispatch the job to send the email
            SendRequestDenyNotification::dispatch($balanceREQ->user, $balanceREQ, $request->description)->onQueue('low');
            return response()->json(['status' => true, 'load' => true, 'message' => 'Negative Balance Request Declined.']);
        }
        if (isset($request->status) && $request->status === "approved") {
            $balanceREQ->update([
                'is_approved' => true,
                'is_declined' => false,
                'admin_id' => Auth::guard('admin')->id(),
            ]);

            $installmentCount = $balanceREQ->installmentPlan->length;
            $nextPayout = Carbon::parse($request->installment_start);

            $initialAmountPerMonth = $balanceREQ->amount / $installmentCount;
            $extraAmountPerMonth = ($initialAmountPerMonth * $balanceREQ->installmentPlan->extra_charge_percent) / 100;
            $finalAmountPerMonth = $initialAmountPerMonth + $extraAmountPerMonth;

            $installments = collect(range(1, $installmentCount))->map(function ($installmentNumber) use ($balanceREQ, $nextPayout, $initialAmountPerMonth, $extraAmountPerMonth, $finalAmountPerMonth) {
                return [
                    'installment_number' => $installmentNumber,
                    'payment_date' => $nextPayout->copy()->addMonths($installmentNumber - 1),
                    'initial_amount' => $initialAmountPerMonth,
                    'extra_amount' => $extraAmountPerMonth,
                    'final_amount' => $finalAmountPerMonth,
                    'user_id' => $balanceREQ->user_id,
                    'negative_balance_request_id' => $balanceREQ->id,
                    'is_paid' => false,
                    'currency_id' => $balanceREQ->currency_id,
                ];
            });

            try {

                DB::beginTransaction();

                UserInstallment::insert($installments->toArray());

                $wallet = UserNegetiveBalanceWallet::where('user_id', $balanceREQ->user_id)->where('currency_id', $balanceREQ->currency_id)->first();
                if (!$wallet) {
                    UserNegetiveBalanceWallet::create([
                        'currency_id' => $balanceREQ->currency_id,
                        'user_id' => $balanceREQ->user_id,
                        'current_balance' => $balanceREQ->amount,
                    ]);
                } else {
                    $wallet->update([
                        'current_balance' => $wallet->current_balance + $balanceREQ->amount,
                    ]);
                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => $exception->getMessage()]);
            }

            // Dispatch the job to send the email
            SendRequestApprovalNotification::dispatch($balanceREQ->user, $balanceREQ, $request->description, $installments)->onQueue('high');
            return response()->json(['status' => true, 'load' => true, 'message' => 'Request Approved and Installments Scheduled Successfully.']);
        }

        return response()->json(['status' => false, 'message' => 'Something went Wrong']);
    }
}
