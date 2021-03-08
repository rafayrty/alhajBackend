<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\User;
use App\Models\Vehicles;

use Carbon\Carbon;
use App\Models\Waiting;
class ScheduleOrders2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:schedule2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For Scheduling Orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Orders::all();
        $users = User::all();
       foreach($users as $user){
  $pilotd = Orders::where('user_id',$user->id)->where('orderType','=','normal')->where('status','Waiting')->orWhere('status','Preparing')->orWhere('status','On The Way')->count();
  if($pilotd==0){
    if(User::findOrFail($user->id)->status != 'Available'){
      $usera =  User::findOrFail($user->id);
      $usera->status = 'Available';
      $usera->save();
        $this->info($user);   

    }
  }
}
$vehicles = Vehicles::all();

foreach($vehicles as $vehicle){
    $vehicled = Orders::where('vehicle_id',$vehicle->id)->where('orderType','=','normal')->where('status','Waiting')->orWhere('status','Preparing')->orWhere('status','On The Way')->count();
    if($vehicled==0){
      if(Vehicles::findOrFail($vehicle->id)->status != 'Available'){
        $vehiclea =  Vehicles::findOrFail($vehicle->id);
        $vehiclea->status = 'Available';
        $vehiclea->save();
          $this->info($vehiclea);   
  
      }
    }
  }

        foreach ($orders as $order) {
            // if($order->status=='Waiting'){
            // if($order->orderType=='normal'){
            //     $user = User::findOrFail($order->user_id);
            //     if($user->status=='Available'){

            //         $pilot = User::findOrFail($order->user_id)->update(['status'=>"Out For Delivery"]);
            //         $updateOrder = Orders::findOrFail($order->id)->update(['orderType'=>'normal','processing'=>1]);
            //         $this->fcm("Order Update","There Is A New Order For Mr/Mrs".$order->client,$order->user_id);
        
            //     }
            // }      
            // }
        
if($order->orderType=='schedule'){
    if($order->date!=null){
        $time = substr($order->time,11,8);
        $newDate = $order->date . " " . $time;
    
    if(Carbon::parse($newDate)->diffInMinutes(now(),false) >= 0){
$user = User::findOrFail($order->user_id);
if($user->status=='Available'){
    // $waiting = Waiting::create([
    //     'date'=>$order->date,
    //     'time'=>$time,
    //     'order_id'=>$order_id
    // ]);
    $pilot = User::findOrFail($order->user_id)->update(['status'=>"Out For Delivery"]);
    $updateOrder = Orders::findOrFail($order->id)->update(['orderType'=>'normal']);
    $this->fcm("Order Update","There Is A New Order For Mr/Mrs".$order->client,$order->user_id);
}



    }
}
}



$this->info('Schedule Command Run successfully!');

            # code...
        }

    }

    public function fcm($heading,$message,$id){
        $access_token = 'AAAAEU0XO60:APA91bH9MKSRQAzxDgPRidceceSMWuKntLvh3h90HMUqrNYvrHxcbFA6rn6rqwMXZN7ugCnJSAKWHrLJGJXSpL5pxpEmu2wkEFR9a_Vf5TNrHWs2FqpC_WF6QOSZUqebPFw4zQgmqNDL';
    $reg_id = User::findOrFail($id)->fcm;
    
    $message = [
        'to' => $reg_id,
        'data'=>array('role'=>User::findOrFail($id)->role,'heading'=>'Order Update','text'=>"Tintnhue Has On The Way The Order"),
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










}
