<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 4:08
 */

namespace core;

/**
 * Class View
 * @package application\core
 */
class View
{
    /**
     * @var string
     */
    public $layoutName = 'main';

    /**
     * @var string
     */
    protected $layoutsPath = APP_PATH . 'views/layouts/';

    /**
     * @var string
     */
    protected $viewsPath = APP_PATH . 'views/';

    /**
     * View constructor.
     * @param string $layout
     */
    public function __construct($layout = null)
    {
        $this->layoutName = $layout;
    }

    /**
     * @param string $viewFile
     * @param array $params
     * @return string
     * @throws \core\Exception
     */
    public function renderFile($viewFile, array $params = [])
    {
        $viewFileName = $this->viewsPath . $viewFile;

        if (!file_exists($viewFileName)) {
            throw new Exception('View "' . $viewFile . '" is not found');
        }

        if (is_array($params)) {
            extract($params, EXTR_SKIP);
        }

        ob_start();
        require $viewFileName;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Layout + content
     * @param string $content
     * @throws \core\Exception
     */
    public function renderPage($content = '')
    {
        $layoutFileName = $this->layoutsPath . $this->layoutName . '.php';

        if (!file_exists($layoutFileName)) {
            throw new Exception('Layout ' . $this->layoutName . ' is not found');
        }

        require_once $layoutFileName;
    }
}