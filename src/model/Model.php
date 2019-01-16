<?php
namespace model;
use query\Query;

/*
 * Squelette class Model
 */
abstract class Model
{
    private $attr_tab = array();
    public function __construct($tab = [])
    {
        $this->attr_tab = $tab;
    }
    public function __get($attr_nom){
        if(array_key_exists($attr_nom,$this->attr_tab)){
            return $this->attr_tab[$attr_nom];
        } elseif (method_exists($this,$attr_nom)){
            return $this->$attr_nom();

        } else {

            throw new \Exception("L'attr_tab n'existe pas");
        }

    }

    public function __set($attr_nom, $valeur_nom)
    {

        $this->attr_tab[$attr_nom] = $valeur_nom;
    }
    public function delete(){
        Query::table(static::$table)->where(static::$primary_key,'=',$this->id)->delete();

    }
    public function insert(){

        $this->id = Query::table(static::$table)->insert($this->attr_tab);
    }

    public static function objectModel($tab){
        $objectTab = array();
        foreach ($tab as $k => $v){
            $object = new static();
            foreach ($v as $k2 => $v2){
                $object->$k2 = $v2;
            }
            $objectTab[]=$object;
        }
        return $objectTab;
    }
    public static function all(){

        $tab = Query::table(static::$table)->get();
        return self::objectModel($tab);
    }
    public static function find($id, array $tab = []){
        if(empty($tab)){
            if(is_array($id)){
                if(is_array($id[0])){
                    $found = Query::table(static::$table);
                    foreach ($id as $k => $v){
                        $found = $found->where($v[0],$v[1],$v[2]);
                    }
                $found = $found->get();
                return self::objectModel($found);
                } else {
                    $found = Query::table(static::$table)->where($id[0],$id[1],$id[2])->get();
                }
            } else {
                $found = Query::table(static::$table)->where('id','=',$id)->get();
            }
        } else {
            if(is_array($id)){
                $found = Query::table(static::$table)->select($tab)->where($id[0],$id[1],$id[2])->get();
            } else {
                $found = Query::table(static::$table)->select($tab)->where('id', '=', $id)->get();
            }
        }
        $tab = $found;
        return self::objectModel($tab);
    }
    public static function first($id, array $tab = []){
        $found = self::find($id,$tab);
        return $found[0];
    }

    protected function belongs_to($model,$foreignKey){
        $id = static::$primary_key;
        $found = self::first([static::$primary_key,'=',$this->$id],[$foreignKey]);
        $instance = new $model();
        $res = $instance::first([$instance::$primary_key,"=",$found->$foreignKey]);
        return $res;
    }
    protected function has_many($model,$foreignKey){
        $id = static::$primary_key;
        $found = self::first([static::$primary_key,'=',$this->$id],[static::$primary_key]);
        $articles = new $model();
        $res = $articles::find([$foreignKey,'=',$found->$id]);
        return $res;
    }
}