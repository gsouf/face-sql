<?php

namespace FaceSql\Reader;

use \FaceSql\Query\FQuery;
use FaceSql\Reader\InstancesKeeper;

/**
 * Description of QueryArrayReader
 *
 * @author bobito
 */
class QueryArrayReader {
    
    /**
     *
     * @var \FaceSql\Query\FQuery
     */
    protected $FQuery;
    /**
     *
     * @var InstancesKeeper
     */
    protected $instancesKeeper;
            
    function __construct(\FaceSql\Query\FQuery $FQuery) {
        $this->FQuery = $FQuery;
        $this->instancesKeeper=new InstancesKeeper();
    }

    
    public function read(\PDOStatement $stmt){
        
        $faceList = $this->FQuery->getAvailableFaces();
        
        $faceList = array_reverse($faceList);
        
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            foreach($faceList as $basePath=>$face){
                /* @var $face \Face\Core\EntityFace */
                $identity=$this->getIdentityOfArray($face, $row, $basePath);
                if($this->instancesKeeper->hasInstance($face->getClass(), $identity)){
                    $instance = $this->instancesKeeper->getInstance($face->getClass(), $identity);
                }else{
                    $instance = $this->createInstance($face, $row, $basePath, $faceList);
                    $this->instancesKeeper->addInstance($instance, $identity);
                }

            }
        }
        
        return $this->instancesKeeper;
        
    }
    
    public function createInstance(\Face\Core\EntityFace $face,$array,$basePath, $faceList){
        
        $className = $face->getClass();
        $instance  = new $className();
        
        foreach($face as $element){
            /* @var $element \Face\Core\EntityFaceElement */

            if($element->isValue()){
                $value=$array[$this->FQuery->_doFQLTableName($basePath.".".$element->getName())];
                $instance->faceSetter($element,$value);
            }else{
                $identity = $this->getIdentityOfArray($element->getFace(),$array,$basePath);
                if(isset($faceList[$basePath.".".$element->getName()]) && $this->instancesKeeper->hasInstance($element->getClass(), $identity) ){
                    $childInstance = $this->instancesKeeper->getInstance($element->getClass(), $identity);
                    $instance->faceSetter($element,$childInstance);
                }else{
                    
                    // TODO 
                }
                    // TODO reverse instances setter
                    // TODO forward instances not set
                    // TODO array of value
                    // TODO array of instances
            }
        }
        
        return $instance;
    }
    
    public function getIdentityOfArray(\Face\Core\EntityFace $face,$array,$basePath){
        $primaries=$face->getPrimaries();
        $identity="";
        
        
        foreach($primaries as $elm){
            /* @var $elm \Face\Core\EntityFaceElement */
            
            $identity.=$array[$this->FQuery->_doFQLTableName($basePath.".".$elm->getName())];
        }
        
        return $identity;
    }
    
    
}

?>
