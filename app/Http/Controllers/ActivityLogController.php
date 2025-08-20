<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use function Carbon\int;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('activity-log.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $types = ['default', 'admin', 'area', 'banner', 'brand', 'cart', 'category', 'city', 'country', 'coupon',
            'currency', 'notice', 'offer', 'order', 'page', 'payment', 'product', 'productquestion', 'productquestionanswer',
            'productspecification', 'productstock', 'producttax', 'promocode', 'promocodeusage', 'rating', 'refundrequest',
            'refundtransaction', 'reviewanswer', 'search', 'specificationkey', 'specificationkeytype', 'specificationkeytypeattribute',
            'stockpurchase', 'subscriber', 'supportticket', 'supportticketreply', 'tax', 'user', 'useraddress', 'usercoupon', 'userphone',
            'userpoint', 'userwallet', 'wallettopup', 'wallettransaction', 'wishlist', 'zone', 'system'
        ];

        $filters = $this->filters($request);
        $data = ActivityLog::with(['user:id,name', 'admin:id,name'])
            ->when($request->has('find') && $request->find !== null, function ($query) use ($request) {
                $searchTerm = $request->find;
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereHas('user', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })->orWhereHas('admin', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })->orWhere('activity', 'like', '%' . $searchTerm . '%');
                });
            })
            ->when(isset($filters['activity_type']), fn($query) => $query->where('activity_type', $filters['activity_type']))
            ->when(isset($filters['action']), fn($query) => $query->where('action', $filters['action']));

        $this->applyDateFilters($data, $filters);

        $recordsTotal = ActivityLog::count();

        $recordsFiltered = $data->count();

        $activitylogs = $data->latest()->get();


        if ($request->ajax()) {

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user', function ($log) {
                    return $log->user_id ? 'User - ' . $log->user->name : ($log->admin_id ? 'Admin - ' . $log->admin->name : 'System');
                })
                ->editColumn('type', function ($log) {
                    return ucwords($log->activity_type);
                })
                ->editColumn('activity', function ($log) {
                    return add_line_breaks($log->activity, 8);
                })
                ->editColumn('created_at', function ($log) {
                    return $log->created_at->format('Y-m-d H:i:s');
                })
                ->editColumn('status', function ($log) {
                    return ucwords($log->action);
                })
                ->rawColumns(['user', 'type', 'activity', 'created_at', 'status']) // Specify columns with raw HTML
                ->make(true);
        }

        $count = $data->count();
        return view('backend.activity-log.index', compact('count', 'types'));
    }

    public function show($id)
    {
        $report = ActivityLog::find($id);

    }

    private function filters($request)
    {
        return [
            'activity_type' => $request->activity_type ?? null,
            'action' => $request->action ?? null,
            'date_range' => [
                'from' => $request->from ?? null,
                'to' => $request->to ?? null,
            ],
            'between' => $request->between ?? null,
        ];
    }

    private function applyDateFilters($query, $filters)
    {
        if (isset($filters['date_range']) && !empty($filters['date_range']['from']) && !empty($filters['date_range']['to'])) {
            $query->whereBetween('created_at', [$filters['date_range']['from'], $filters['date_range']['to']]);
        } elseif (isset($filters['between']) && !isset($filters['date_range']['from'])) {
            switch ($filters['between']) {
                case 'last_day':
                    $query->whereDate('created_at', Carbon::now()->subDay()->format('Y-m-d'));
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [
                        Carbon::now()->subWeek()->startOfDay()->format('Y-m-d'),
                        Carbon::now()->endOfDay()->format('Y-m-d')
                    ]);
                    break;
                case 'last_month':
                    $query->whereBetween('created_at', [
                        Carbon::now()->subMonth()->startOfDay()->format('Y-m-d'),
                        Carbon::now()->endOfDay()->format('Y-m-d')
                    ]);
                    break;
                case 'last_year':
                    $query->whereBetween('created_at', [
                        Carbon::now()->subYear()->startOfDay()->format('Y-m-d'),
                        Carbon::now()->endOfDay()->format('Y-m-d')
                    ]);
                    break;
            }
        }
    }

}
