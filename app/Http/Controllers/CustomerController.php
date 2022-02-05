<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer=Customer::where('is_deleted',false)->get();
        return response($customer, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'contact_mobile'=> 'required|string',
        ]);
        $category = Customer::create([
            'name'=> $fields['name'],
            'contact_mobile' => $request['contact_mobile'],
            'is_deleted'=> false,
        ]);
        return response($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function patchCustomer(Request $request){
        
       if($request){
        $customer=Customer::find($request->id);
        $customer['name']=$request->name;
        $customer['contact_mobile']=$request->contact_mobile;
        $customer['is_deleted']=false;
        $customer->save();
        return response()->json([
            'message'=>'Customer Updated successfully',
            'customer'=>$customer,
            'customers'=>Customer::where('is_deleted',false)->get()  
        ],201);
        
       }
    }

    public function deleteCustomer(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->is_deleted=true;
        $customer->save();
        return response()->json([
            'message'=>'Customer Deleted successfully',
            'customer'=>$customer,
        ],201);
    }
}
