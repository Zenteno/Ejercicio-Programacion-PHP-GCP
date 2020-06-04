<?php

namespace App\Http\Middleware;
use \Lcobucci\JWT\Parser;
use \Lcobucci\JWT\ValidationData;
use \Lcobucci\JWT\Signer\Key;
use \Lcobucci\JWT\Signer\Hmac\Sha256;

use Closure;

class JwtMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$signer = new Sha256();
		$key = env('TOKEN_KEY','thisIsMyLittleBe#autifulToken123#');
		$auth = $request->header('Authorization');
		if($auth== null)
			return response([],401);
		$arr = explode(" ", $auth);
		$n = count($arr);
		$token_str = $arr[$n-1];
		try {
			$parser =new Parser();
			$token = $parser->parse($token_str);
		}catch (\Throwable $e) {
			return response([],401);		
		}
		$data = new ValidationData();
		if (!$token->validate($data) || !$token->verify($signer,$key))
			return response([],401);
		return $next($request);
	}
}
