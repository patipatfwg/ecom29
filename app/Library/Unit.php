<?php
namespace App\Library;

class Unit
{
    public function removeFirstInjection($text = '')
    {
        if (!empty($text)) {
            $character_remove = ['=' , '+' , '-' , '@'];
            foreach ($character_remove as $remove) {
                if (strpos($text, $remove) !== false && strpos($text, $remove) == 0) {
                    $text = str_replace_first($remove, "'".$remove, $text);
                    break;
                }
            }
        }

        return $text;
    }
}