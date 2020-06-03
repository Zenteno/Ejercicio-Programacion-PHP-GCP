<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Validator;
use Closure;
use ValidateRequests;

class StudentMiddleware
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
		if(strcasecmp($request->header('content-type'), "application/json")!=0)
			return response(["msg"=>"missing json content-type"],400);
		$v = Validator::make($request->all(), [
            'rut' => 'required|string|cl_rut|max:15',
            'name' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'age' => 'required|integer|min:19',
            'course' => 'required|integer|exists:courses,id',
        ]);
        if($v->fails())
        	return response(["msg"=>"Input Error"],400);
		return $next($request);
	}
}
