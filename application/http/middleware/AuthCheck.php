<?php

namespace app\http\middleware;

use app\facade\Admin;
class AuthCheck
{
    public function handle($request, \Closure $next)
    {
        $controllerName = strtolower($request->controller());
        $actionName = strtolower($request->action());

        $path = str_replace('.', '/', $controllerName) . '/' . $actionName;
        
        if(!Admin::check($path, $request->admin_id )) {
            return json([
                'code'=> 2,
                'message' => '',
                'data' => []
            ]);
        }
        return $next($request);
    }
}
