<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class APITokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = ApiToken::all();
            
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('action', function ($model) {
                    return view('backend.api-token.action', compact('model'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.api-token.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.api-token.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ApiToken::create([
            'name' => $request->name,
            'token' => $request->token,
            'abilities' => json_encode(['*'])
        ]);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Token created successfully"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = ApiToken::findOrFail($id);
        return view('backend.api-token.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = ApiToken::findOrFail($id);
        $model->name = $request->name;
        $model->save();

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Token updated successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = ApiToken::findOrFail($id);
        $model->delete();

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Token deleted successfully"
        ]);
    }
}
