<?php
namespace App\Library;

class Nestable
{
    /**
     * Method for conversion json to array
     */
    public function parseJsonArray($jsonArray = [], $parent = 0)
    {
        $return = [];

        if (is_array($jsonArray) && !empty($jsonArray)) {

            foreach($jsonArray as $subArray) {

                if (isset($subArray['id'])) {

                    $subArrayData = [];

                    if (isset($subArray['children'])) {
                        $subArrayData = $this->parseJsonArray($subArray['children'], $subArray['id']);
                    }

                    $return[] = [
                        'id'     => $subArray['id'],
                        'parent' => $parent
                    ];

                    $return = array_merge($return, $subArrayData);
                }
            }
        }

        return $return;
    }
}