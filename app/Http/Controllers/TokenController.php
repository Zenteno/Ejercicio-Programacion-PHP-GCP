<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Lcobucci\JWT\Builder;

class TokenController extends Controller
{
    /**
     * Retrieve a new token
     *
     * @return Response
     */
    public function tokenizer()
    {
    	$time = time();
    	$token = (new Builder())
	        ->issuedAt($time)
	        ->expiresAt($time + 60 * env('TOKEN_DURATION', 30))
	        ->getToken();
    	return ["token" =>  strval($token)];
    }
}