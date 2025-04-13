<?php

namespace Afpa\Controllers;

use Afpa\Core\Controller;
use Afpa\Models\ArticleDAO;

class ArticleController extends Controller 
{
    public function index()
    {
        
        $articles = ArticleDAO::getAll();

        $data = compact("articles");

        $this->render('ArticlesList', $data);
    }
}