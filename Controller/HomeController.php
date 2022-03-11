<?php

class HomeController extends AbstractController
{
    /**
     * Home Page
     * @return void
     */
    public function index()
    {
        $this->render('home/home');
    }
}