<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class CommanMessageController extends Controller
{
    public function messageCreate($id=0, $title="", $messageText=""){

            $success=false;
  
            $saveItem = new Message;
            $saveItem->user_id = $id;
            $saveItem->title = $title;
            $saveItem->message = $messageText;
            $saveItem->read_status = 0;
            $saveItem->status = 1;
            $saveItem->save();

            if(!$saveItem){   $success=false; }
            else{   $success=true;  }

            return $success;
       

    }
}