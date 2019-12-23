<?php
/**
 * Class Controller
 * Master controller
 */
class Controller extends Core{
	protected $View;
	protected $Model;
	protected $vars = array();

	public function __construct()
	{
        parent::__construct();
        $this->View = new View;
        $this->Model = new Model;
	}

    /**
     * Call the view manager and pass data to the view
     * @param $view_name
     */
    public function render($view_name)
    {
        $model = str_replace('Controller',  '', get_class($this));
        $this->View->render($model, $view_name, $this->vars);
    }

    /**
     * Set data for the view
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
    	$this->vars[$key] = $value;
    }

    public function useLayout($layout)
    {
        $this->View->setLayout($layout);
    }
    /**
     * retrieve a specific data set for the view
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
    	return $this->vars[$key];
    }

    /**
     * retrieve all the data currently for the view
     * @return array
     */
    public function getAll()
    {
    	return $this->vars;
    }
}