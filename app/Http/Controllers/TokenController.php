<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Lcobucci\JWT\Builder;
use \Lcobucci\JWT\Signer\Key;
use \Lcobucci\JWT\Signer\Hmac\Sha256;

class TokenController extends Controller
{
    /**
     * Retrieve a new token
     *
     * @return Response
     */
    public function tokenizer()
    {
    	$signer = new Sha256();
        $key = env('TOKEN_KEY','thisIsMyLittleBe#autifulToken123#');
        $time = time();
    	$token = (new Builder())
	        ->issuedAt($time)
	        ->expiresAt($time + 60 * env('TOKEN_DURATION', 30))
	        ->getToken($signer, new Key($key));
    	return ["token" =>  strval($token)];
    }
}