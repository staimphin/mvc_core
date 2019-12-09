<?php
class HomeController extends Controller {

    public function index()
    {
        $title ="Home page";
        $content ="this is default content";
        $this->set('title', $title);
        $this->set('content', $content);
    	$this->render('home');
    }
}