<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\GroupMenuRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ContentsRepository;
use App\Repositories\CampaignRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\BannerRepository;
use App\Http\Requests\GroupMenuRequest;
use App\Http\Requests\GroupHilightMenuRequest;
use DateTime;
class GroupMenuController extends \App\Http\Controllers\BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $redirect = [
		'login' => '/',
		'index' => 'group_menu'
	];

    protected $views = [
        'index'     => 'group_menu.index',
        'create'    => 'group_menu.create',
        'edit'      => 'group_menu.edit',
        'content'   => 'group_menu.content',
        'edit_menu' => 'group_menu.edit_menu'
    ];

    public function __construct(CampaignRepository $campaignRepository,ContentsRepository $contentRepository,PaymentRepository $paymentRepository,GroupMenuRepository $groupMenuRepository, CategoryRepository $categoryRepository, BannerRepository $bannerRepository)
    {
        parent::__construct();
        $this->messages             = config('message');
        $this->groupMenuRepository  = $groupMenuRepository;
        $this->categoryRepository   = $categoryRepository;
        $this->bannerRepository     = $bannerRepository;
        $this->paymentRepository     = $paymentRepository;
        $this->contentRepository    = $contentRepository;
        $this->campaignRepository   = $campaignRepository;
    }

    public function index()
    {
        return view($this->views['index']);
    }

    public function create()
    {
        return view($this->views['create'], [
            'language' => config('language.campaign'),
            'slug_input_name' => 'name_en',
            'editAble' => true
        ]);
    }

    public function store(GroupMenuRequest $request)
    {
        $inputs = $request->input();

        if (isset($inputs['status'])) {
            $inputs['status'] = 'active';
        } else {
            $inputs['status'] = 'inactive';
        }
        return $group_menuResult = $this->groupMenuRepository->createGroupMenu($inputs);
    }

    public function createHilightMenu(GroupHilightMenuRequest $request)
    {
        $inputs = $request->input();

        $this->extractHilightMenuData($inputs);

        return $group_menuResult = $this->groupMenuRepository->createGroupHilightMenu($inputs);
    }

    public function edit($id, Request $request)
    {
        $inputs = $request->input();
        $groupmenuData = $this->groupMenuRepository->getGroupMenusById($id);

        $editAble = true;
        $getConfigParams = [
                'config_type' => 'Group Menu',
                'start' => null,
                'length' => null
        ];
        $resultConfigData = $this->paymentRepository->getConfigs($getConfigParams);
        if(isset($resultConfigData['status'])&&$resultConfigData['status']['code']==200) {
            foreach($resultConfigData['data']['records'] as $config) {
                if($config['code']==$groupmenuData['slug']) {
                    $editAble = false;
                    break;
                }
            }
        }

        return view($this->views['edit'], [
            'language' => config('language.campaign'),
            'id' => $id,
            'group_id' => $id,
            'title' => $inputs['title'],
            'groupmenuData' => $groupmenuData,
            'editAble' => $editAble
        ]);
    }

    public function update(GroupMenuRequest $request)
    {
        $inputs = $request->input();

        if (isset($inputs['status'])) {
            $inputs['status'] = 'active';
        } else {
            $inputs['status'] = 'inactive';
        }

        return $this->groupMenuRepository->updateGroupMenu($inputs);
    }

    public function updateHilightMenu(GroupHilightMenuRequest $request)
    {
        $inputs = $request->input();

        $this->extractHilightMenuData($inputs);

        return $this->groupMenuRepository->updateHilightMenu($inputs);
    }

    protected function extractHilightMenuData(&$inputs)
    {
        if ($inputs['type'] == 'banner') {
            $inputs['value'] = $inputs['slug_banner'];
            unset($inputs['slug_banner']);
        }

        if (isset($inputs['status'])) {
            $inputs['status'] = 'active';
        } else {
            $inputs['status'] = 'inactive';
        }
    }

    public function destroy($id, Request $request)
    {
        $inputs = $request->input();
        $removableIds = [];
        if (!empty($inputs)) {
            return $this->groupMenuRepository->deleteGroupHilightMenu($id);
        } else {
            $ids = explode(',',$id);
            return $this->groupMenuRepository->deleteGroupMenu($id);
        }
    }

    public function addMenu($id, Request $request)
    {
        $inputs = $request->input();
        // Product Category List
        $type = 'product';
        $productCategoryCurlResult = $this->categoryRepository->getRootCategoryIncludeChild($type);
        $productCategoryList = [];
        foreach($productCategoryCurlResult as $productCategory) {
            $productCategoryData = [
                'id' => $productCategory['slug'],
                'level' => $productCategory['level'],
                'name' => [
                    'en' => $productCategory['name']['en'],
                    'th' => $productCategory['name']['th']
                    ],
            ];
            if(isset($productCategory['children'])){
                foreach($productCategory['children'] as $productCategoryChildren){
                    $children = [
                        'id' => $productCategoryChildren['slug'],
                        'level' => $productCategoryChildren['level'],
                        'name' => [
                            'en' => $productCategoryChildren['name']['en'],
                            'th' => $productCategoryChildren['name']['th']
                        ],
                    ];
                    $productCategoryData['children'][] = $children;
                }
            }
            $productCategoryList[] = $productCategoryData;
        }

        // Business Category List
        $type = 'business';
        $businessCategoryCurlResult = $this->categoryRepository->getRootCategoryIncludeChild($type);
        $businessCategoryList = [];
        foreach($businessCategoryCurlResult as $businessCategory) {
            $businessCategoryData = [
                'id' => $businessCategory['slug'],
                'level' => $businessCategory['level'],
                'name' => [
                    'en' => $businessCategory['name']['en'],
                    'th' => $businessCategory['name']['th']
                    ],
            ];
            if(isset($businessCategory['children'])){
                foreach($businessCategory['children'] as $businessCategoryChildren){
                    $children = [
                        'id' => $businessCategoryChildren['slug'],
                        'level' => $businessCategoryChildren['level'],
                        'name' => [
                            'en' => $businessCategoryChildren['name']['en'],
                            'th' => $businessCategoryChildren['name']['th']
                        ],
                    ];
                    $businessCategoryData['children'][] = $children;
                }
            }
            $businessCategoryList[] = $businessCategoryData;
        }

        $getCampaignParams = [
            'query' => [
                'offset' => 0,
                'limit' => 9999,
                'status' => "active"
            ],
            
        ];

        $campaignCurlResult = $this->campaignRepository->getCurlDataCampaign($getCampaignParams);
        $campaignList = [];

        if(isset($campaignCurlResult['status']['code']) && $campaignCurlResult['status']['code'] == 200) {
            foreach($campaignCurlResult['data']['records'] as $campaign) {
                $campaignData = [
                    'id'   => $campaign['slug'],
                    'name' => [
                        'th' => $campaign['campaign_code']
                    ]
                ];
                $campaignList[] = $campaignData;
            }
        }

        $getContentParams = [
            'start' => 0,
            'length' => 9999,
            'order' => [
                [
                    'dir' => 'false'
                ]
            ],
            'status' => "active"

        ];
        $contentCurlResult = $this->contentRepository->getCurlDataContent($getContentParams);
        $contentList = [];
        if(isset($contentCurlResult['status']['code']) && $contentCurlResult['status']['code'] == 200) {
            foreach($contentCurlResult['data']['records'] as $content) {
                $contentData = [
                    'id' => $content['slug'],
                    'name' => $content['name']
                ];
                $contentList[] = $contentData;
            }
        }

        // Banner List
        $getBannerParams = [
            'start' => 0,
            'length' => 9999
        ];
        $bannerList = $this->bannerRepository->getDataBanners($getBannerParams);

        if(is_null($bannerList) || empty($bannerList)){
            $bannerList = [];
        }

        return view($this->views['edit_menu'], [
            'productCategoryList'  => $productCategoryList,
            'businessCategoryList' => $businessCategoryList,
            'campaignList'         => $campaignList,
            'bannerList'           => $bannerList,
            'contentList'          => $contentList,
            'id'                   => $id,
            'title'                => $inputs['title'],
            'language'             => config('language.campaign')
        ]);
    }

    public function getData(Request $request)
    {
        $param = $request->input();
        return $this->groupMenuRepository->getGroupMenus($param);
    }

    public function content($id, Request $request)
    {
        $param = $request->input();
        return view($this->views['content'], [
            'language' => config('language.campaign'),
            'id' => $id,
            'title' => $param['title']
        ]);
    }

    public function getHilightMenu($id, Request $request)
    {
        $param = $request->input();
        return $this->groupMenuRepository->getHilightMenu($id, $param);
    }

    public function editMenu($id, $hilightid, Request $request)
    {
        $inputs = $request->input();
        // Product Category List
        $type = 'product';
        $productCategoryCurlResult = $this->categoryRepository->getRootCategoryIncludeChild($type);
        $productCategoryList = [];
        foreach($productCategoryCurlResult as $productCategory) {
            $productCategoryData = [
                'id' => $productCategory['slug'],
                'level' => $productCategory['level'],
                'name' => [
                    'en' => $productCategory['name']['en'],
                    'th' => $productCategory['name']['th']
                    ],
            ];
            if(isset($productCategory['children'])){
                foreach($productCategory['children'] as $productCategoryChildren){
                    $children = [
                        'id' => $productCategoryChildren['slug'],
                        'level' => $productCategoryChildren['level'],
                        'name' => [
                            'en' => $productCategoryChildren['name']['en'],
                            'th' => $productCategoryChildren['name']['th']
                        ],
                    ];
                    $productCategoryData['children'][] = $children;
                }
            }
            $productCategoryList[] = $productCategoryData;
        }

        // Business Category List
        $type = 'business';
        $businessCategoryCurlResult = $this->categoryRepository->getRootCategoryIncludeChild($type);
        $businessCategoryList = [];
        foreach($businessCategoryCurlResult as $businessCategory) {
            $businessCategoryData = [
                'id' => $businessCategory['slug'],
                'level' => $businessCategory['level'],
                'name' => [
                    'en' => $businessCategory['name']['en'],
                    'th' => $businessCategory['name']['th']
                    ],
            ];
            if(isset($businessCategory['children'])){
                foreach($businessCategory['children'] as $businessCategoryChildren){
                    $children = [
                        'id' => $businessCategoryChildren['slug'],
                        'level' => $businessCategoryChildren['level'],
                        'name' => [
                            'en' => $businessCategoryChildren['name']['en'],
                            'th' => $businessCategoryChildren['name']['th']
                        ],
                    ];
                    $businessCategoryData['children'][] = $children;
                }
            }
            $businessCategoryList[] = $businessCategoryData;
        }

        $getCampaignParams = [
            'query' => [
                'offset' => 0,
                'limit' => 16000,
                'status' => "active"
            ],
            
        ];

        $campaignCurlResult = $this->campaignRepository->getCurlDataCampaign($getCampaignParams);
        $campaignList = [];

        if(isset($campaignCurlResult['status']['code']) && $campaignCurlResult['status']['code'] == 200) {
            foreach($campaignCurlResult['data']['records'] as $campaign) {
                $campaignData = [
                    'id' => $campaign['slug'],
                    'name' => $campaign['name']
                ];
                $campaignList[] = $campaignData;
            }
        }

        $getContentParams = [
            'start' => 0,
            'length' => 16000,
            'order' => [
                [
                    'dir' => 'false'
                ]
            ],
            'status' => "active"
        ];
        $contentCurlResult = $this->contentRepository->getCurlDataContent($getContentParams);
        $contentList = [];
        if(isset($contentCurlResult['status']['code']) && $contentCurlResult['status']['code'] == 200) {
            foreach($contentCurlResult['data']['records'] as $content) {
                $contentData = [
                    'id' => $content['slug'],
                    'name' => $content['name']
                ];
                $contentList[] = $contentData;
            }
        }
         // Banner List
        $getBannerParams = [
            'start' => 0,
            'length' => 9999
        ];
        $bannerList = $this->bannerRepository->getDataBanners($getBannerParams);
        $groupHilightData = $this->groupMenuRepository->getHilightMenuById($hilightid);
        $kType = [
            'link_external'     => 'External Link',
            'link_internal'     => 'Internal Link',
            'banner'            => 'Banner',
            'campaign'          => 'Campaign',
            'business_category' => 'Business Category',
            'product_category'  => 'Product Category',
            'content'           => 'Content'
        ];
        $kTarget = [
            '_blank'    => 'Open in new window' ,
            '_self'     => 'Replace the current page content'
        ];
        return view($this->views['edit_menu'], [
            'productCategoryList' => $productCategoryList,
            'businessCategoryList' => $businessCategoryList,
            'campaignList' => $campaignList,
            'keyType'    => $kType,
            'keyTarget'  => $kTarget,
            'bannerList' => $bannerList,
            'contentList' => $contentList,
            'groupHilightData' => $groupHilightData,
            'id' => $id,
            'hilight_id' => $hilightid,
            'title' => $inputs['title'],
            'language' => config('language.campaign'),
        ]);
    }

    public function updateHilightMenuPriority(Request $request)
    {
        $params = $request->input();
		$data = [];

		foreach($params['priority'] as $key => $val){
            if($val != $params['priority_old'][$key]){
                $data[] = [
                    'id' => $key,
                    'priority' => $val
                    ];
            }
		}
        $result = $this->groupMenuRepository->updateHilightMenuPriority($data);
        return $result;
    }

    public function updateHilightMenuStatus($id, Request $request)
    {
        $params = $request->input();
        $updateData = [
            'ids' => $id,
            'status' => $params['status']
        ];
        return $this->groupMenuRepository->updateHilightMenuStatus($updateData);
    }

    public function updateGroupMenuStatus($id, Request $request)
    {
        $params = $request->input();
        $updateData = [
            'ids' => $id,
            'status' => $params['status']
        ];
        return $this->groupMenuRepository->updateGroupMenuStatus($updateData);
    }
    /**
	 * Method for report excel
	 */
	public function exportGroupMenu(Request $request)
	{
		$result = $this->groupMenuRepository->getGroupMenuReport($request->input());

		if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);

			return redirect($this->redirect['index']);
		}
	}

    public function exportGroupHilightMenu($id ,$title, Request $request)
	{
        $inputs = $request->input();
        $url = 'group_menu/'.$id.'/content?title='.$title;
		$result = $this->groupMenuRepository->getGroupHilightMenuReport($id,$inputs);

		if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);

			return redirect($url);
		}
	}
}
?>