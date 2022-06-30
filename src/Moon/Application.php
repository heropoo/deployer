<?php
/**
 * User: heropoo
 * Datetime: 2022/7/1 1:09 上午
 */

namespace Moon;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Moon\Container\Container;
use Moon\Request\Request;
use Moon\Routing\Route;
use Moon\Routing\Router;
use Moon\Config\Config;
use Moon\Routing\UrlMatchException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Application
 * @method string getEnvironment()
 * @method string getRootPath()
 * @method string getConfigPath()
 * @method string getAppPath()
 * @method string getCharset()
 * @method string getTimezone()
 * @method array getConfig()
 * @method bool getDebug()
 * @package Moon
 */
class Application
{
    protected $rootPath;
    protected $configPath;
    protected $appPath;

    protected $config = [];

    protected $environment = 'production';
    protected $debug = false;
    protected $charset = 'UTF-8';
    protected $timezone = 'UTC';

    /** @var Container $container */
    public $container;

    /**
     * Application constructor.
     * @param $rootPath
     * @param array $options
     * @param Container $container
     */
    public function __construct($rootPath, array $options = [], Container $container = null)
    {
        if (!is_dir($rootPath)) {
            throw new Exception("Directory '$rootPath' is not exists!");
        }
        $this->rootPath = realpath($rootPath);

        foreach ($options as $option => $value) {
            $this->$option = $value;
        }

        $this->configPath = is_null($this->configPath) ? $this->rootPath . '/config' : $this->configPath;

        $this->appPath = is_null($this->appPath) ? $this->rootPath . '/app' : $this->appPath;

        $this->container = is_null($container) ? new Container() : $container;

        \App::$instance = $this;
        \App::$container = $this->container;

        $this->init();
    }

    protected function init()
    {
//        try {
//            (new Dotenv($this->rootPath))->load();
//        } catch (ExceptionInterface $e) {
//            trigger_error($e->getMessage(), E_USER_ERROR);
//        }

        $config = new Config($this->configPath);
        $this->container->add('config', $config);

        $this->config = $config->get('app', true);

        if (isset($this->config['timezone'])) {
            $this->timezone = $this->config['timezone'];
            date_default_timezone_set($this->timezone);
        }

        if (isset($this->config['charset'])) {
            $this->charset = $this->config['charset'];
        }

        if (isset($this->config['environment'])) {
            $this->environment = $this->config['environment'];
        }

        if (isset($this->config['debug'])) {
            $this->debug = $this->config['debug'];
        }

        $this->initLogger();

//        if (isset($_SERVER)) { //todo In swoole or fastcgi
        $this->handleError();
//        }

        $this->initRoutes();

        $this->initComponents();
    }

    /**
     * handle application errors
     */
    protected function handleError()
    {
        set_error_handler(
            function($level, $error, $file, $line){
                if(0 === error_reporting()){
                    return false;
                }
                throw new \ErrorException($error, -1, $level, $file, $line);
            },
            E_ALL
        );

        register_shutdown_function(function(){
            $error = error_get_last();
            if($error){
                throw new \ErrorException($error['message'], -1, $error['type'], $error['file'], $error['line']);
            }
        });

        $debug = $this->debug;
        set_exception_handler(function($exception) use ($debug){
            /** @var \Exception $exception */
            if($debug){
                echo "<pre>".$exception->__toString();
            }
            /** @var Logger $logger */
            $logger = $this->container->get('logger');
            $logger->error($exception->__toString());
            http_response_code(500);
        });

//        $logger = $this->container->get('logger');

//        $whoops = new Run();
//
//        if (is_cli()) {
//            $whoops->pushHandler(new PlainTextHandler());
//        } else {
//            if ($this->debug) {
//                if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"] == 'xmlhttprequest')) {
//                    $whoops->pushHandler(new JsonResponseHandler());
//                } else {
//                    $handler = new PrettyPageHandler();
//                    $handler->setPageTitle('Moon App Error');
//                    $whoops->pushHandler($handler);
//                }
//            }
//        }
//
//        $handler = new PlainTextHandler($logger);
//        $handler->loggerOnly(true);
//        $whoops->pushHandler($handler);
//        $whoops->register();
    }

    public function initComponents()
    {
        isset($this->config['components']) ?: $this->config['components'] = [];
        foreach ($this->config['components'] as $componentName => $params) {

            $className = $params['class'];
            unset($params['class']);

            if (!isset($params['auto_inject_by_class']) || $params['auto_inject_by_class'] !== false) {
                $this->container->alias($className, $componentName);
            }
            unset($params['auto_inject_by_class']);

            $this->container->bind($componentName, function () use ($className, $params) {
                $ref = new \ReflectionClass($className);
                return $ref->newInstanceArgs($params);
            }, true);

        }
    }

    protected function initLogger()
    {
        $logger = new Logger('app');
        $this->container->add('logger', $logger);
        $filename = $this->rootPath . '/runtime/logs/app-' . date('Y-m-d') . '.log';
        $logger->pushHandler(new StreamHandler($filename, Logger::DEBUG));
    }

