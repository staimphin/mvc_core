<?php
class AdminController extends Controller {

    public function index()
    {
        $title ="Admin page";
        $content ="this is default content for Admin page";
        $this->set('title', $title);
        $this->set('content', $content);
    	$this->render('index');
    }
}