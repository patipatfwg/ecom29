<?php
namespace App\Repositories;

use App\Models\Positions;

class PositionsRepository
{
	public function getImgUrl($params)
	{
		$model = new Positions();
		$position = $model->where('type', '=', $params['type'])->get();
		return $position;
	}
}