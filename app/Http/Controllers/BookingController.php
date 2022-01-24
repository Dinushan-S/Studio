<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Package;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'test';
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
        //
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
        //
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
        //
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

    public function categoryPackage(Request $request){
        $categories=Category::all();
        $packages=Package::all();
        $categoryPackage=[];
        $categoryPackageFilter=[];
        //get all categories and packages for each catergory

        $parentCategories=[];
        $parentCategories=$categories->where('parent_id',null);
        $childCategories=[];
        $childCategories=$categories->where('parent_id','!=',null);

        foreach($childCategories as $category){
            $categoryPackage[$category->id]=[
                'category'=>$category,
                'packages'=>$packages->where('category_id',$category->id)
            ];
        }

        foreach($parentCategories as $category){
            $category['child']=$childCategories->where('parent_id',$category->id);
        }
        return response($parentCategories, 201);
    }




}
