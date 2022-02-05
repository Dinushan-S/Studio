<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Location;
use DateTime;
use Carbon\Carbon;
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get bookings where is_deleted false
        $bookings = Booking::where('is_deleted', false)->get();
        if(count($bookings)>0){
            foreach ($bookings as $booking) {
                 $booking->customer=Customer::where('id',$booking->customer_id)->where('is_deleted',false)->get()->first();
                 $booking->package=Package::where('id',$booking->package_id)->where('is_deleted',false)->get()->first();
                 $booking->category=Category::where('id',$booking->package->category_id)->where('is_deleted',false)->first();
                 $booking->bookingType=Category::where('id',$booking->category->parent_id)->where('is_deleted',false)->first();
                 $booking->locations=Location::where('booking_id',$booking->id)->where('is_deleted',false)->get();
            }
        }
        return response($bookings, 201);
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
        if($request){
            $data=$request->all();
            $data['locations']=json_decode($data['locations'],true);
            //return $data['locations'];

        }
        
        $booking['name']=$request->bookingName;
        $booking['customer_id']=$request->customer;
        $booking['package_id']=$request->package;
        $booking['additional_notes']=$request->notes;
        $booking['status']=$request->status;
        $booking['reference_id']=$request->reference_id ? $request->reference_id : null;
        $booking['start_date']=$request->startDate ;
        $booking['end_date']=$request->endDate ? $request->endDate : $request->startDate ;
        $booking['is_deleted']=false;        
        
        $booking=Booking::create($booking);
        if(count($data['locations'])>0){
            foreach($data['locations'] as $location){
                $location['booking_id']=$booking->id;
                Location::create($location);
            }
        }
        $bookings=Booking::all();
        if(count($bookings)>0){
            foreach ($bookings as $booking) {
                 $booking->customer=Customer::find($booking->customer_id);
                 $booking->package=Package::find($booking->package_id);
                 $booking->category=Category::find($booking->package->category_id);
                 $booking->bookingType=Category::find($booking->category->parent_id);
                 $booking->locations=Location::where('booking_id',$booking->id)->get();
            }
        }
        return response($bookings, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //return $booking; 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
       // return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }

    public function patchBooking(Request $request){
        
       if($request){
        
        $booking['id']=$request->id;
        $booking['name']=$request->name;
        $booking['customer_id']=$request->customer_id;
        $booking['package_id']=$request->package_id;
        $booking['additional_notes']=$request->additional_notes;
        $booking['status']=$request->status;
        $booking['reference_id']=$request->reference_id ? $request->reference_id : null;
        $booking['start_date']=Carbon::parse(new Carbon($request->start_date))->format('Y-m-d h:i:s'); 
        $booking['end_date']=Carbon::parse(new Carbon($request->end_date))->format('Y-m-d h:i:s'); 
        $booking['is_deleted']=false;        
        // return $request['locations'];
        Booking::where('id', $booking['id'])->update($booking);
        if(count($request['locations'])>0){
            foreach($request['locations'] as $location){
                if(isset($location['id'])){
                    $found=Location::where('booking_id',$booking['id'])->where('id',$location['id'])->first();
                    if($found){
                        $loc['latitude']=$location['latitude'];
                        $loc['longitude']=$location['longitude'];
                        $loc['is_deleted']=$location['is_deleted'];
                        // return $location;
                        Location::where('id',$location['id'])->update($loc);
                    }
                    
                }else{
                    $location['booking_id']=$booking['id'];
                    Location::create($location);
                }
            }
        }
        return response()->json([
            'message'=>'Booking Updated successfully',
            'booking'=>Booking::find($request->id),
            'locations'=>Location::where('booking_id',$booking['id'])->get()
        ],201);
        }
    }

    public function categoryPackage(Request $request){
        $categories=Category::where('is_deleted',false)->get();
        $packages=Package::where('is_deleted',false)->get();
        $customer=Customer::where('is_deleted',false)->get();
        return response()->json([
            'EventType'=>$categories,
            'EventPackages'=>$packages,
            'Customers'=>$customer

        ]);
    }


    public function deleteBooking(Request $request){
        $booking=Booking::find($request->id);
        $booking->is_deleted=true;
        $booking->save();
        return response()->json([
            'message'=>'Booking Deleted successfully',
            'booking'=>$booking
        ],201);
    }

    public function statusUpdate(Request $request){
        $booking=Booking::find($request->id);
        $booking->status=$request->status;
        $booking->save();

        $bookings = Booking::where('is_deleted', false)->get();
        if(count($bookings)>0){
            foreach ($bookings as $booking) {
                 $booking->customer=Customer::where('id',$booking->customer_id)->where('is_deleted',false)->get()->first();
                 $booking->package=Package::where('id',$booking->package_id)->where('is_deleted',false)->get()->first();
                 $booking->category=Category::where('id',$booking->package->category_id)->where('is_deleted',false)->first();
                 $booking->bookingType=Category::where('id',$booking->category->parent_id)->where('is_deleted',false)->first();
                 $booking->locations=Location::where('booking_id',$booking->id)->where('is_deleted',false)->get();
            }
        }
        return response($bookings, 201);
        return response()->json([
            'message'=>'Booking Status Updated successfully',
            'bookings'=>$bookings
        ],201);

    }




}
