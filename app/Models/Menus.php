<?php
namespace App\Models;

use App\Models\BaseModel;

class Menus extends BaseModel
{
    /**
     * connect db
     */
	protected $connection = 'mongodb';

    /**
     * collection mongo
     */
    protected $collection = 'menus';

    /**
     * time db
     */
    public $timestamps = false;

    /**
     * where parent
     */
    public function scopeGetParents($query)
    {
        return $query->where('parent', '=', 0)->orderBy('order', 'ASC')->get();
    }

    /**
     * relation hasMany children
     */
    public function children()
    {
        return $this->hasMany('App\Models\Menus', 'parent')->orderBy('order', 'ASC');
    }

    /**
     * relation belongsTo parents
     */
    public function parents()
    {
    	return $this->belongsTo('App\Models\Menus', 'parent');
    }
}
