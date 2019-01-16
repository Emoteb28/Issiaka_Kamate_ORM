<?php
namespace model;
use model\Model;

/*
 * On rajoute une fonction Categorie à la classe Model
 */
class Categorie extends Model
{

    protected static $table = 'categorie';
    protected static $primary_key = 'id';

    public function articles(){

        return $this->has_many('\model\Article','id_categ');
    }

}