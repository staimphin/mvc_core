<?php

/**
 * Class View
 *
 * View manager
 */
class View
{
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
		include_once $this->template(VIEW_PATH."{$folder}/{$file_name}.php");

        include_once VIEW_COMMON_PATH.'footer.php';
	}

	private function template($file_name)
    {
        if (file_exists($file_name)) {
            return $file_name;
        } else {
            return VIEW_PATH."missing_view.php";
        }
    }
}