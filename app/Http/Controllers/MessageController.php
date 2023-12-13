<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function viewAllMessages(){
        $messages = Message::query()->get();
        return [
            "success" => true,
            "data" => $messages
        ];
    }
}
