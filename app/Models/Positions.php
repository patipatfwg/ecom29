<?php

namespace App\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Positions extends BaseModel
{
   /**
     * connect db
     */
	protected $connection = 'mongodb';

    /**
     * collection mongo
     */
    protected $collection = 'positions';

}
