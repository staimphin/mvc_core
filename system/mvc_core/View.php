<?php

/**
 * Class View
 *
 * View manager
 */
class View
{
    protected $layout = 'Default';
    /**
     * Display page
     * @param $folder
     * @param $file_name
     * @param array $view_data
     */
	public function render($folder, $file_name, $view_data = array())
	{
	    extract($view_data);
		//should use layout instead
        include_once VIEW_COMMON_PATH.'head.php';

        $layoutName = "{$this->layout}Layout.php";
        $layout =  $this->checkLayout($layoutName);

        include_once $layout;
	}

	public function template($file_name, $is_main =0)
    {
        if (file_exists($file_name)) {
            return $file_name;
        }

        return null;
    }

    /**
     * Check if the
     */
    protected function checkTemplate($folder, $file_name)
    {
        $path = VIEW_PATH."{$folder}/{$file_name}.php";
        if (file_exists($path)) {
            return $path;
        }
        $path = BASE_VIEW_PATH."{$folder}/{$file_name}.php";
        if (file_exists($path)) {
            return $path;
        }
        return BASE_VIEW_PATH."missing_view.php";
    }

    protected function checkLayout($file_name)
    {
        $path = LAYOUT_PATH."{$file_name}.php";
        if (file_exists($path)) {
            return $file_name;
        }
        $path = BASE_VIEW_PATH."{$file_name}.php";
        if (file_exists($path)) {
            return $file_name;
        }
        return BASE_VIEW_PATH."DefaultLayout.php";
    }

    public function setLayout($layout = 'Default')
    {
        $this->layout = $layout;
    }
}