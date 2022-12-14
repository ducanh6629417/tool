<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function add()
    {
        return view('addLink');
    }
    public function saveLink(Request $request)
    {
        $model = Http::withToken($request->token)
            ->get(
                $request->link . '/wp-json/wp/v2/categories?mo_rest_api_test_config=jwt_auth&per_page=100'
            );
        $token = $request->token;
        $link = $request->link;
        $model = json_decode($model);

        return view('add', compact('model', 'token', 'link'));
    }

    public function saveAdd(Request $request)
    {
        set_time_limit(10000);
        $title = $request->title - 1;
        $content = $request->content - 1;
        $data = Excel::toArray([], $request->file('file'));

       

        foreach ($data as $item) {

            foreach ($item as $index => $ite) {
                if ($index >= 1) {
                    $response = Http::withToken($request->token)
                        ->post(
                            $request->link . '/wp-json/wp/v2/posts?mo_rest_api_test_config=jwt_auth',
                            [

                                'title' =>  $item[$index][$title],
                                'content' => $item[$index][$content],
                                
                                'categories'=>$request->category,

                            ]
                        );
                }
            }
        }
        $model = Http::withToken($request->token)
            ->get(
                $request->link . '/wp-json/wp/v2/categories?mo_rest_api_test_config=jwt_auth&per_page=100'
            );
        $token = $request->token;
        $link = $request->link;
        $model = json_decode($model);

        return view('add', compact('model', 'token', 'link'));
       
    }
}
