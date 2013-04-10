<?php


class queryTest extends PHPUnit_Framework_TestCase
{

    
    public function testGetter()
    {
        
        $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
        

        
        $fQuery=new FaceSql\Query\FQuery(A::getEntityFace());
        
        $fQuery->join("b")
               ->join("b.c")
               ->where("~a LIKE :name")
               ->bindValue(":name", "%A%");


        
        $j=$fQuery->execute($pdo);
        
        
        var_dump($j->errorInfo());
        $reader=new \FaceSql\Reader\QueryArrayReader($fQuery);
        var_dump($reader->read($j));
        
//        var_dump($j);
        
    }
    

 

}



class A{
    use \Face\Traits\EntityFaceTrait;
    
    protected $id;
    protected $a;
    protected $b;
    
    public static function __getEntityFace() {
        return [
            "sqlTable"=>"a_table",
            "elements"=>[
                
                "id"=>[
                    "propertyName"  =>  "id",
                    "type"          =>  "value",
                    "sql"=>[
                        "columnName"=> "id",
                        "isPrimary"   =>true
                    ]
                ],
                
                "idB"=>[
                    "type"          =>  "value",
                    "sql"=>[
                        "columnName"=> "id_b"
                    ]
                ],
                
                "a"=>[
                    "propertyName"  =>  "a",
                    "type"          =>  "value",
                    "sql"=>[
                        "columnName"=> "a_column"
                    ]
                ],
                "b"=>[
                    "propertyName"  =>  "b",
                    "type"          =>  "entity",
                    "class"         =>  "B",
                    "sql"=>[
                        "join"=> ["idB"=>"id"]
                    ]
                    
                ]
            ]
            
        ];
    }
    public function getA() {
        return $this->a;
    }

    public function setA($a) {
        $this->a = $a;
    }

    public function getB() {
        return $this->b;
    }

    public function setB($b) {
        $this->b = $b;
    }


}

class B{
    use \Face\Traits\EntityFaceTrait;
    
    protected $name;
    protected $c;
    
    public static function __getEntityFace() {
        return [
            "sqlTable"=>"b_table",
            
            "elements"=>[
                "id"=>[
                    "type"=>"value",
                    "sql"=>[
                        "columnName"=> "id"
                    ]
                ],
                "idC"=>[
                    "type"=>"value",
                    "sql"=>[
                        "columnName"=> "id_c"
                    ]
                ],
                "name"=>[
                    "propertyName"=>"name",
                    "type"=>"value",
                    "sql"=>[
                        "columnName"=> "name"
                    ]
                ],
                "c"=>[
                    "propertyName"=>"c",
                    "type"          =>  "entity",
                    "class"         =>  "C",
                    "sql"=>[
                        "join"=> ["idC"=>"id"]
                    ]
                ]
            ]
            
        ];
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }




    
}

class C{
    use \Face\Traits\EntityFaceTrait;
    
    protected $name;

    
    public static function __getEntityFace() {
        return [
            
            "sqlTable"=>"c_table",
            
            "elements"=>[
                "id"=>[
                    "type"=>"value",
                    "sql"=>[
                        "columnName"=> "id"
                    ]
                ],
                "name"=>[
                    "propertyName"=>"name",
                    "type"=>"value",
                    "sql"=>[
                        "columnName"=> "c_name"
                    ]
                ],
            ]
            
        ];
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }




    
}


?>
