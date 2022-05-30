<?php

namespace App\Http\Responses;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse extends Controller implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function toResponse($request)
    {
        $data["status"] = true;
		$data["redirect_url"] = route('dashboard.branch');
		$data["replace"] = true;
        
		return response()->json($data);
    }
}
