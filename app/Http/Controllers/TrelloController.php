<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Response;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

use Unirest\Request as URequest;

class TrelloController extends Controller
{
    public function __construct()
    {
        
        $this->headers = array(
            'Accept' => 'application/json'
        );
        
        $this->query = array(
            'key' => 'bbe0a03ad5389c31c5e03948fc9542c5',
            'token' => '5ca5d09905d0a2fdda23b8b4ba384c7a3619967649365d2d4061342b0fe3bc73'
        );

    }

    public function getboardlists()
    {
        // dd("working");
        $response = URequest::get(
            'https://api.trello.com/1/boards/1SVeEb58/lists',
            $this->headers,
            $this->query
        );

        return Response($response->body);
    }

    public function addcard(Request $request)
    {
        // $query_1 = array(
        //     'name' => 'high',
        //     'color' => 'red',
        //     'idBoard' => '1SVeEb58',
        //     'key' => $this->query["key"],
        //     'token' => $this->query["token"]
        // );
          
        // $response = URequest::post(
        //     'https://api.trello.com/1/labels',
        //     // $this->headers,
        //     $query_1
        // );
        // dd($response);

        $list = URequest::get(
            'https://api.trello.com/1/boards/1SVeEb58/lists',
            $this->headers,
            $this->query
        );
        $list_body = $list->body;
        
        $found_key = array_search($request->list, array_column($list_body, 'name'));
        
        $list_id = $list_body[$found_key]->id;

        // dd($this->query["key"]);

        $body = array(
            'idList' => $list_id,
            'key' => $this->query["key"],
            'token' => $this->query["token"],
            'name' => $request->name,
            'desc' => $request->desc,
            'pos' => "top"
        );

        

        $response = URequest::post(
            'https://api.trello.com/1/cards',
            $this->headers,
            $body
        );

        return $response->body->name;


    }
}
