<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\User;
use App\Models\Vehicles;
use App\Models\Role;

class OrdersController extends Controller
{

    public function data(){
        $pilots = Role::find(4);

        $users = [];
        foreach ($pilots->users as $user) {
            $users[] = $user;
                        # code...
        }
        $collectors = Role::find(2);

        $usersB = [];
        foreach ($collectors->users as $userB) {
            $usersB[] = $userB;
                        # code...
        }
        $array = [
            'vehicles'=> Vehicles::all(),
            'drivers'=>$users,
            'collectors'=>$usersB
        ];

        return response()->json($array);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$type){
        if($type=='history'){
        if($request->search!="" || $request->search){
       return Orders::with('user','vehicle')->where(function($query){
                return $query
                ->whereNull('date')
                ->whereDate('created_at', '<', date('Y-m-d'))
                ->orWhere('date','<',date('Y-m-d'));
            })->where('client','like','%'.$request->search.'%')->latest()->get();


        }elseif($request->search=="" && $request->date){
            
       return Orders::with('user','vehicle')->where(function($query) use($request){
        //       $date;
        //   if($request->date==date('Y-m-d')){
        //     $date = date('Y-m-d',strtotime("+1 day"));
        //   }else{
        //       $date = $request->date;
        //   }
                return $query
                ->whereNull('date')
                ->whereDate('created_at',$request->date)
                ->orWhere('date',$request->date);
            })->where('client','like','%'.$request->search.'%')->latest()->get();
 
           }elseif($request->search!="" && $request->date){

      return Orders::with('user','vehicle')->where('client','like','%'.$request->search.'%')->where(function($query) use($request){
                return $query
                ->whereNull('date')
                ->whereDate('created_at',$request->date)
                ->orWhere('date',$request->date);
            })->latest()->get();

        }else{
            return Orders::with('user','vehicle')->where('date','<',date('Y-m-d'))->orWhere(function($query){
                return $query
                ->whereNull('date')
                ->whereDate('created_at', '<', date('Y-m-d'));
            })->latest()->paginate(8);

        }
    }elseif($type=='today'){
        if($request->search!="" || $request->search){
            return Orders::with('user','vehicle')->where('client','like','%'.$request->search.'%')->where(function($query){
                return $query
                ->whereNull('date')
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->orWhere('date',date('Y-m-d'));
            })->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();

        }else{
            return Orders::with('user','vehicle')->where(function($query){
                return $query
                ->whereNull('date')
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->orWhere('date',date('Y-m-d'));
            })->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->paginate(8);

        }
    
    
    }elseif($type=='tomorrow'){
        if($request->search!="" || $request->search){
                       return Orders::with('user','vehicle')->where('date','>', date("Y-m-d", strtotime("+1 day")))->where('client','like','%'.$request->search.'%')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();


        }else{
            return Orders::with('user','vehicle')->where('date', date("Y-m-d", strtotime("+1 day")))->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->paginate(8);

        }
    }elseif($type=='upcoming'){
        
         if($request->search!="" || $request->search){
return Orders::with('user','vehicle')->where('client','like','%'.$request->search.'%')->where('date','>', date("Y-m-d", strtotime("+1 day")))->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();



        }elseif($request->search=="" && $request->date){
            return Orders::with('user','vehicle')->where('date', $request->date)->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();

        }elseif($request->search!="" && $request->date){

      return Orders::with('user','vehicle')->where('client','like','%'.$request->search.'%')->where('date', $request->date)->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();

        }else{
            return Orders::with('user','vehicle')->where('date','>', date("Y-m-d", strtotime("+1 day")))->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->paginate(8);

        }
        
        
        
        
        
        
        
        
        // if($request->search!="" || $request->search){
        //             return Orders::with('user','vehicle')->where('client','like','%'.$request->search.'%')->where('date','>', date("Y-m-d", strtotime("+1 day")))->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();



        // }else{
        //     return Orders::with('user','vehicle')->where('date','>', date("Y-m-d", strtotime("+1 day")))->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->paginate(8);

        // }
    }
        //
    }


