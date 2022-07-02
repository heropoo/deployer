<?php
/**
 * User: heropoo
 * Datetime: 2022/5/23 1:12 上午
 */


if (!function_exists('format_json')) {
    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return string
     */
    function format_json($code = 0, $msg = "success", $data = [])
    {
        header('Content-type:application/json;charset=utf-8');
        return json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => (object)$data
        ]);
    }
}

if (!function_exists('return_json')) {
    /**
     * @param array $data
     * @return string
     */
    function return_json($data)
    {
        header('Content-type: application/json;charset=utf-8');
        return json_encode($data);
    }
}

if (!function_exists('generate_random_str')) {
    /**
     * @param int $length
     * @return string
     */
    function generate_random_str($length)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $random_str = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $random_str .= $str[$num];
        }
        return $random_str;
    }
}


if (!function_exists('is_cli')) {
    /**
     * check if php running in cli mode
     */
    function is_cli()
    {
        return preg_match("/cli/i", php_sapi_name()) ? true : false;
    }
}

if (!function_exists('root_path')) {
    /**
     * @param string $path
     * @return string
     */
    function root_path($path = '')
    {
        return \App::$instance->getRootPath() . (strlen($path) ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('dump')) {
    /**
     * pretty var_dump
     * @param $var
     * @params mixed $var
     */
    function dump($var)
    {
        if (is_cli()) {
            foreach (func_get_args() as $var) {
                var_dump($var);
            }
        } else {
            echo '<pre>';
            foreach (func_get_args() as $var) {
                var_dump($var);
            }
            echo '</pre>';
        }
    }
}

if (!function_exists('dd')) {
    /**
     * pretty var_dump and exit 1
     * @param mixed $var
     */
    function dd($var)
    {
        call_user_func_array('dump', func_get_args());
        exit(1);
    }
}

if (!function_exists('request')) {
    /**
     * @param null|string $key
     * @param null|mixed $default
     * @return null|mixed|\Moon\Request\Request $request
     */
    function request($key = null, $default = null)
    {
        /** @var Moon\Request\Request $request */
        $request = \App::$container->get('request');
        if (is_null($key)) {
            return $request;
        }
        return $request->get($key, $default);
//        return is_null($value) || strlen($value) == 0 ? $default : $value;
    }
}

if (!function_exists('abort')) {
    /**
     * @param int $code
     * @param string $message
     * @return string
     * @throws \Moon\HttpException
     */
    function abort($code = 404, $message = '')
    {
        throw new \Moon\HttpException($message, $code);
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }

        return $value;
    }
}


if (!function_exists('config')) {
    /**
     * get a config
     * @param string $key
     * @param bool $throw
     * @return mixed|null|\Moon\Config\Exception
     */
    function config($key, $throw = false)
    {
        $config = \App::$container->get('config');
        return $config->get($key, $throw);
    }
}

if (!function_exists('view')) {
    /**
     * render a view
     * @param string $view
     * @param array $data
     * @param null|string $layout
     * @param null|string $viewPath
     * @return \Moon\View
     */
    function view($view, $data = [], $layout = null, $viewPath = null)
    {
        return new \Moon\View($view, $data, $layout, $viewPath);
    }
}
