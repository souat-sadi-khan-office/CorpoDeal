<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

trait IstiyakTraitLog
{
    /**
     * Boot the activity logging trait for model events.
     */
    public static function bootIstiyakTraitLog()
    {
        static::created(function ($model) {
            $model->logActivity('create');
        });

        static::updated(function ($model) {
            $model->logActivity('update');
        });

        static::deleted(function ($model) {
            $model->logActivity('delete');
        });
    }

    /**
     * Log the activity for the model based on the action.
     *
     * @param string $action The action that was performed (create, update, delete)
     */
    public function logActivity($action)
    {
        $userName = '';
        $userId = null;
        $adminId = null;

        // Determine the acting user/admin
        if ($this->isAdminRoute()) {
            if (Auth::guard('admin')->check()) {
                $userName = Auth::guard('admin')->user()->name;
                $adminId = Auth::guard('admin')->id();
            }
        } elseif (Auth::guard('customer')->check()) {
            $userName = Auth::guard('customer')->user()->name;
            $userId = Auth::guard('customer')->id();
        }

        if (!$userName) {
            return;
        }
        $modal = class_basename($this);

        $changes = '';

        // Handle create action
        if ($action === 'create') {
            if ($modal === "Order") {
                $changes = " by {$userName} (ID: {$this->unique_id})";

            }else{
                $changes = " by {$userName} (ID: {$this->id})";

            }
        }

        // Handle update action
        if ($action === 'update' && $this->isDirty()) {
            $dirty = $this->getDirty();

            $filteredDirty = array_filter($dirty, fn($key) => $key !== 'updated_at', ARRAY_FILTER_USE_KEY);

            $totalChanges = count($filteredDirty);
            $limitedDirty = $totalChanges > 7 ? array_slice($filteredDirty, 0, 7) : $filteredDirty;

            $changes = " by {$userName}: " . implode(', ', array_map(
                    fn($key) => "$key: " . $this->getOriginal($key) . " -> " . $this->{$key},
                    array_keys($limitedDirty)
                ));

            if ($modal === "Currency") {
                $changes =implode(', ', array_map(
                    fn($key) => "$key: " . $this->getOriginal($key) . " -> " . $this->{$key},
                    array_keys($limitedDirty))). ' ==> ' . $this->code;
                $userId=null;
            }


            if ($totalChanges > 7) {
                $changes .= '...';
            }
        }
        // Handle delete action
        if ($action === 'delete') {
            $attributes = json_encode(array_slice($this->attributes, 0, 7), JSON_PRETTY_PRINT);
            $changes = " by {$userName}: {$attributes}";
        }

        // Determine activity type
        $types = ['default', 'admin', 'area', 'banner', 'brand', 'cart', 'category', 'city', 'country', 'coupon',
            'currency', 'notice', 'offer', 'order', 'page', 'payment', 'product', 'productquestion', 'productquestionanswer',
            'productspecification', 'productstock', 'producttax', 'promocode', 'promocodeusage', 'rating', 'refundrequest',
            'refundtransaction', 'reviewanswer', 'search', 'specificationkey', 'specificationkeytype', 'specificationkeytypeattribute',
            'stockpurchase', 'subscriber', 'supportticket', 'supportticketreply', 'tax', 'user', 'useraddress', 'usercoupon', 'userphone',
            'userpoint', 'userwallet', 'wallettopup', 'wallettransaction', 'wishlist', 'zone', 'system'];

        $classname = in_array(strtolower(class_basename($this)), $types) ? class_basename($this) : 'default';
        // Create activity log
        ActivityLog::create([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'activity_id' => $this->id ?? null,
            'activity_type' => strtolower($classname),
            'activity' => ucfirst($action) . "d {$modal}: {$changes}",
            'action' => $action,
        ]);
    }

    /**
     * Determine if the current route belongs to the admin prefix.
     *
     * @return bool
     */
    protected function isAdminRoute()
    {
        $currentRoute = Route::currentRouteName();

        return $currentRoute && str_starts_with($currentRoute, 'admin.');
    }
}