    /**
     * Display a listing of the resource For Officer.
     *
     * @return \Illuminate\Http\Response
     */
    public function office(Request $request,$type){
        if($type=='all'){
        if($request->search!="" || $request->search){
       return Orders::where('client','like','%'.$request->search.'%')->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();


        }elseif($request->search=="" && $request->date){
            
       return Orders::whereDate('created_at',$request->date)->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->whereNull('date')->orWhere('date',$request->date)->where('client','like','%'.$request->search.'%')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();
 
           }elseif($request->search!="" && $request->date){

      return Orders::where('client','like','%'.$request->search.'%')->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->whereNull('date')->whereDate('created_at',$request->date)->orWhere('date',$request->date)->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();

        }else{
            return Orders::where('status','!=','UnPrepared')->where('status','!=','Scheduled')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->paginate(8);

        }
    }elseif($type=='unrecorded'){

        if($request->search!="" || $request->search){
            return Orders::where('client','like','%'.$request->search.'%')->where('receipt',NULL)->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();
        }elseif($request->search=="" && $request->date){
          
            return Orders::whereDate('created_at',$request->date)->where('receipt',NULL)->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->whereNull('date')->orWhere('date',$request->date)->where('client','like','%'.$request->search.'%')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();      
        
        }elseif($request->search!="" && $request->date){

        return Orders::where('client','like','%'.$request->search.'%')->where('receipt',NULL)->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->whereNull('date')->whereDate('created_at',$request->date)->orWhere('date',$request->date)->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();
        
            }else{
                 return Orders::where('status','!=','UnPrepared')->where('receipt',NULL)->where('status','!=','Scheduled')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->paginate(8);
     
        }
    
    
    }elseif($type=='recorded'){
        if($request->search!="" || $request->search){
            return Orders::where('client','like','%'.$request->search.'%')->where('receipt','!=',NULL)->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();
        }elseif($request->search=="" && $request->date){
          
            return Orders::whereDate('created_at',$request->date)->where('receipt','!=',NULL)->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->whereNull('date')->orWhere('date',$request->date)->where('client','like','%'.$request->search.'%')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();      
        
        }elseif($request->search!="" && $request->date){

        return Orders::where('client','like','%'.$request->search.'%')->where('receipt','!=',NULL)->where('status','!=','UnPrepared')->where('status','!=','Scheduled')->whereNull('date')->whereDate('created_at',$request->date)->orWhere('date',$request->date)->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->get();
        
            }else{
        return Orders::where('status','!=','UnPrepared')->where('receipt','!=',NULL)->where('status','!=','Scheduled')->orderByRaw("FIELD(status , 'Waiting', 'Preparing', 'Out For Delivery','Delivered') ASC")->latest()->paginate(8);
        }
    }
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
    public function userCollector($id){
        $order= Orders::where('user_id',$id)->where('status','Waiting')->orWhere('status','Preparing')->orWhere('status','On The Way')->latest()->get();
  
        return $order;
      }
    public function user($id){
      $order= Orders::with('user','vehicle')->where('user_id',$id)->where('orderType','=','normal')->where('status','Waiting')->orWhere('status','Preparing')->orWhere('status','On The Way')->latest()->get();

return $order;
    }
    public function status(Request $request,$id){
        $status = $request->status;
        $response_order = Orders::findOrFail($id);
        if($status=='On The Way'){
            $order = Orders::findOrFail($id)->update(['status'=>$status,'shipped'=>now()]);

$manager = User::where('id',$response_order->assigned_by)->get()->first();
        // $driver = User::findOrFail($response_order->assigned_by);
        //     if($manager->id != $driver->id){
                // $this->fcm(" Update","The Order Has Been Delivered",$manager->id,'Manager',$id);
            // }



        }else if($status=='Delivered'){
            $order = Orders::findOrFail($id)->update(['status'=>$status,'delivered'=>now(),'recipient'=>$request->recipient]);
            $drivers =  Orders::findOrFail($id)->drivers;
            foreach($drivers as $driver){
              User::findOrFail($driver)->update(['status'=>'Available']);
            }
            $vehicles =  Orders::findOrFail($id)->vehicles;
            foreach($vehicles as $vehicle){
               Vehicles::findOrFail($vehicle)->update(['status'=>'Available']);
            }

// $manager = User::where('id',$response_order->assigned_by)->get()->first();
//             $pilot = User::findOrFail($response_order->user_id);
            // if($manager->id != $pilot->id){
                $this->fcm("Update","The Order Has Been Delivered",$manager->id,'Manager',$id);
            // }
        }else if($status=='Preparing'){
            $order = Orders::findOrFail($id)->update(['status'=>$status,'started'=>now()]);
            $drivers =  Orders::findOrFail($id)->drivers;
            foreach($drivers as $driver){
              User::findOrFail($driver->id)->update(['status'=>'Available']);
            }
            $vehicles =  Orders::findOrFail($id)->vehicles;
            foreach($vehicles as $vehicle){
               Vehicles::findOrFail($vehicle->id)->update(['status'=>'Available']);
            }
            $users = Role::find(4);
            $officers = [];
            foreach ($users->users as $officer) {
                $officers[] = $officer;
                            # code...
            }
          $this->fcm("A New Order","A New Order Is Available For Mr/Mrs".Orders::findOrFail($id)->client,$officers,'Officer',$id);
            // $this->fcm($pilot->name." Update","The Order Has Been Delivered",$manager->id,'Manager',$id);

        }
        return Orders::where('status','Waiting')->orWhere('status','Preparing')->orWhere('status','On The Way')->get()->first();
        // return Orders::whereHas('collectors','drivers',function($q) use($id) {
        //     $q->where('id', $id);
        // })->where('status','Waiting')->orWhere('status','Preparing')->orWhere('status','On The Way')->get()->first();
        // return Orders::with('user','vehicle')->where('user_id',$response_order->user_id)->where('status','Waiting')->orWhere('status','Preparing')->orWhere('status','On The Way')->get()->first();

        // return Orders::with('user','vehicle')->where('user_id',$response_order->user_id)->where('status','Preparing')->orWhere('status','On The Way')->get()->first();

    }
    public function proceed(Request $request){
        if($request->image){
            $this->validate($request,[
                'image'=>"required",
                'format'=>"required",
                'phone'=>"required|numeric|min:8",
                'name'=>"required",
                'note'=>"max:255",
                'address'=>"required",
            ]);
        }else{
            $this->validate($request,[
                'phone'=>"required|numeric|min:8",
                'name'=>"required",
                'note'=>"max:255",
                'address'=>"required",
                'lat'=>'required',
                'lng'=>'required'

            ]);
        }
    
    }
public function fcm($heading,$message,$collectors,$role,$order_id){
    $access_token = 'AAAAEU0XO60:APA91bH9MKSRQAzxDgPRidceceSMWuKntLvh3h90HMUqrNYvrHxcbFA6rn6rqwMXZN7ugCnJSAKWHrLJGJXSpL5pxpEmu2wkEFR9a_Vf5TNrHWs2FqpC_WF6QOSZUqebPFw4zQgmqNDL';
// $reg_id = User::findOrFail($id)->fcm;
$order = Orders::findOrFail($order_id);
\Log::info("From FCM",['data'=>json_encode($collectors)]);
if($role=='Collector'){
    $type = 'Scheduled';
}else{
    $type = 'Normal';
}

foreach ($collectors as  $value) {

    if($value->fcm!=null){
$message = [
    'to' => $value->fcm,
    'data'=>array('role'=>$role,'type'=>$type,'heading'=>'Order Update','text'=>"Tintnhue Has On The Way The Order"),
    'notification' => [
        'title' => $heading,
        'body' => $message,
        'priority'=>"high",
        'sound'=>"default"
        ],

];

$client = new \GuzzleHttp\Client([
    'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'key='.$access_token,
    ]
]);

$response = $client->post('https://fcm.googleapis.com/fcm/send',
    ['body' => json_encode($message)]
);
sleep(3);
}
}
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->image && $request->format){
            $this->validate($request,[
                'image'=>"required",
                'format'=>"required",
                'phone'=>"required|numeric|min:8",
                'name'=>"required",
                'address'=>"required",
                'vehicle'=>"required",
                'payment'=>"required",
                "driver"=>"required",
                "collector"=>"required",
                "start_time"=>"required",
                "date"=>"required",
                "end_time"=>"required"
            ]);
        }else{
            $this->validate($request,[
                'phone'=>"required|numeric|min:8",
                'name'=>"required",
                'payment'=>"required",
                'address'=>"required",
                'vehicle'=>"required",
                "driver"=>"required",
                "collector"=>"required",
                "start_time"=>"required",
                "date"=>"required",
                "end_time"=>"required"


            ]);
        }
        $date = null;
        if($request->date){
            $date = substr($request->date,0,10);
        }
        if($request->image && $request->format){
            $png_url = "order-".time().".".$request->format;
            $path = public_path('orders/' . $png_url);
        
            \Image::make(file_get_contents("data:image/png;base64,".$request->image))->save($path,60);     
            $response = array(
                'status' => 'success',
            );
            $image = \URL::to('/')."/orders/".$png_url;
     

           $order = Orders::create([
               'assigned_by'=>$request->user()->id,
               'image'=>$image,
                'shipping'=>$request->address,
                'client'=>$request->name,
                'phone'=>$request->phone,
                'note'=>$request->note,
                'payment'=>$request->payment,
                'date'=>$date ?? null,
                'lat'=>$request->lat,
                'lng'=>$request->lng,
                'receipt'=>$request->receipt,
                'created_by'=>$request->user()->id,
                'start_time'=>$request->start_time,
                'end_time'=>$request->end_time,
                'receipt'=>$request->receipt ?? null,
                'status'=>"Scheduled",
                'details'=>$request->order ?? null,
                'urgent'=>$request->urgent ?? 0,
                'certificate'=>$request->certificate ?? 0

           ]);

          }else{
            $order = Orders::create([
                'assigned_by'=>$request->user()->id,
                'shipping'=>$request->address,
                'client'=>$request->name,
                'phone'=>$request->phone,
                'note'=>$request->note,
                'payment'=>$request->payment,
                'date'=>$date ?? null,
                'lat'=>$request->lat,
                'lng'=>$request->lng,
                'receipt'=>$request->receipt,
                'created_by'=>$request->user()->id,
                'start_time'=>$request->start_time,
                'end_time'=>$request->end_time,
                'receipt'=>$request->receipt ?? null,
                'status'=>"Scheduled",
                'details'=>$request->order ?? null,
                'urgent'=>$request->urgent ?? 0,
                'certificate'=>$request->certificate ?? 0
 
            ]);

          }
        $collectors = [];
        if(!is_array($request->collector)){
            $request->collector = array($request->collector);
        }
        foreach ($request->collector as $key => $value) {
               $collectors[] = ['user_id'=>$value,'orders_id'=>$order->id];       
        }
foreach ($collectors as $key => $value) {
    \DB::table('collector_order')->insert($value);
}

$vehicles = [];
if(!is_array($request->vehicle)){
    $request->vehicle = array($request->vehicle);
}
foreach ($request->vehicle as $key => $value) {
       $vehicles[] = ['vehicles_id'=>$value,'orders_id'=>$order->id];       
}
foreach ($vehicles as $key => $value) {
\DB::table('vehicle_order')->insert($value);
}

$drivers = [];
if(!is_array($request->driver)){
    $request->driver = array($request->driver);
}
foreach ($request->driver as $key => $value) {
       $drivers[] = ['user_id'=>$value,'orders_id'=>$order->id];       
}
foreach ($drivers as $key => $value) {
\DB::table('driver_order')->insert($value);
}
$collectorsNotify =  Orders::find($order->id)->collectors;
// foreach ($collectorsNotify as $key => $value) {
//             $this->fcm("Order Update","There Is A New Upcoming Order Update For Mr/Mrs ".$request->name,$request->pilot,$order->id);

// }
        // if(Orders::findOrFail($order->id)->assigned_by!=$pilot->id){
            $this->fcm("Order Update","There Is A New Upcoming Order For Mr/Mrs ".$request->name,$collectorsNotify,'Collector',$order->id);
        // }
        
