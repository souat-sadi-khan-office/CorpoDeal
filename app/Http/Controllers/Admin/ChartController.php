<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChartController extends Controller
{

    private function getRangeQuery($query, $range)
    {
        switch ($range) {
            case 'daily':
                return $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->limit(10);

            case 'weekly':
                return $query->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, COUNT(*) as count')
                    ->groupBy('year', 'week')
                    ->orderBy('year', 'desc')
                    ->orderBy('week', 'ASC')
                    ->limit(10);

            case 'monthly':
                return $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'ASC')
                    ->limit(10);

            case 'yearly':
                return $query->selectRaw('YEAR(created_at) as year, COUNT(*) as count')
                    ->groupBy('year')
                    ->orderBy('year', 'ASC')
                    ->limit(10);

            default:
                return collect([]);
        }
    }


    public function userdata(Request $request)
    {
        $range = $request->get('range', 'daily');
        $cacheKey = "userdata_{$range}";

        $data = Cache::remember($cacheKey, now()->addDay(), function () use ($range) {
            $query = User::query();

            return $this->getRangeQuery($query, $range)->get();
        });

        $mappedData = $this->dateMapper($data, $range);

        $labels = $mappedData->pluck('label');
        $values = $mappedData->pluck('value');

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function userStatus()
    {
        list($activeCount, $inactiveCount) = Cache::remember('chart_user_count', now()->addDay(), function () {

            return [User::where('status', 1)->count(), User::where('status', 0)->count()];
        });
        return response()->json([
            'labels' => ['Active', 'Inactive'],
            'values' => [$activeCount, $inactiveCount],
        ]);
    }

    public function orderData(Request $request)
    {
        $range = $request->get('range', 'daily');
        $status = $request->get('status', 'pending');

        $cacheKey = "orderData_{$range}_{$status}";

        $data = Cache::remember($cacheKey, now()->addDay(), function () use ($range, $status) {
            $query = Order::query();

            if ($status) {
                $query->where('status', $status);
            }
            return $this->getRangeQuery($query, $range)->get();

        });

        $mappedData = $this->dateMapper($data, $range);

        $labels = $mappedData->pluck('label');
        $values = $mappedData->pluck('value');

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    private function dateMapper($data, $range)
    {
        return $data->map(function ($row) use ($range) {
            return [
                'label' => match ($range) {
                    'daily' => get_system_date($row->date),
                    'weekly' => "Week {$row->week}, {$row->year}",
                    'monthly' => (new DateTime("{$row->year}-{$row->month}-01"))->format('M') . "-{$row->year}",
                    'yearly' => $row->year,
                },
                'value' => $row->count,
            ];
        });
    }

}
