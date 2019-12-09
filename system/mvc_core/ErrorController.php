<?php


class ErrorController extends Controller
{
    public function index()
    {
        $title ="Not found page";
        $content ="this is default content for 404";
        $this->set('title', $title);
        $this->set('content', $content);

        $this->render('404');
    }
}