        return Orders::all();

        //
    }
    public function upcoming(Request $request){
        // return Orders::with('user','vehicle')->where('user_id',$request->user()->id)->where('status','Waiting')->where('processing',null)->latest()->get();
        return Orders::with('user','vehicle')->where('user_id',$request->user()->id)->where('status','Waiting')->latest()->get();

    }


    public function driver(Request $request){
        // return Orders::with('user','vehicle')->where('user_id',$request->user()->id)->where('status','Waiting')->where('processing',null)->latest()->get();
        $id = $request->user()->id;
        $driver = Orders::whereHas('drivers',function($q) use($id) {
            $q->where('id', $id);
        })->where('status', 'Waiting')->orWhere('status','On The Way')->get();

     

        return $driver;
        // return Orders::where('user_id',$request->user()->id)->where('status','Scheduled')->latest()->get();

    }

    public function collectorOrders(Request $request){
        // return Orders::with('user','vehicle')->where('user_id',$request->user()->id)->where('status','Waiting')->where('processing',null)->latest()->get();
        $id = $request->user()->id;
  
        $collector = Orders::whereHas('collectors',function($q) use($id) {
            $q->where('id', $id);
        })->where('status', 'UnPrepared')->get();

     

        return $collector;
        // return Orders::where('user_id',$request->user()->id)->where('status','Scheduled')->latest()->get();

    }
    public function collector(Request $request){
        // return Orders::with('user','vehicle')->where('user_id',$request->user()->id)->where('status','Waiting')->where('processing',null)->latest()->get();
        $id = $request->user()->id;
        $collector = Orders::whereHas('collectors',function($q) use($id) {
            $q->where('id', $id);
        })->where('status', 'Scheduled')->get();

     

        return $collector;
        // return Orders::where('user_id',$request->user()->id)->where('status','Scheduled')->latest()->get();

    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function officeSave(Request $request,$id)
    {
        $this->validate($request,[
            'receipt'=>"required|max:255",
            'payment'=>"required",
        ]);

        $order = Orders::findOrFail($id);
        $receipt = $order->receipt;
        $order->update(['receipt'=>$request->receipt,'payment'=>$request->payment,'certificate'=>$request->certificate ?? 0]);
        
        return $order;
        // return Orders::with('user','modifier','creator','collectors','drivers','vehicles')->where('id',$id)->get()->first();
        //
    }
    public function sendToDriver($id){
        Orders::findOrFail($id)->update(['status'=>"Waiting"]);
        $name = Orders::findOrFail($id);
        $driverOrder = Orders::findOrFail($id);
        foreach ($driverOrder->drivers as $value) {
            $this->driverFCM("Order Update","There Is A New Order For Mr/Mrs".$name->client,$value->id,$id);

        }   
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Orders::with('user','modifier','creator','collectors','drivers','vehicles')->where('id',$id)->get()->first();
        //
    }

    public function dummy(Request $request,$id){

        $order = Orders::findOrFail($id);
        if($request->image && $request->format){
            $this->validate($request,[
                'image'=>"required",
                'format'=>"required",
                'phone'=>"required|numeric|min:8",
                'name'=>"required",
                'address'=>"required",
                'vehicle'=>"required",
                'payment'=>"required",
                "driver"=>"required",
                "collector"=>"required",
                "start_time"=>"required",
                "date"=>"required",
                "end_time"=>"required",

            ]);
        }else{
            $this->validate($request,[
                'phone'=>"required|numeric|min:8",
                'name'=>"required",
                'payment'=>"required",
                'address'=>"required",
                'vehicle'=>"required",
                "driver"=>"required",
                "collector"=>"required",
                "date"=>"required",
                "start_time"=>"required",
                "end_time"=>"required"


            ]);
        }




        $date = substr($request->date,0,10);


        if($request->image && $request->format){
            $png_url = "order-".time().".".$request->format;
            $path = public_path('orders/' . $png_url);
        
            \Image::make(file_get_contents("data:image/png;base64,".$request->image))->save($path);     
            $response = array(
                'status' => 'success',
            );
            $image = \URL::to('/')."/orders/".$png_url;
            $order->update([
                'details'=>$request->order,
                'phone'=>$request->phone,
                'note'=>$request->note,
                'payment'=>$request->payment,
                'client'=>$request->name,
                'shipping'=>$request->address,
                'date'=>$date,
                'start_time'=>$request->start_time,
                'end_time'=>$request->end_time,
                'last_modified'=>$request->user()->id,
                'receipt'=>$request->receipt,
                'urgent'=>$request->urgent ?? 0,
                'certificate'=>$request->certificate ?? 0,
                'lat'=>$request->lat,
                'lng'=>$request->lng,
                'image'=>$image
            ]);
         }else{
            $order->update([
                'details'=>$request->order,
                'phone'=>$request->phone,
                'note'=>$request->note,
                'payment'=>$request->payment,
                'client'=>$request->name,
                'shipping'=>$request->address,
                'date'=>$date,
                'start_time'=>$request->start_time,
                'end_time'=>$request->end_time,
                'last_modified'=>$request->user()->id,
                'receipt'=>$request->receipt,
                'urgent'=>$request->urgent ?? 0,
                'certificate'=>$request->certificate ?? 0,
                'lat'=>$request->lat,
                'lng'=>$request->lng,
            ]);
            }

            $driversNotify =  Orders::find($id)->drivers;
            foreach($driversNotify as $delete){
                \DB::table('driver_order')->where('user_id',$delete->id)->delete();
            }

            $collectorsNotify =  Orders::find($id)->collectors;
            foreach($collectorsNotify as $delete){
                \DB::table('collector_order')->where('user_id',$delete->id)->delete();
            }

            $vehicles =  Orders::find($id)->vehicles;
            foreach($vehicles as $delete){
                \DB::table('vehicle_order')->where('vehicles_id',$delete->id)->delete();
            }

            $collectors = [];
            if(!is_array($request->collector)){
                $request->collector = array($request->collector);
            }
            foreach ($request->collector as $key => $value) {
                   $collectors[] = ['user_id'=>$value,'orders_id'=>$order->id];       
            }
    foreach ($collectors as $key => $value) {
        \DB::table('collector_order')->insert($value);
    }
    
    $vehicles = [];
    if(!is_array($request->vehicle)){
        $request->vehicle = array($request->vehicle);
    }
    foreach ($request->vehicle as $key => $value) {
           $vehicles[] = ['vehicles_id'=>$value,'orders_id'=>$order->id];       
    }
    foreach ($vehicles as $key => $value) {
    \DB::table('vehicle_order')->insert($value);
    }
    
    $drivers = [];
    if(!is_array($request->driver)){
        $request->driver = array($request->driver);
    }
    foreach ($request->driver as $key => $value) {
           $drivers[] = ['user_id'=>$value,'orders_id'=>$order->id];       
    }
    foreach ($drivers as $key => $value) {
    \DB::table('driver_order')->insert($value);
    }




            if($order->status=='Scheduled'){
                $collectorsNotify =  Orders::find($id)->collectors;
                $this->fcm("Order Update","A Order Has Been Updated For ".$order->client,$collectorsNotify,'Collector',$order->id);
    
             }elseif($order->status=='UnPrepared'){
                $collectorsNotify =  Orders::find($id)->collectors;
                $this->fcm("Order Update","A Order Has Been Updated For ".$order->client,$collectorsNotify,'Collector',$order->id);
             }elseif($order->status=='Preparing'){
                $users = Role::find(4);
                $officers = [];
                foreach ($users->users as $officer) {
                    $officers[] = $officer;
                                # code...
                }
              $this->fcm("Order Update","A Order Has Been Updated For ".$order->client,$officers,'Officer',$id);
             }else{
                $driversNotify =  Orders::find($id)->drivers;
                $this->fcm("Order Update","A Order Has Been Updated For ".$order->client,$driversNotify,'Driver',$order->id);
             } 


return $order;










    }
   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        \Log::info($request->all());
     $order = Orders::findOrFail($id);
     if($order->status!='Waiting'){
         if($request->image && $request->format){
            $png_url = "order-".time().".".$request->format;
            $path = public_path('orders/' . $png_url);
        
            \Image::make(file_get_contents("data:image/png;base64,".$request->image))->save($path);     
            $response = array(
                'status' => 'success',
            );
            $image = \URL::to('/')."/orders/".$png_url;
            $order->update([
                'details'=>$request->order,
                'phone'=>$request->phone,
                'note'=>$request->note,
                'payment'=>$request->payment,
                'client'=>$request->name,
                'shipping'=>$request->address,
                'date'=>$request->date,
                'time'=>$request->normal,
                'last_modified'=>$request->user()->id,
                'type'=>$request->type,
                'receipt'=>$request->receipt,
                'image'=>$image
            ]);
         }else{
            $order->update([
                'details'=>$request->order,
                'phone'=>$request->phone,
                'note'=>$request->note,
                'payment'=>$request->payment,
                'client'=>$request->name,
                'shipping'=>$request->address,
                'date'=>$request->date,
                'last_modified'=>$request->user()->id,
                'time'=>$request->normal,
                'receipt'=>$request->receipt,
                'type'=>$request->type
            ]);
            }
     }else{
if($request->orderType == 'normal'){

    if($request->image && $request->format){
        $png_url = "order-".time().".".$request->format;
        $path = public_path('orders/' . $png_url);
    
        \Image::make(file_get_contents("data:image/png;base64,".$request->image))->save($path);     
        $response = array(
            'status' => 'success',
        );
        $image = \URL::to('/')."/orders/".$png_url;
    $order->update([
        'assigned_by'=>$request->user()->id,
        'user_id'=>$request->pilot,
        'image'=>$image,
        'vehicle_id'=>$request->vehicle,
        'shipping'=>$request->address,
        'client'=>$request->name,
        'phone'=>$request->phone,
        'note'=>$request->note,
        'orderType'=>$request->orderType,
        'type'=>$request->type,
         'receipt'=>$request->receipt,
        'payment'=>$request->payment,
        'date'=>null,
        'last_modified'=>$request->user()->id,
        'time'=> null,
        'receipt'=>$request->receipt ?? null,
        'details'=>$request->order ?? null,

    ]);
    }else{
        $order->update([
            'assigned_by'=>$request->user()->id,
            'user_id'=>$request->pilot,
            'vehicle_id'=>$request->vehicle,
            'shipping'=>$request->address,
            'client'=>$request->name,
            'phone'=>$request->phone,
            'note'=>$request->note,
            'receipt'=>$request->receipt,
            'orderType'=>$request->orderType,
            'type'=>$request->type,
            'payment'=>$request->payment,
            'date'=>null,
            'last_modified'=>$request->user()->id,
            'time'=> null,
            'receipt'=>$request->receipt ?? null,
            'details'=>$request->order ?? null,
    
        ]);
    }
       if(Orders::findOrFail($id)->user_id != $request->pilot || Orders::findOrFail($id)->vehicle_id != $request->vehicle){
        $pilot = User::findOrFail($request->pilot);
if($pilot->id!= Orders::findOrFail($id)->user_id){
        $this->fcm($pilot->name." Update","The Order Has Been Updated",$pilot->id,$id);
}
       }else{
        $pilot = User::findOrFail(Orders::findOrFail($id)->user_id);
        // $this->fcm($pilot->name." Update","The Order Has Been Delivered",$pilot->id);
        if($pilot->id!= Orders::findOrFail($id)->user_id){
        $this->fcm("Order Update","There Is A New Order For Mr/Mrs".$request->name,$pilot->id,$id);
        }
       }
    
  


}else{
    $date = null;
    if($request->date){
        $date = substr($request->date,0,10);
    }

    // if($order->type=='normal'){
    //         $updateOrder = Orders::findOrFail($order->id)->update(['orderType'=>'normal','processing'=>0]);
    //         $this->fcm("Order Update","There Is An Order Update For Mr/Mrs".$order->client,$order->user_id);
    // }

    if($request->image && $request->format){
        $png_url = "order-".time().".".$request->format;
        $path = public_path('orders/' . $png_url);
    
        \Image::make(file_get_contents("data:image/png;base64,".$request->image))->save($path);     
        $response = array(
            'status' => 'success',
        );
        $image = \URL::to('/')."/orders/".$png_url;
    $order->update([
        'assigned_by'=>$request->user()->id,
        'user_id'=>$request->pilot,
        'image'=>$image,
        'vehicle_id'=>$request->vehicle,
        'shipping'=>$request->address,
        'client'=>$request->name,
        'phone'=>$request->phone,
        'note'=>$request->note,
        'orderType'=>$request->orderType,
        'type'=>$request->type,
       'receipt'=>$request->receipt,
    //    'processing'=>null,
        'payment'=>$request->payment,
        'date'=>$date ?? null,
        'last_modified'=>$request->user()->id,
        'time'=>$request->time ?? null,
        'receipt'=>$request->receipt ?? null,
        'details'=>$request->order ?? null,

    ]);
    }else{
        $order->update([
            'assigned_by'=>$request->user()->id,
            'user_id'=>$request->pilot,
            'vehicle_id'=>$request->vehicle,
            'shipping'=>$request->address,
            'client'=>$request->name,
            'phone'=>$request->phone,
            'note'=>$request->note,
            'orderType'=>$request->orderType,
            'type'=>$request->type,
            'payment'=>$request->payment,
             'receipt'=>$request->receipt,
            //  'processing'=>null,
            'date'=>$date ?? null,
            'last_modified'=>$request->user()->id,
            'time'=>$request->time ?? null,
            'receipt'=>$request->receipt ?? null,
            'details'=>$request->order ?? null
        ]);
    }
       if(Orders::findOrFail($id)->user_id != $request->pilot || Orders::findOrFail($id)->vehicle_id != $request->vehicle){
        $pilot = User::findOrFail($request->pilot);
if($pilot->id!= Orders::findOrFail($id)->user_id){

        $this->fcm($pilot->name." Update","A Order Has Been Updated",$pilot->id,$id);
}
       }else{
        $pilot = User::findOrFail(Orders::findOrFail($id)->user_id);
        // $this->fcm($pilot->name." Update","The Order Has Been Delivered",$pilot->id);
        if($pilot->id!= Orders::findOrFail($id)->user_id){

        $this->fcm("Order Update","There Is A New Upcoming Order For Mr/Mrs ".$request->name,$pilot->id,$id);
       }
       }

}


     }

        //
        return  Orders::findOrFail($id);

    }


    public function driverFCM($heading,$message,$id){
        $access_token = 'AAAAEU0XO60:APA91bH9MKSRQAzxDgPRidceceSMWuKntLvh3h90HMUqrNYvrHxcbFA6rn6rqwMXZN7ugCnJSAKWHrLJGJXSpL5pxpEmu2wkEFR9a_Vf5TNrHWs2FqpC_WF6QOSZUqebPFw4zQgmqNDL';
    $reg_id = User::findOrFail($id)->fcm;
    //$role = User::findOrFail($id)->role;
    $message = [
        'to' => $reg_id,
        'data'=>array('role'=>'Driver','type'=>'normal','heading'=>'Order Update','text'=>"Rty Has On The Way The Order"),
        'notification' => [
            'title' => $heading,
            'body' => $message,
            'priority'=>"high",
            'sound'=>"default"
            ],
    
    ];
    
    $client = new \GuzzleHttp\Client([
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'key='.$access_token,
        ]
    ]);
    
    $response = $client->post('https://fcm.googleapis.com/fcm/send',
        ['body' => json_encode($message)]
    );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $order = Orders::findOrFail($id);
         if($order->status=='Scheduled'){
            $collectorsNotify =  Orders::find($id)->collectors;
foreach($collectorsNotify as $delete){
    \DB::table('collector_order')->where('user_id',$delete->id)->delete();
}

            $this->fcm("Order Update","A Order Has Been Removed For ".$order->client,$collectorsNotify,'Collector',$order->id);

         }elseif($order->status=='UnPrepared'){
            $collectorsNotify =  Orders::find($id)->collectors;
            foreach($collectorsNotify as $delete){
                \DB::table('collector_order')->where('user_id',$delete->id)->delete();
            }
            
            $this->fcm("Order Update","A Order Has Been Removed For ".$order->client,$collectorsNotify,'Collector',$order->id);
         }elseif($order->status=='Preparing'){
            $users = Role::find(4);
            $officers = [];
            foreach ($users->users as $officer) {
                $officers[] = $officer;
            }
            
          $this->fcm("Order Update","A Order Has Been Removed For ".$order->client,$officers,'Officer',$id);
         }else{
             
            $driversNotify =  Orders::find($id)->drivers;
            foreach($driversNotify as $delete){
                \DB::table('driver_order')->where('user_id',$delete->id)->delete();
            }
            $this->fcm("Order Update","A Order Has Been Removed For ".$order->client,$driversNotify,'Driver',$order->id);
         } 
        return Orders::findOrFail($id)->delete();
        //
    }
}
