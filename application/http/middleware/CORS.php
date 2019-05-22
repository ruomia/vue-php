<?php
namespace app\http\middleware;

use think\facade\Response;
class CORS
{
    public function handle($request, \Closure $next)
    {
        // $origin = $request->server('HTTP_ORIGIN') ?: '';
        // $allow_origin = [
        //     'http://zz.cnguu.cn',
        // ];
        // if (in_array($origin, $allow_origin)) {

        // // }
        // return $next($request);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE');
        header('Access-Control-Max-Age: 1728000');
        if (strtoupper($request->method()) == "OPTIONS") {
            return Response::create();
        }

        return $next($request);
    }
}
