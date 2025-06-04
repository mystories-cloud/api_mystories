<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse\Response;

class UserController extends Controller
{
    use Response;

    public function getStarted()
    {
        $users = User::where('get_started', true)->orderByDesc('id')->get();

        return $this->response($users);
    }
}
