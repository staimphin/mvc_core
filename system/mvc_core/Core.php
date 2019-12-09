<?php


class Core
{
    public $log;

    public function __construct()
    {
        $this->log = new Logger;
    }

    /**
     * Magic method
     * Prevent: PHP Fatal error: Call to undefined method
     * Logs undefined functions
     * @param $name
     * @param $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        $call_not_found = "Not found: class:".get_class($this)." Function {$name}  Args:"    . implode(', ', $arguments). "\n";
        $this->log->log($call_not_found);

        return false;
    }

    /**
     * Magic method
     * Prevent: PHP Fatal error: Call to undefined method
     * Logs undefined functions
     * @param $name
     * @param $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments)
    {
        $call_not_found = "Static call Not found: class: Function {$name}  Args:"    . implode(', ', $arguments). "\n";
        $log = new Logger;
        $log->log($call_not_found);

        return false;
    }

}