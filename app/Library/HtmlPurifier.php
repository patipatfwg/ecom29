<?php
namespace App\Library;
use Purifier;

class HtmlPurifier
{
    public function removeXSS($params)
    {
        if (!empty($params) && is_array($params)) {
            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    $params[$key] = $this->removeXSS($value);
                } else {
                    $params[$key] = Purifier::clean(htmlspecialchars_decode($value), array('Attr.EnableID' => true));
                }
            }
        } else {
            $params = Purifier::clean(htmlspecialchars_decode($params), array('Attr.EnableID' => true));
        }

        return $params;
    }
}