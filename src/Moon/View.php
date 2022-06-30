<?php
/**
 * User: heropoo
 * Datetime: 2022/7/1 1:20 上午
 */

namespace Moon;



/**
 * Class View
 * @package Moon
 */
class View
{
    protected $viewFile;
    protected $data;
    protected $viewPath;
    protected $layout;

    public $title;

    public function __construct($viewFile, $data = [], $layout = null, $viewPath = null)
    {
        $baseViewPath = \App::$instance->getRootPath() . '/views';
        $viewPath = is_null($viewPath) ? $baseViewPath : $baseViewPath . '/' . $viewPath;

        $this->viewFile = $viewFile;
        $this->data = $data;
        $this->viewPath = $viewPath;
        $this->layout = $layout;
    }

    public function getViewPath()
    {
        return $this->viewPath;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * render a view
     * @return string
     */
    public function render()
    {
        $content = $this->renderPart($this->viewFile, $this->data);
        if ($this->layout) {
            return $this->renderPart($this->layout, ['content' => $content]);
        }
        return $content;
    }

    /**
     * render a part of view
     * @param string $view
     * @param array $data
     * @return string
     */
    public function renderPart($view, array $data = [])
    {
        $viewFile = $this->viewPath . '/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View file `$viewFile` is not exists");
        }

        ob_start();
        extract($data);
        include $viewFile;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function e($var)
    {
        return htmlspecialchars($var);
    }

    public function toString()
    {
        return $this->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}