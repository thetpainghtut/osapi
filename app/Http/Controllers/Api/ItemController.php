<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{
    // public function __construct($value='')
    // {
    //     $this->middleware('auth');
    // }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();

        return response()->json([
            'status' => 'ok',
            'totalResults' => count($items),
            'items' => ItemResource::collection($items)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);

        //validation
        $request->validate([
            'codeno' => 'required|min:4',
            'name' => 'required|unique:items',
            'photo' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'description' => 'required',
            'brand' => 'required',
            'subcategory' => 'required'
        ]);

        //if include file, upload
        $imageName = time().'.'.$request->photo->extension();

        $request->photo->move(public_path('backend/itemimg'),$imageName);
        $myfile = 'backend/itemimg/'.$imageName;

        //data insert
        $item = new Item;
        $item->codeno = $request->codeno;
        $item->name = $request->name;
        $item->photo = $myfile;
        $item->price = $request->price;
        $item->discount = $request->discount;
        $item->description = $request->description;
        $item->brand_id = $request->brand;
        $item->subcategory_id = $request->subcategory;
        $item->save();

        //redirect
        return (new ItemResource($item))
                    ->response()
                    ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }

    /**
     * Filtering the resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $q = $request->q;

        $items = Item::where('name','LIKE',"%{$q}%")->get();
        // For Case Sensitive (collation => utf8_bin) 
        
        return response()->json([
            'status' => 'ok',
            'totalResults' => count($items),
            'items' => ItemResource::collection($items)
        ]);
    }

    /**
     * Filtering the resource from storage by brand.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function byBrand(Request $request)
    {
        $bid = $request->brand;

        $items = Item::where('brand_id',$bid)->get();
        // For Case Sensitive (collation => utf8_bin) 
        
        return response()->json([
            'status' => 'ok',
            'totalResults' => count($items),
            'items' => ItemResource::collection($items)
        ]);
    }

    /**
     * Filtering the resource from storage by subcategory.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function bySubcategory(Request $request)
    {
        $sid = $request->subcategory;

        $items = Item::where('subcategory_id',$sid)->get();
        // For Case Sensitive (collation => utf8_bin)
        
        return response()->json([
            'status' => 'ok',
            'totalResults' => count($items),
            'items' => ItemResource::collection($items)
        ]);
    }
}
