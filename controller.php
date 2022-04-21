<?php

require_once "model.php";

class Controller
{
    private $model;

    public function __construct($model){
        $this->model = $model;
    }

    public function sort($sort=2, $page=0){
        return $this->model->SelectAll($sort, $page);
    }

    public function scroll($page=0){
        return $this->model->SelectAll(2, $page);
    }

    public function filtr($arr){
        return $this->model->filtr($arr);
    }

    public function add($arr){
        return $this->model->addBook($arr);
    }
}

