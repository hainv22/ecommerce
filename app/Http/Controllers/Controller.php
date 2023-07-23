<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function writeLogInDatabase($data, $content = null)
    {
        return Log::create([
            'user_id' => Auth::id(),
            'type' => $data['type'],
            'content' => $content,
            'data' => json_encode($data)
        ]);
    }

    public function makeDataLogByRequest($type, $request)
    {
        return $request->all() + array('ip' => $request->ip(), 'method' => $request->method(), 'url' => $request->url(), 'type' => $type);
    }
}
