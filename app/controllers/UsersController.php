<?php
class UserController extends Controller {

    public function index()
    {
        $title ="User page";
        $content ="this is default content for User";
        $this->set('title', $title);
        $this->set('content', $content);

    	$this->render('index');
    }
}