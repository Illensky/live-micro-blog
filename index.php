<?php

require 'vendor/autoload.php';

use App\Model\Manager\RoleManager;
use App\Model\Manager\ArticleManager;
use App\Model\Manager\CommentManager;
use App\Model\Manager\UserManager;
use App\Model\Manager\UserRoleManager;

$roleManager = new RoleManager();
dump($roleManager->getAll());

$articleManager = new ArticleManager();
dump($articleManager->getAll());