<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Validator;
use Closure;

class CourseMiddleware
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
            'name' => 'required|string|max:100',
            'code' => 'required|max:4'
        ]);
        if($v->fails()){
        	//var_dump($v->errors());
    		return response(["msg"=>"Input Error"],400);
        }
        return $next($request);
	}
}
