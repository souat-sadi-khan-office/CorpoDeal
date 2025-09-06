<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FlashDeal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class FlashDealApiController extends Controller
{
    public function getBySlug(string $slug): JsonResponse
    {
        $model = FlashDeal::where('status', 1)->where('slug', $slug)->first();

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Flash Deal not found'
            ], 404);
        }

        $starting_time = $model->starting_time;
        $deadline_time = $model->deadline_time;
        $deadline_type = $model->deadline_type;

        $deadline = Carbon::parse($starting_time)->add($deadline_type, $deadline_time);
        $now = Carbon::now();

        $time_difference = $deadline->diffInSeconds($now, false);

        $isCrossedDeadline = $time_difference <= 0;

        $days = $isCrossedDeadline ? 0 : $deadline->diffInDays($now);
        $hours = $isCrossedDeadline ? 0 : $deadline->copy()->subDays($days)->diffInHours($now);
        $minutes = $isCrossedDeadline ? 0 : $deadline->copy()->subDays($days)->subHours($hours)->diffInMinutes($now);
        $seconds = $isCrossedDeadline ? 0 : $deadline->copy()->subDays($days)->subHours($hours)->subMinutes($minutes)->diffInSeconds($now);

        // Flash deal related products
        $products = app('App\Repositories\ProductRepository')->flashDealProduct($model->id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $model->id,
                'title' => $model->title,
                'slug' => $model->slug,
                'banner' => $model->banner,
                'start_time' => $starting_time,
                'deadline_time' => $deadline->toDateTimeString(),
                'isCrossedDeadline' => $isCrossedDeadline,
                'remaining' => [
                    'days' => $days,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                ],
                'products' => $products // Should be formatted via resource if needed
            ]
        ]);
    }
}
