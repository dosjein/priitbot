<?php namespace Dosje_in\priitbot;

use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\BadResponseException;

use Illuminate\Http\Request;

use Config;
use Cache;
use Carbon\Carbon;


trait ChatFieldTrait {

    public function chatbot(Request $request)
    {
      $return = $request->all();

      if(!$request->ajax()){
        return view('chatbot');
      }

      $expiresAt = Carbon::now()->addMinutes(2);

      if (!Cache::has($return['message'].'_message')){
          $return['put_msh'] = 1;
          Cache::put($return['message'].'_message', json_encode($return['message']), $expiresAt); 
      }

      if (!isset($return['message'])){
        return ')))';
      }else{
        $return['requested'] = 1;
        $msg = $this->prepareResponse($return['message'] , $return['message']);

        if ($msg){
          $return['msg'] = $msg;
        }

        $return['error_log'] = $this->error;
        $return['progress'] = Cache::get('message_in_process');

        return die(json_encode($return));
      }
    }

    public function error($idontcare){
      $this->error[] = $idontcare;
    }

    public function cache(){
      Cache::flush();
    }

    public function prepareResponse($id , $message){

        if (!Cache::has($id.'_message')){
            $this->error('Message not tagged');
            return array('no_msg');
        }

        $chatBotUrl = Config::get('priit.chatbot_url');
        $chatBotToken = Config::get('priit.chatbot_token');
        if (!$chatBotUrl || !$chatBotToken){
            return array('ChatBot details missing');
        }

        $return = false;

        if (!Cache::has($id.'_message_sent') && !Cache::has('message_in_process')){
            $expiresAt = Carbon::now()->addMinutes(2);

            $client = new Client();
            $options = array('query' => array(
                'IDENT' => Config::get('priit.chatbot_token'),
                'IN' => $message
            ));

            try {

                $response = $client->get(Config::get('priit.chatbot_url'), $options);
                $json = json_decode($response->getBody(true)->getContents() , true);

                if (!(json_last_error() == JSON_ERROR_NONE && is_array($json))) {
                    $this->error('Response Error');
                }else{
                    Cache::put($id.'_message_sent', $message, $expiresAt); 
                    Cache::put('message_in_process', $id, $expiresAt); 
                    Cache::put('message_last_timestamp', $json['edit_time'], $expiresAt); 
                }

            } catch (BadResponseException $ex) {
                $error =  array('error' => 1 , 'details' => 'problems : '.$ex->getResponse()->getBody()); 

                $this->error(json_encode($error));
            }

        }else if (Cache::get('message_in_process') == $id){

            $expiresAt = Carbon::now()->addMinutes(2);

            $client = new Client();
            $options = array('query' => array(
                'IDENT' => Config::get('priit.chatbot_token'),
            ));

            try {

                $response = $client->get(Config::get('priit.chatbot_url'), $options);
                $json = json_decode($response->getBody(true)->getContents() , true);

                if (!(json_last_error() == JSON_ERROR_NONE && is_array($json))) {
                    $this->error('Response Error');
                }else if ($json['edit_time'] != Cache::get('message_last_timestamp')){
                    Cache::forget('message_in_process'); 
                    Cache::put('message_last_timestamp', $json['edit_time'], $expiresAt); 
                }

                $return['response'] = $json['message'];


            } catch (BadResponseException $ex) {
                $error =  array('error' => 1 , 'details' => 'problems : '.$ex->getResponse()->getBody()); 

                $this->error(json_encode($error));
            }
        }

        return $return;
    }

}