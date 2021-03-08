<?php
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
// return  "Hello";
// });

Route::get('testing',function(){

$access_token = 'AAAAEU0XO60:APA91bH9MKSRQAzxDgPRidceceSMWuKntLvh3h90HMUqrNYvrHxcbFA6rn6rqwMXZN7ugCnJSAKWHrLJGJXSpL5pxpEmu2wkEFR9a_Vf5TNrHWs2FqpC_WF6QOSZUqebPFw4zQgmqNDL';

$reg_id = 'fbM_Xq4pSF-200Q5wiIT2o:APA91bGIGjuc6ORLkU1ZwG0WGnK2914aGEiqow7226TY1VxIZpGLlWwYKyxkXvFcP5F5ftmAXWfB54vVds_n5em3ZCM6GHNXDPYWUY--5QJKUZdA3R5YSF4UEo6DRJWwQkDZcw7jRztz';

$message = [
    'to' => $reg_id,
    'data'=>array('role'=>'Manager','heading'=>'Order Update','text'=>"Tintnhue Has Shipped The Order"),
    'notification' => [
        'title' => 'Order Update',
        'body' => "Tintnhue Is Shipping The Order",
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
});
