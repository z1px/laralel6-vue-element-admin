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
 * 请求参数解密
 * Class DataDecryptionMiddleware
 * @package App\Http\Middleware\Admin
 */
class DataDecryptionMiddleware
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
        // 执行一些任务
//        dump("DataDecryptionMiddleware");

        // 清除掉非加密传输过来的数据
        $keys = $request->keys();
        foreach ($keys as $key){
            $request->offsetUnset($key);
        }
        unset($keys);

        // 对传输过来的数据进行AES解密处理
        $data = file_get_contents("php://input");
        if(!empty($data)){
            $secret_key = 'OUM6iqqNsAmSDsx97YXz6IGbCaTs8Lvw6nD4zqMtn0Y';
            $vi = 'Dsx96IG8LvzqMt0Y';
            $data = Aes::decrypt($data, $secret_key, 'AES-256-CBC', OPENSSL_RAW_DATA, $vi);
            unset($secret_key, $vi);
            if($data){
                $data = json_decode($data, true) ?: [];
            }
            if($data && is_array($data)){
                foreach ($data as $key=>$value){
                    $request->offsetSet($key, $value);
                }
            }
        }
        unset($data);

        if($request->route() && $request->route()->getName() === 'admin.upload'){
            if($request->hasHeader('X-Token') && !$request->offsetExists('accessToken')){
                $request->offsetSet('accessToken', $request->header('X-Token'));
            }
        }

        return $next($request);
    }

}
