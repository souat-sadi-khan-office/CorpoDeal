<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use App\Repositories\Interface\PhoneBookRepositoryInterface;

class PhoneBookController extends Controller
{

    private $user;
    private $currency;
    private $phone;

    public function __construct(
        UserRepositoryInterface $user,
        CurrencyRepositoryInterface $currency,
        PhoneBookRepositoryInterface $phone 
    ) {
        $this->user = $user;
        $this->phone = $phone;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = $this->phone->getAllByUser();
        return view('frontend.customer.phone.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend.customer.phone.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'phone_number'        => 'required|string|max:15',
            'is_default'          => 'required'
        ]);

        $this->phone->createModel($data);

        return response()->json([
            'status' => true, 
            'goto' => route('account.phone-book.index'),
            'message' => "New phone number is added successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = $this->phone->findModelById($id);
        return view('frontend.customer.phone.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'phone_number'        => 'required|string|max:15',
            'is_default'          => 'required'
        ]);

        $this->phone->updateModel($id, $data);

        return response()->json([
            'status' => true, 
            'goto' => route('account.phone-book.index'),
            'message' => "Phone book number information updated successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->phone->deleteModel($id);

        return response()->json([
            'status' => true,
            'load' => true,
            'message' => "Phone number is deleted successfully"
        ]);
    }
}