<?php
namespace model;
//use model\Model;

/*
 * On rajoute une fonction Article à la classe Model
 */
class Article extends Model
{
    protected static $table = 'article';
    protected static $primary_key = 'id';

    public function categorie(){

        return $this->belongs_to('\model\Categorie','id_categ');
    }
}