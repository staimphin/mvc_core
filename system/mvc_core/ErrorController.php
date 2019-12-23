<?php


class ErrorController extends Controller
{
    public function index($content = 'Error 404: Not found')
    {
        $title ="Not found page";
        $this->set('title', $title);
        $this->set('content', $content);
        $this->render('404');
    }
}