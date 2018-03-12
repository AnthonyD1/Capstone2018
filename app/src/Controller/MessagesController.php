<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class MessagesController extends AppController
{
    public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Messages->find());
        $this->set(compact('articles'));
    }
}