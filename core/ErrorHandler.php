<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 2:09
 */

namespace core;

/**
 * Class ErrorHandler
 * @package application\core
 */
class ErrorHandler
{
    /**
     * Init error handler
     */
    public function init()
    {
        ini_set('display_errors', 1);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * @param \Exception|\Throwable $exception
     * @throws \core\Exception
     */
    public function handleException(\Throwable $exception)
    {
        App::$logger->error($exception->getMessage());
        App::$logger->debug($exception->getTraceAsString());
        $view = new View();
        $view->layoutName = 'error';
        $view->renderPage($view->renderFile('exception.php', [
            'exception' => $exception,
        ]));
        exit();
    }
}