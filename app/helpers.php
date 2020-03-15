<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/9/30
 * Time: 11:06 上午
 */

if (! function_exists('_result')) {
    /**
     * @param $data
     * @return array
     */
    function _result($data)
    {
        if(is_string($data) || is_numeric($data)){
            $data = ['message' => $data];
        }
        if(!is_array($data)){
            $data = ['data' => $data];
        }
        $data = array_merge([
            'code' => 0,
            'message' => '请求异常',
            'data' => [],
            'url' => 'javascript:history.back().reload();',
            'wait' => 3,
            'timestamp' => time(),
            'runtime' => microtime(true) - request()->server('REQUEST_TIME_FLOAT'),
        ], $data);
        return $data;
    }
}

if (! function_exists('json')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    function json(array $data = [], int $status = 200, array $headers = [])
    {
        $data = _result($data);

        if(isset($data['url'])){
            unset($data['url']);
        }
        if(isset($data['wait'])){
            unset($data['wait']);
        }

        return response()->json($data, $status, $headers);
    }
}

if (! function_exists('jump')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    function jump(array $data = [], int $status = 200, array $headers = [], string $view='jump')
    {
        $data = _result($data);

        isset($data['url']) || $data['url'] = 'javascript:history.back().reload();';
        isset($data['wait']) || $data['wait'] = 3;

        return response()->view($view, $data, $status, $headers);
    }
}

if (! function_exists('result')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    function result(array $data = [], int $status = 200, array $headers = [], string $view='jump')
    {
        if(request()->isMethod('post')){
            return json($data, $status, $headers);
        }else{
            return jump($data, $status, $headers, $view);
        }
    }
}

if (! function_exists('error')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    function error(int $status = 404, string $view='404', array $data = [], array $headers = [])
    {
        return jump($data, $status, $headers, $view);
    }
}


