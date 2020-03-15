<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2020/3/12
 * Time: 6:59 下午
 */


namespace App\Http\Middleware\Admin;


use Closure;
use Z1px\Tool\Aes;

/**
 * 返回参数加密
 * Class DataEncryptionMiddleware
 * @package App\Http\Middleware\Admin
 */
class DataEncryptionMiddleware
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
        $response = $next($request);

        // 执行一些任务
//        dump("DataEncryptionMiddleware");


        // 对传输的数据进行AES加密处理
        if(isset($response->headers) && false !== strpos($response->headers->get('Content-Type'), 'application/json')){
            if(!$request->route() || $request->route()->getName() !== 'admin.upload'){
                $secret_key = 'OUM6iqqNsAmSDsx97YXz6IGbCaTs8Lvw6nD4zqMtn0Y';
                $vi = 'Dsx96IG8LvzqMt0Y';
                $content = $response->getContent();
                $content = Aes::encrypt($content, $secret_key, 'AES-256-CBC', OPENSSL_RAW_DATA, $vi);
                $response->setContent(is_string($content) ? $content : '');
                unset($secret_key, $vi, $content);
            }
        }

        return $response;
    }

}
