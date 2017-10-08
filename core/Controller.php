<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 1:23
 */

namespace core;

/**
 * Class Controller
 * @package application\core
 *
 * Base controller
 */
class Controller
{
    /**
     * Layout name
     * @var string
     */
    public $layout = 'main';

    /**
     * @var View
     */
    protected $view;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Init controller
     */
    public function init()
    {
        $this->view = new View($this->layout);
    }

    /**
     * @param $viewName
     * @param array $params
     * @throws \core\Exception
     */
    public function render($viewName, array $params = [])
    {
        $controllerName = mb_strtolower(App::$instance->controllerName);
        $viewFile = $controllerName . '/' . $viewName . '.php';
        $content = $this->view->renderFile($viewFile, $params);
        $this->view->renderPage($content);
    }

    /**
     * @param $route
     * @param array $params
     */
    public function redirect($route, $params = [])
    {
        header( 'Location: ' . Route::createUrl($route, $params));
        exit;
    }

    /**
     * Redirect to Home Page
     */
    public function goHome()
    {
        header( 'Location: /');
        exit;
    }

    /**
     * Default action
     */
    public function actionIndex()
    {
    }
}