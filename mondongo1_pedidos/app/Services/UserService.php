<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserService
{
    public function getUserById($userId)
    {
        $response = Http::get('http://localhost:8000/api/users/' . $userId);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}
