<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 1:00
 */

namespace core;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SimpleLogger\File;
use SimpleLogger\Logger;

/**
 * Class App
 * @package core
 */
class App
{
    /**
     * @var App
     */
    public static $instance;

    /**
     * @var string
     */
    public static $baseUrl;

    /**
     * @var \mysqli
     */
    public static $db;

    /**
     * @var Session
     */
    public static $session;

    /**
     * @var LoggerInterface
     */
    public static $logger;

    /**
     * @var array
     */
    public $config = [];

    /**
     * @var string
     */
    public $controllerName;

    /**
     * @var string
     */
    public $actionName;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    protected $controllerNamespace = 'application\controllers';

    /**
     * App constructor.
     * @param $config
     * @throws \core\Exception
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->init();
    }

    /**
     * Init app
     * @throws \core\Exception
     */
    public function init()
    {
        self::$instance = $this;
        self::$baseUrl = $this->config['baseUrl'];

        $file = new File($this->config['logFile']);
        $file->setLevel(LogLevel::INFO);
        self::$logger = new Logger();
        self::$logger->setLogger($file);

        (new ErrorHandler())->init();

        self::$db = (new Database())->init($this->config['components']['db']);

        self::$session = new Session();
        self::$session->open();

        $this->user = new User();
        $this->user->init();

        // Defaults
        $this->controllerName = 'Site';
        $this->actionName = 'index';
    }

    /**
     * Run app
     * @throws \Exception
     */
    public function run()
    {
        $route = Route::parseRequest();

        if (!empty($route[0])) {
            $this->controllerName = mb_convert_case($route[0], MB_CASE_TITLE, 'UTF-8');
        }

        if (!empty($route[1])) {
            $this->actionName = mb_convert_case($route[1], MB_CASE_TITLE, 'UTF-8');
        }

        $controllerClassName = $this->controllerNamespace . '\\' . $this->controllerName . 'Controller';

        if (!class_exists($controllerClassName)) {
            throw new Exception('Controller "' . $this->controllerName . '" not found in ' . $this->controllerNamespace);
        }

        $controller = new $controllerClassName;
        $actionName = 'action' . $this->actionName;

        if (!method_exists($controller, $actionName)) {
            throw new Exception('Action  "' . $this->actionName . '" not found in ' . $controllerClassName);
        }

        $controller->$actionName();
    }
}