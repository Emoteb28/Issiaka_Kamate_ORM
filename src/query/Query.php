<?php
namespace query;
use connection\ConnectionFactory;


/*
 * Ici nous écrivons la classe Query
 */
class Query {
    private $sqlTable;
    private $fields = '*';
    private $where = null;
    private $args = array();
    private $sql = "";

    /*
     * Fonction table
     */
    public static function table($table){
        $query = new Query;
        $query->sqlTable = $table;
        return $query;
    }

    /*
 * Fonction select
 */
    public function select(array $fields){
        $this->fields = implode(',',$fields);
        return $this;
    }
    public function where($col, $op, $val){
        if(!is_null($this->where)){
            $this->where .= ' AND '.$col.$op.'?';
            $this->args[] = $val;
        } else {
            $this->args[] = $val;
            $this->where = $col.$op.'?';
        }
        return $this;
    }

    /*
    * Fonction get
    */
    public function get(){
        if(isset($this->where)){
            $this->sql = 'SELECT '. $this->fields . ' FROM '. $this->sqlTable.' WHERE '.$this->where;
        } else {
            $this->sql = 'SELECT '. $this->fields . ' FROM '. $this->sqlTable;
        }
        $pdo = connectionFactory::getConnection();
        $rq = $pdo->prepare($this->sql);
        $rq->execute($this->args);
        return $rq->fetchAll(\PDO::FETCH_ASSOC);
    }

    /*
    * Fonction delete
    */
    public function delete(){
        if(!is_null($this->where)){
            $this->sql = 'DELETE FROM '. $this->sqlTable .' WHERE '.$this->where;
            $pdo = connectionFactory::getConnection();
            $rq = $pdo->prepare($this->sql);
            $rq->execute($this->args);
        }

    }    

    /*
     * Fontion insert
     */
    public function insert($tab){

        $tabKey = array();
        $tabValue = array();

        foreach ($tab as $key => $values){

            $tabKey[]= $key;
            $this->args[]= $values;
            $tabValue[]= '?';

        }
        $stringKey = implode(",",$tabKey);
        $stringValue = implode(",",$tabValue);

        $this->sql = "INSERT INTO ".$this->sqlTable." ($stringKey) VALUES ($stringValue)";

        $pdo = connectionFactory::getConnection();
        $rq = $pdo->prepare($this->sql);
        $rq->execute($this->args);

        
        return $pdo->lastInsertId();      
    }

    /*
     * Fontion update
     */
   public function update($tab){
       $tabKey = array();
       $tabValue = array();
       foreach ($tab as $key => $values){
           $tabKey[]= "$key=?";
           $this->args[]= $values;
       }  
       $tabReverse = array_reverse($this->args);
       $set = implode(',',$tabKey);
       $this->sql = 'UPDATE '.$this->sqlTable.' SET '.$set.' WHERE '.$this->where;
      $pdo = connectionFactory::getConnection();
       $rq = $pdo->prepare($this->sql);
       $rq->execute($tabReverse);
   }
}