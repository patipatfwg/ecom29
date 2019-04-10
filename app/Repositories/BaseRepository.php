<?php

namespace App\Repositories;

class BaseRepository
{
    public function __construct()
    {

    }

    public function highlight($search, $value)
    {
        if (!empty($search)) {
            return str_replace($search, '<strong style="color:#FF8000">'. $search .'</strong>', $value);
        }

        return $value;
    }
}