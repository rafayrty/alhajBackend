<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\Vehicles;
use App\Models\Orders;

class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Vehicles::all();
        //
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

        $this->validate($request,[
          'name'=>'required',
        ]);
        if($request->file && $request->format){
        $png_url = "vehicle-".time().".".$request->format;
        $path = public_path('vehicles/' . $png_url);
    
        \Image::make(file_get_contents("data:image/png;base64,".$request->file))->save($path);     
        $response = array(
            'status' => 'success',
        );
        $image = \URL::to('/')."/vehicles/".$png_url;
    }else{
            $image = "https://ui-avatars.com/api/?name=".$request->name;
        }
        $Vehicle = Vehicles::create([
            'name'=>$request->name,
            'image'=>$image,
            'status'=>"Available"
        ]);

        return response()->json($Vehicle);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     return   $Vehicle = Vehicles::findOrFail($id);

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return   $Vehicle = Vehicles::findOrFail($id);



        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>"required",
          ]);
          if($request->file && $request->format){
            $png_url = "pilot-".time().".".$request->format;
            $path = public_path('pilots/' . $png_url);
        
            \Image::make(file_get_contents("data:image/png;base64,".$request->file))->save($path);     
            $response = array(
                'status' => 'success',
            );
            $image = \URL::to('/')."/pilots/".$png_url;
            $Vehicle = Vehicles::findOrFail($id)->update([
                'name'=>$request->name,
                'image'=>$image,
            ]);
          }else{
            $Vehicle = Vehicles::findOrFail($id)->update([
                'name'=>$request->name,
            ]); 
          }
       
          return Vehicles::findOrFail($id);

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Vehicle = Vehicles::findOrFail($id);
        
        $order = Orders::whereHas('vehicles',function($q) use($id) {
            $q->where('id', $id);
        })->delete();  
        $Vehicle->delete();
        return Vehicles::all();
        //
    }
}
