<?php

require 'model.php';
require 'controller.php';
echo ("<link rel='stylesheet' href='style.css'>");
echo ("<script ENGINE=\"text/javascript\" src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>");
echo ("<script type=\"text/javascript\" src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js?ver=1.6.4'></script>");
echo("<script src=\"JavaScript.js\" type=\"text/javascript\"></script>");
class View
{
    private $model;
    private $controller;
    public function __construct($controller,$model) {
        $this->controller = $controller;
        $this->model = $model;
    }

    public function output($model) {
        $returnTable = "<table class=\"table\" id=\"table\"> <caption> Таблица книг </caption> <tr id=\"caption\"> <th >Название книги <button id=\"NameBook\" 
            value=\"0\">∇</button></th><th>Жанр</th><th>Год выпуска <button id=\"YearBook\" value=\"0\">∇</button></th><th>Автор(ы)</th></tr>";
        $handlerModel=$model;
        foreach($handlerModel as $row){
            $returnTable=$returnTable."<tr><td>".$row["Name"]."</td><td>".$row["Genre"]."</td><td>".$row["Year"]."</td><td>".$row["Author"]."</td></tr>";
        }
        return $returnTable;
    }
}


$model = new Book();
$controller = new Controller($model);
$view = new View($controller, $model);
//echo( $view->output($model));
//echo $model->SelectAll();
if (!empty($_GET)){
    if ($_GET["By"]=="Name"){
        $varAddSort=$controller->sort($_GET["Sort"], $_GET["Page"]);
        echo( $view->output($varAddSort));
        
    }
    elseif($_GET["By"]=="Year"){
        $varAddSort=$controller->sort($_GET["Sort"], $_GET["Page"]);
        echo( $view->output($varAddSort));
    }
    elseif($_GET["By"]=="Page"){
        $varAddScroll=$controller->scroll($_GET["Page"]);
        echo( $view->output($varAddScroll));
    }
    elseif($_GET["By"]=="Filter"){
        $arrFiltr=array(
            'Author'=>$_GET["Author"],
            'Genre'=>$_GET["Genre"],
            'Year'=>$_GET["Year"],
        );
        $varAddFilter=$controller->filtr($arrFiltr);
        echo ($view->output($varAddFilter));
    }
    elseif($_GET["By"]=="Reset"){
        $varAddReset=$controller->sort(2,0);
        echo( $view->output($varAddReset));
    }
    elseif($_GET["By"]=="Add"){
        $arrAdd=array(
            'Name'=>$_GET["addName"],
            'Genre'=>$_GET["addGenre"],
            'Year'=>$_GET["addYear"],
            'Author'=>$_GET["addAuthor"],
        );
        $varAddBook=$controller->add($arrAdd);
    }
}
else{
    $varAddSort=$controller->sort();
    echo( $view->output($varAddSort));
}

