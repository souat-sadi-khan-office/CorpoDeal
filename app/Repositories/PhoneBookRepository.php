<?php

namespace App\Repositories;

use DataTables;
use App\Models\UserPhone;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\PhoneBookRepositoryInterface;

class PhoneBookRepository implements PhoneBookRepositoryInterface
{
    public function getAll()
    {
        return UserPhone::all();
    }
    
    public function getAllByUser()
    {
        return UserPhone::where('user_id', Auth::guard('customer')->user()->id)->orderBy('id', 'DESC')->get();
    }

    public function findModelById($id)
    {
        return UserPhone::findOrFail($id);
    }

    public function createModel(array $data)
    {
        if ($data['is_default'] == 1) {
            UserPhone::where('user_id', Auth::guard('customer')->user()->id)->update(['is_default' => 0]);
        }

        return UserPhone::create([
            'user_id' => Auth::guard('customer')->user()->id,
            'phone_number' => $data['phone_number'],
            'is_default' => $data['is_default']
        ]);
    }

    public function updateModel($id, array $data)
    {
        if ($data['is_default'] == 1) {
            UserPhone::where('user_id', Auth::guard('customer')->user()->id)->update(['is_default' => 0]);
        }
        
        $model = UserPhone::findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function deleteModel($id)
    {
        $model = UserPhone::findOrFail($id);
        return $model->delete();
    }
}