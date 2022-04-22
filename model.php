<?
class Book{

    public $string;
    protected $mysql;

    public function __construct(){

        $this->string = "";

    }

    private function CreateQuerry($orderBy, $page, $task){

        if ($task==0){
            
            if ($orderBy==2){
                $query="SELECT b.*, a.* FROM list_books b INNER JOIN book_author ba ON ba.book_id = b.ID 
                    INNER JOIN list_authors a ON a.NameAuthor = ba.author_name WHERE b.ID>".($page*10)." AND b.ID<".(($page+1)*10+1);
                return $query;
            }
            elseif ($orderBy==1){
                $query="SELECT b.*, a.* FROM list_books b INNER JOIN book_author ba ON ba.book_id = b.ID 
                    INNER JOIN list_authors a ON a.NameAuthor = ba.author_name WHERE b.ID>".($page*10)." AND b.ID<".(($page+1)*10+1)." ORDER BY NameBook";
                return $query;
            }
            elseif ($orderBy==0){
                $query="SELECT b.*, a.* FROM list_books b INNER JOIN book_author ba ON ba.book_id = b.ID 
                    INNER JOIN list_authors a ON a.NameAuthor = ba.author_name WHERE b.ID>".($page*10)." AND b.ID<".(($page+1)*10+1)." ORDER BY NameBook DESC";
                return $query;
            }
            elseif ($orderBy==3){
                $query="SELECT b.*, a.* FROM list_books b INNER JOIN book_author ba ON ba.book_id = b.ID 
                    INNER JOIN list_authors a ON a.NameAuthor = ba.author_name WHERE b.ID>".($page*10)." AND b.ID<".(($page+1)*10+1)." ORDER BY YearOfPublicationBook DESC";
                return $query;
            }
            elseif ($orderBy==4){
                $query="SELECT b.*, a.* FROM list_books b INNER JOIN book_author ba ON ba.book_id = b.ID 
                    INNER JOIN list_authors a ON a.NameAuthor = ba.author_name WHERE b.ID>".($page*10)." AND b.ID<".(($page+1)*10+1)." ORDER BY YearOfPublicationBook";
                return $query;
            }
        }

    }

    public function filtr($arr){
        $this->mysql = new mysqli("127.0.0.1", "root", "root", "books");
        $query="SELECT b.ID FROM list_books b INNER JOIN book_author ba ON ba.book_id = b.ID 
        INNER JOIN list_authors a ON a.NameAuthor = ba.author_name";
        if ($arr["Author"] !=""){
            $query=$query." and a.NameAuthor=\"".$arr["Author"]."\"";
        }
        if ($arr["Genre"]!=""){
            $query=$query." and b.GenreBook=\"".$arr["Genre"]."\"";
        }
        if ($arr["Year"]!=""){
            $query=$query." and b.YearOfPublicationBook=".$arr["Year"];
        }
        $intermediateQuery=$this->mysql->query($query);
        $resObj=array();
        $per=0;
        foreach($intermediateQuery as $rowMid){
            $queryLast="SELECT b.*, a.* FROM list_books b INNER JOIN book_author ba ON ba.book_id = b.ID 
            INNER JOIN list_authors a ON a.NameAuthor = ba.author_name WHERE b.ID=".$rowMid["ID"];
            $rowUnion=$this->mysql->query($queryLast);
            if($per!=$rowMid["ID"]){
                foreach($rowUnion as $row)
                {
                    $obj=array(
                        "ID"=>$row['ID'],
                        "Name"=>$row['NameBook'],
                        "Genre"=>$row['GenreBook'],
                        "Year"=>$row['YearOfPublicationBook'],
                        "Author"=>$row['NameAuthor'],
                    );
                    if (empty($resObj)){
                        array_push($resObj, $obj);
                    }
                    else{
                        $_isAutor=end($resObj);
                        if($_isAutor['ID']==$obj['ID'] ){
                            $resObj[count($resObj)-1]['Author']=$resObj[count($resObj)-1]['Author'].", ".$obj['Author'];
                            
                        }
                        else{
                            array_push($resObj, $obj);
                        }
                    }
                }
            }
            $per=$rowMid["ID"];
        }
        return $resObj;
    }

    public function SelectAll($orderBy=2, $page=0){

        $this->mysql = new mysqli("127.0.0.1", "root", "root", "books");
        $resultQuery=$this->mysql->query($this->CreateQuerry($orderBy,$page, 0));
        $resObj=array();
        foreach($resultQuery as $row)
        {
            $obj=array(
                "ID"=>$row['ID'],
                "Name"=>$row['NameBook'],
                "Genre"=>$row['GenreBook'],
                "Year"=>$row['YearOfPublicationBook'],
                "Author"=>$row['NameAuthor'],
            );
            if (empty($resObj)){
                array_push($resObj, $obj);
            }
            else{
                $_isAutor=end($resObj);
                if($_isAutor['ID']==$obj['ID']){
                    $resObj[count($resObj)-1]['Author']=$resObj[count($resObj)-1]['Author'].", ".$obj['Author'];
                }
                else{
                    array_push($resObj, $obj);
                }
            }
            
        }
        return $resObj;



    }

    public function addbook($arr){
        $this->mysql = new mysqli("127.0.0.1", "root", "root", "books");
        $queryBook="INSERT INTO `list_books` (`ID`, `NameBook`, `GenreBook`, `YearOfPublicationBook`) VALUES (NULL, '".$arr['Name']."', '".$arr['Genre']."', '".$arr['Year']."')";
        $this->mysql->query($queryBook);
        $returnSql=$this->mysql->query("SELECT MAX(book_id) FROM book_author ");
        foreach($returnSql as $row){
            echo($row["MAX(book_id)"]+1);
            $queryBookAuthor="INSERT INTO `book_author` (`book_id`, `author_name`) VALUES ('".($row["MAX(book_id)"]+1)."', '".$arr['Author']."');";
            $this->mysql->query($queryBookAuthor);
        }
    }
}
