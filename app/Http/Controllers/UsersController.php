<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Orders;
class UsersController extends Controller
{
    /**            $this->fcm($user->name." Update","The Order Has Been Shipped",$order->user_id);

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return User::with('roles')->get();
        //
    }
    public function pilots()
    { 

        $pilots = Role::find(4);

        $users = [];
        foreach ($pilots->users as $user) {
            $users[] = $user;
                        # code...
        }
        return response()->json($users);
//         $drivers = User::whereHas('roles', function($q) use($id)  {
//             $q->whereIn('role_id', $id);
//         })->get();
// dd($drivers);
// return $pilots;
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
    public function fcm(Request $request){
        $user = User::findOrFail($request->user()->id)->update(['fcm'=>$request->token]);

        return $user;
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
          'phone'=>'required|unique:users|numeric|min:8',
          'login_id'=>'required|min:6|max:32|unique:users',
          'role.*'=>'required'
        ]); 
        if($request->file && $request->format){
        
        $png_url = "pilot-".time().".".$request->format;
        $path = public_path('pilots/' . $png_url);
    
        \Image::make(file_get_contents("data:image/png;base64,".$request->file))->save($path);     
        $response = array(
            'status' => 'success',
        );
        $image = \URL::to('/')."/pilots/".$png_url;
    }else{
        $image = "https://ui-avatars.com/api/?name=".$request->name;
    }

    
   $user = User::create([
            'phone'=>$request->phone,
            'name'=>$request->name,
            'login_id'=>$request->login_id,
            'image'=>$image,
            'status'=>"Available"
        ]);
        $addRoles = [];
        foreach ($request->role as $key => $value) {
        
            if($key=='manager' && $value==true){
               $addRoles[] = ['role_id'=>1,'user_id'=>$user->id];
            }
            if($key=='collector' && $value==true){
                $addRoles[] = ['role_id'=>2,'user_id'=>$user->id];
            }
            if($key=='office' && $value==true){
                $addRoles[] = ['role_id'=>3,'user_id'=>$user->id];
            }
            if($key=='driver' && $value==true){
                $addRoles[] = ['role_id'=>4,'user_id'=>$user->id];
            }
            # code...
        }
foreach ($addRoles as $key => $value) {
    \DB::table('role_user')->insert($value);
    # code...
}
                return response()->json($user);

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
 $user = User::with('roles')->where('id',$id)->first();
 return $user;

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
        $user = User::with('roles')->where('id',$id)->first();
return $user;


        //
    }
    public function status(Request $request){
        $status = $request->status;
        $id = $request->user()->id;
        if($request->status=='Available'){
            $order =   Orders::whereHas('drivers',function($q) use($id) {
                $q->where('id', $id);
            })->where('status','Waiting')->orWhere('status','On The Way')->count();

            if($order > 0){
           $user = User::findOrFail($request->user()->id)->update(['status'=>$status]);      
               $status  = "Out For Delivery";
            }else{
       $user = User::findOrFail($request->user()->id)->update(['status'=>$status]);
            }
            
        }elseif($request->status == 'Logged Out'){
             $user = User::findOrFail($request->user()->id)->update(['status'=>$status,'fcm'=>'']);
        }else{
             $user = User::findOrFail($request->user()->id)->update(['status'=>$status]);
        }
       
                
        return User::findOrFail($request->user()->id);

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // if($request->role == 'Manager'){
        //            $this->validate($request,[
        //     'login_id'=>($request->login_id != User::findOrFail($id)->login_id ? 'required|min:6|max:32|unique:users' : ''),
        //     'role.*'=>"required"
        //   ]); 
       
       
       
        // }elseif($request->role == 'Pilot'){
        //                 $this->validate($request,[
        //     'phone'=>''.($request->phone != User::findOrFail($id)->phone ? 'required|numeric|min:8|unique:users' : '').'',
        //     'login_id'=>($request->login_id != User::findOrFail($id)->login_id ? 'required|min:6|max:32|unique:users' : ''),
        //     'name'=>"required",
        //     'role.*'=>"required"
        //   ]); 
        // }else{
                        $this->validate($request,[
            'phone'=>''.($request->phone != User::findOrFail($id)->phone ? 'required|numeric|min:8|unique:users' : '').'',
            'login_id'=>($request->login_id != User::findOrFail($id)->login_id ? 'required|min:6|max:32|unique:users' : ''),
            'name'=>"required",
            'role.*'=>"required"
          ]); 
        // }

          if($request->file && $request->format){
            $png_url = "pilot-".time().".".$request->format;
            $path = public_path('pilots/' . $png_url);
        
            \Image::make(file_get_contents("data:image/png;base64,".$request->file))->save($path);     
            $response = array(
                'status' => 'success',
            );
            $image = \URL::to('/')."/pilots/".$png_url;
            
            
            
            if($request->role=='Manager'){
            $user = User::findOrFail($id)->update([
                'name'=>$request->login_id,
                'login_id'=>$request->login_id,
                'image'=>$image,
            ]); 
            }else{
                 $user = User::findOrFail($id)->update([
                'phone'=>$request->phone,
                'name'=>$request->name,
                'login_id'=>$request->login_id,
                'image'=>$image,
            ]); 
            }
            

          }else{
              
                if($request->role=='Manager'){
            $user = User::findOrFail($id)->update([
                'name'=>$request->login_id,
                'login_id'=>$request->login_id,
            ]); 
            }else{
                 $user = User::findOrFail($id)->update([
                'phone'=>$request->phone,
                'name'=>$request->name,
                'login_id'=>$request->login_id,
            ]); 
            }
      
          }
          \DB::table('role_user')->where('user_id',$id)->delete();
          $addRoles = [];
        foreach ($request->role as $key => $value) {
        
            if($key=='manager' && $value==true){
               $addRoles[] = ['role_id'=>1,'user_id'=>$id];
            }
            if($key=='collector' && $value==true){
                $addRoles[] = ['role_id'=>2,'user_id'=>$id];
            }
            if($key=='office' && $value==true){
                $addRoles[] = ['role_id'=>3,'user_id'=>$id];
            }
            if($key=='driver' && $value==true){
                $addRoles[] = ['role_id'=>4,'user_id'=>$id];
            }
        }
    
foreach ($addRoles as $key => $value) {
    \DB::table('role_user')->insert($value);
}

          return User::findOrFail($id);

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
        $user = User::findOrFail($id);
        $roles = \DB::table('role_user')->where('user_id',$id)->delete();
        
        $order = Orders::whereHas('drivers',function($q) use($id) {
            $q->where('id', $id);
        })->delete();
        $order = Orders::whereHas('collectors',function($q) use($id) {
            $q->where('id', $id);
        })->delete();  
        $user->delete();
        return User::all();
        //
    }
}
