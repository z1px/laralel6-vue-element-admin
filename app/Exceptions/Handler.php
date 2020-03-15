<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // 接管异常，自定义异常界面
        if($exception){
            if($exception instanceof AuthorizationException){ // 权限验证异常
                return result([
                    'code' => $exception->getCode() ?: 0,
                    'message' => $exception->getMessage() ?: '请求异常'
                ]);
            } elseif ($exception instanceof ValidationException){ // 验证器错误
                try{
                    $data = $exception->errors();
                    $func_message = function ($message) use (&$func_message){
                        if(is_array($message) && !empty($message)){
                            $message = reset($message);
                            if(is_array($message)){
                                $message = $func_message($message);
                            }
                        }
                        if(!is_string($message) && !is_numeric($message)){
                            $message = '';
                        }
                        return $message;
                    };
                    $message = $func_message($data);
                }catch (Exception $e){
                    $message = $exception->getMessage() ?: '请求异常';
                    $data = [];
                }finally{
                    if((!is_string($message) && !is_numeric($message))){
                        $message = $exception->getMessage() ?: '请求异常';
                    }
                }
                return result([
                    'code' => $exception->getCode() ?: 0,
                    'message' => $message,
                    'data' => $data,
                    'url' => 'javascript:history.back();',
                ]);
            } elseif ($exception instanceof Exception){ // 顶级错误
                return result([
                    'code' => $exception->getCode() ?: 0,
                    'message' => $exception->getMessage() ?: '请求异常',
                    'url' => 'javascript:history.back();',
                ]);
            }
        }

        return parent::render($request, $exception);
    }
}
