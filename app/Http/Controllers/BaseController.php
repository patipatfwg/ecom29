<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;

class BaseController extends Controller
{
    public $position = [
        'page' => '',
        'title' => ''
    ];
    protected $userId;

    public function __construct()
    {

    }

    public function setDefaultSEO_Subject($seo_subject,$name_en,$name_th)
    {
        if(empty($seo_subject) && !empty($name_en)) {
			$seo_subject = $name_en;
		}
		else if(empty($seo_subject) && !empty($name_th)) {
			$seo_subject = $name_th;
		}
        return $seo_subject;
    }

    public function setDefaultSEO_Explanation($seo_explanation,$description_en,$description_th)
    {
		if(empty($seo_explanation) && !empty($description_en)) {
			$seo_explanation = substr(strip_tags($description_en),0,170);
		}
		else if(empty($seo_explanation) && !empty($description_th)) {
			$seo_explanation = substr(strip_tags($description_th),0,170);
		}
        return $seo_explanation;
    }

    public function position(Request $request, $position)
    {
        $header = $request->header();
        if (!array_key_exists('referer', $header)) {
            $referUrl = '';
            $referLabel = '';
        }
        else {
            $referUrl = $header['referer'][0];
            $path = explode('/', $referUrl);
            $referLabel = $path[count($path) - 1];
            if ($referLabel == 'create')
            {
                $referLabel = 'Create';
            }
            else
            {
                $referLabel = 'Edit';
            }
        }
        $params = ['type' => $position];
        $images = $this->positionsRepository->getImgUrl($params);
        $imgUrl = '';
        foreach ($images as $key => $image) {
            $imgUrl[$key] = $image->imgUrl;
        }
        return view('img_position.position', [
            'referUrl' => $referUrl,
            'page' => $this->position['page'],
            'title' => $this->position['title'],
            'referLabel' => $referLabel,
            'imgUrl' => $imgUrl,
        ]);
    }
}
