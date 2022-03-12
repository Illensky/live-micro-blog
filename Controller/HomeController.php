<?php

class HomeController extends AbstractController
{
    /**
     * Home Page
     * @return void
     */
    public static function index()
    {
        self::render('home/home');
    }
}