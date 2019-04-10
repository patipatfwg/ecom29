<?php
namespace App\Models;

class PrintCounter extends BaseModel
{
    /**
     * connect db
     */
    protected $connection = 'mongodb';

    /**
     * collection mongo
     */
    protected $collection = 'printcounter';

}