    public function initRoutes()
    {
        $route_config = $this->container->get('config')->get('route');

        $router = new Router([
            'namespace' => isset($route_config['namespace']) ? $route_config['namespace'] : 'App\Controllers',
            'prefix' => isset($route_config['prefix']) ? $route_config['prefix'] : null,
            'middleware' => isset($route_config['middleware']) ? $route_config['middleware'] : [],
        ]);

        $this->container->add('router', $router);

        if (isset($route_config['groups'])) {
            foreach ($route_config['groups'] as $group) {
                $router->group([
                    'namespace' => isset($group['namespace']) ? $group['namespace'] : null,
                    'prefix' => isset($group['prefix']) ? $group['prefix'] : null,
                    'middleware' => isset($group['middleware']) ? $group['middleware'] : [],
                ], function (Router $router) use ($group) {
                    require_once $group['file'];
                });
            }
        }
    }

    public function run()
    {
        $request = Request::createFromGlobals();
        $this->container->add('request', $request);

        $router = $this->container->get('router');

        try {
            $response = $this->resolveRequest($router, $this->container->get('request'));
        } catch (UrlMatchException $e) {
            $response = $this->makeResponse($e->getMessage(), $e->getCode());
        }

        // set session
        if ($request->getSession()) {
            /** @var Session $session */
            $session = $request->getSession();
            $session->write();
            $cookieParams = $session->getCookieParams();
            setcookie($session->getName(), $session->getId(), $cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'],
                $cookieParams['secure'], $cookieParams['httponly']);
        }

        $response->send();
    }

    /**
     * @param Request $request
     * @param Router $router
     * @return JsonResponse|Response
     * @throws Exception
     * @throws UrlMatchException
     */
    protected function resolveRequest(Router $router, Request $request)
    {
        $matchResult = $router->dispatch($request->getPathInfo(), $request->getMethod());
        return $this->resolveController($matchResult);
    }

    /**
     * @param mixed $data
     * @param int $status
     * @return JsonResponse|Response
     */
    protected function makeResponse($data, $status = 200)
    {
        /** @var Response $response */
        if($this->container->exists('response')){
            $response = $this->container->get('response');
        }else{
            $response = new Response();
        }
        if ($status == 200) {
            $status = $response->getStatusCode();
        }
        if ($data instanceof Response) {
            $response->setContent($data->getContent());
            $response->setStatusCode($data->getStatusCode());
        } else if ($data instanceof View) {
            $response->setContent(strval($data));
            $response->setStatusCode($status);
        } else if (is_array($data) || is_object($data)) {
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($data));
            $response->setStatusCode($status);
        } else {
            $response->setContent($data);
            $response->setStatusCode($status);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param array $middlewareList
     * @return mixed
     * @throws Exception
     */
    protected function filterMiddleware($request, $middlewareList)
    {
        if (empty($middlewareList)) {
            return null;
        }
        $middleware = array_shift($middlewareList);
        if (!class_exists($middleware)) {
            throw new Exception('Class ' . $middleware . ' is not exists!');
        }
        $middlewareObj = new $middleware();
        return $middlewareObj->handle($request, function ($request) use ($middlewareList) {
            return $this->filterMiddleware($request, $middlewareList);
        });
    }

    /**
     * @param array $matchResult
     * @return JsonResponse|Response
     * @throws Exception
     */
    protected function resolveController($matchResult)
    {
        /**
         * @var Router $router
         */
        //$router = $this->container->get('router');
        /** @var Route $route */
        $route = $matchResult['route'];
        $params = $matchResult['params'];

        $params = array_map(function ($param) {
            return urldecode($param);
        }, $params);

        $middlewareList = $route->getMiddleware();
        $request = $this->container->get('request');
        $result = $this->filterMiddleware($request, $middlewareList);
        if (!is_null($result)) {
            return $this->makeResponse($result);
        }

        try {
            /**
             * resolve controller
             */
            $action = $route->getAction();
            if ($action instanceof \Closure) {
                $data = $this->container->callFunction($action, $params);
                return $this->makeResponse($data);
            } else {
                $actionArr = explode('::', $action);
                $controllerName = $actionArr[0];
                if (!class_exists($controllerName)) {
                    throw new Exception("Controller class '$controllerName' is not exists!");
                }
                $methodName = isset($actionArr[1]) ? $actionArr[1] : null;
                $data = $this->container->callMethod($controllerName, $methodName, $params);
                return $this->makeResponse($data);
            }
        } catch (HttpException $e) {
            return $this->makeResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') === 0) { //get protected attribute
            $attribute = lcfirst(substr($name, 3));
            if (isset($this->$attribute)) {
                return $this->$attribute;
            }
        }
        throw new Exception('Call to undefined method ' . get_class($this) . '::' . $name . '()');
    }
}