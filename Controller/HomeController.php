<?php

class HomeController extends AbstractController
{
    /**
     * Home Page
     * @return void
     */
    public static function index()
    {
        ArticleController::listAllArticles();
    }
}