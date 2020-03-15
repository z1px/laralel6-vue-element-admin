<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">

    <title>提示信息 - {{ $message ?? '' }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-borderless/borderless.css" />

</head>
<body>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9.6.0/dist/sweetalert2.min.js"></script>

<script type="text/javascript">
    $(function () {

        @php
            isset($code) || $code = 2;
            is_int($code) || $code = intval($code);
            if(!isset($message)){
                switch ($code){
                    case 0: $message = "Error"; break;
                    case 1: $message = "SUCCESS"; break;
                    case 2: $message = "INFO"; break;
                    case 3: $message = "WARNING"; break;
                    case 4: $message = "QUESTION"; break;
                    default: $message = "WARNING";
                }
            }
            $func_html = function ($data) use (&$func_html){
                $html = "";
                if(empty($data)){
                    $html = "";
                }elseif(is_string($data) || is_numeric($data)){
                    $html = $data;
                }elseif(is_array($data)){
                    $html .= "<dl>";
                    foreach ($data as $key=>$value){
                        if(is_string($key)){
                            $html .= "<dt><span>{$key}</span></dt>";
                        }
                        $html .= "<dd>";
                        if(is_string($value) || is_numeric($value)){
                            $html .= "<span>{$value}</span>";
                        }else{
                            $html .= $func_html($value);
                        }
                        $html .= "</dd>";
                    }
                    $html .= "</dl>";
                }else{
                    $html = "";
                }
                return $html;
            };
            $html = $func_html($data ?? "");
            isset($wait) || $wait = 3;
            is_int($code) || $code = intval($code);
            isset($url) || $url = "javascript:history.back();";
        @endphp

        let timerInterval;
        Swal.fire({
            title: "{{ $message }}",
            html: "{!! $html ?? '' !!}<h5>页面自动 <a id='href' href='{{ $url }}' >跳转</a>等待时间：<strong></strong></h5>",
            icon: 1 === parseFloat("{{ $code }}") ? "success" : "error",
            timer: 1000 * parseFloat("{{ $wait }}"),
            showConfirmButton: true,
            confirmButtonText: "确定",
            allowOutsideClick: false, // 如果设置为false，用户无法通过点击弹窗外部关闭弹窗。
            allowEscapeKey: false, // 如果设置为false，用户无法通过按下Escape键关闭弹窗。
            onBeforeOpen: () => {
                timerInterval = setInterval(() => {
                    Swal.getContent().querySelector('strong')
                        .textContent = (Swal.getTimerLeft() / 1000)
                        .toFixed(0)
                }, 100)
            },
            onClose: () => {
                clearInterval(timerInterval)
            }
        }).then(() => {
            document.getElementById('href').click();
        })
    })
</script>

</body>
</html>
