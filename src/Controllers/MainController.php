<?php

namespace Forms\Controllers;

use Forms\Views\View;

class MainController
{
    public function index(): void
    {
        View::render('main', ['title' => 'Главная страница']);
    }
}
