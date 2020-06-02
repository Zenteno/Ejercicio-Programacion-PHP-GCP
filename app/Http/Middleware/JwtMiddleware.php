<?php

namespace App\Http\Middleware;
use \Lcobucci\JWT\Parser;
use \Lcobucci\JWT\ValidationData;

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
		if (!$token->validate($data))
			return response([],401);
		return $next($request);
	}
}
