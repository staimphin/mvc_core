<?php
/**
 * Class File
 * handle file I/O
 */

class File
{
    public $filename = 'log.log';
    public $basepath;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->basepath = LOG_PATH;
    }

    /*
     * Setters
     */
    /**
     *
     * @param $name
     */
    public function setFileName($name)
    {
        $this->filename = $name;
    }

    /**
     * Add a suffix to a filename before the extension (used when there's a duplicate)
     *
     * @param string $filename the original filename
     */
    public function renameFileWithDate($filename)
    {
        $tmp  = explode('.', $filename);
        $name = date("Y-m-d") .'_'. $tmp[0];
        $extension = $tmp[1];

        $this->setFileName("{$name}.{$extension}");
    }
    /**
     * create folder
     * @param $name
     * @param string $path
     */
    public function makeFolder($name, $path = '')
    {
        $target = ($path) ? "$path/$name" : $this->basepath."/$name";
        // path/ folder exist
        if(!file_exists($target)){
            //create
            mkdir($target, 0777, true);
        }
    }

    //write
    /**
     *  Save data
     * @param $content
     * @param string $target
     * @return bool|false|int
     */
    public function save($content, $target = '')
    {
        $filename = $this->basepath.($target ? $target : $this->filename);

        //Handle array:
        if (is_array($content)) {
            $content = implode(PHP_EOL, $content);
        }

        $destfile = fopen($filename,"w+");
        $op=fwrite( $destfile,$content);
        fclose( $destfile);
        return $op;
    }

    //check file
    /**
     * Checks if a file is an image
     *
     * Used by Upload_Manager::upload
     *
     * @param string $file the path of the file to test
     *
     * @return boolean true if the file is an image
     */
    public function isPicture($file)
    {
        return (substr_count(mime_content_type($file), 'image')) ? true : false;
    }

}