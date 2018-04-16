<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RepliesRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Transformers\ReplyTransformer;

class RepliesController extends Controller
{
    public function store(RepliesRequest $request,Topic $topic,Reply $reply)
    {
        $reply->content=$request->content;
        $reply->topic_id=$topic->id;
        $reply->user_id=$this->user()->id;
        $reply->save();

        return $this->response->item($reply,new ReplyTransformer())->setStatusCode(201);
    }
}
