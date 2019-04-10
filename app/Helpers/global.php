<?php
function alert($data, $type = 0)
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	if ($type == 1) {
		die();
	}
}

function convertToDisplayWords($snake_case)
{
	return ucwords(str_replace('_', ' ', $snake_case));
}

function slug_generate($text)
{
	// replace non letter or digits by -
	$text = preg_replace('~[^\pL\d]+~u', '-', $text);

	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);

	// trim
	$text = trim($text, '-');

	// remove duplicate -
	$text = preg_replace('~-+~', '-', $text);

	// lowercase
	$text = strtolower($text);

	if (empty($text)) {
		return '';
	}

	return $text;
}

function slug_validate($text)
{
	$validate = "/^[A-Za-z0-9]+(?:(\-|\_)[A-Za-z0-9]+)*$/";

	return preg_match($validate, $text);
}

function trans_append_language($text,$lang)
{
	return trans($text) . ' (' . strtoupper($lang) . ')';
}

function convertDateTime($data, $from, $to)
{
	if (!empty($data) && !empty($from) && !empty($to)) {
	    $date = DateTime::createFromFormat($from, $data);
	    return $date->format($to);
	}

	return '';
}
function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}