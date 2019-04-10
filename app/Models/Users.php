<?php

namespace App\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Users extends BaseModel
{
   /**
     * connect db
     */
    //use SoftDeletes;
    protected $dates = ['deleted_at'];

	protected $connection = 'mongodb';

    /**
     * collection mongo
     */
    protected $collection = 'users';

}
