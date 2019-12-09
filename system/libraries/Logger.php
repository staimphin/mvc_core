<?php
/*
 * class for login data
 */

class Logger extends File
{

    public function log($data)
    {
        $this->renameFileWithDate('log.log');
        $this->save(print_r($data, true));
    }
}