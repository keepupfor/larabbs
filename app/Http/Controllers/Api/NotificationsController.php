<?php

namespace App\Http\Controllers\Api;

use App\Transformers\NotificationsTransformer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications=$this->user->notifications()->paginate(20);
        return $this->response->paginator($notifications,new  NotificationsTransformer());
    }
}
