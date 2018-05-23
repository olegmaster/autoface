<?php

namespace App\Http\Controllers;

use Illuminate\{Http\Request, Support\Facades\Auth};
use App\Message;

class MessageController extends Controller
{

    public function index(){
        $user_id = Auth::id();
        $messages = Message::where('user_id', $user_id)->paginate(3);
        return view('message.index', ['messages' => $messages]);
    }

    private function checkMessageAlreadyAdded($request, $device_id){
        $key = 'alarm' . $device_id;
        $alarmStatus = $request->session()->get($key);
        if(!isset($alarmStatus['message_is_shown'])){
            return false;
        }
        return true;
    }

    public function handleMessage(Request $request){
        $device_id = $request->device_id;
        if(!$this->checkMessageAlreadyAdded($request, $device_id)){

            $data = $this->getMessageData($request);

            $message = new Message;
            $message->user_id = Auth::id();
            $message->device_id = $data['device_id'];
            $message->type = 'alarm_system';
            $message->status = 'processed';
            $message->data = '';

            $message->save();
            $this->tagMessageHandled($request, $device_id);
        }
    }

    private function addMessage($data){
        $message = new Message($data);
        $message->save();
        return response()->json(['status' => 'success']);
    }

    private function getMessageData($request){
        $result = [];
        $result['device_id'] = $request->device_id;
        $result['type'] = 'alarm_system';
        $result['status'] = 'processed';
        $result['data'] = '';
        return $result;
    }

    private function tagMessageHandled($request, $device_id){
        $key = 'alarm' . $device_id;
        $alarmStatus = $request->session()->get($key);
        $alarmStatus['message_is_shown'] = 1;
        $request->session()->put($key, $alarmStatus);

    }




}
