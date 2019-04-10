<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Response;

class VersionController extends BaseController
{
	public function getVersionAction()
	{
		exec('git tag', $tag);
		exec("git branch | sed -n '/\* /s///p'", $branch);
        exec('git log -1', $line);

        $line = $this->toKeyValue($line);
        $line['tag'] = isset($tag[0]) ? $tag[0] : '';
		$line['current_branch'] = isset($branch[0]) ? $branch[0] : '';

        return $this->output([$line]);
    }

    public function toKeyValue($data)
    {
    	$version = [];

        if (!empty($data)) {
    	    foreach ($data as $kLine => $vLine) {

			    if($kLine === 0) {

				    $pieces = explode(' ', $vLine, 2);
				    $version[strtolower($pieces[0])] = trim($pieces[1]);

			    } else {

				    $pieces = explode(':', $vLine, 2);
				    switch (count($pieces)) {
					    case 2:
						    $version[strtolower($pieces[0])] = trim($pieces[1]);
						    break;
					    case 1:
						    $version['comment'] = trim($pieces[0]);
						    break;
				    }
			    }
		    }
        }

		return $version;
    }

    public function output($output = [])
	{
		return [
			'data' => $output
		];
	}
}
