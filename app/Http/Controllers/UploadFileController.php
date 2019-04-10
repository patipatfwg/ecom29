<?php
namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class UploadFileController extends \App\Http\Controllers\BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public $allowed = [
        'png', 'jpg'
    ];

    public function images(Request $request)
    {
        $result = [
            'url'     => '',
            'message' => 'Error Upload File'
        ];

        //get callback function CKEditor
        $funcNum = $request->get('CKEditorFuncNum');

        if ($request->hasFile('upload')) {

            $result = [
                'url'     => '',
                'message' => 'Image type must be .jpg or .png format'
            ];

            //get image
            $image = $request->file('upload');
            //get extension image
            $extension = $image->getClientOriginalExtension();

            //check extension image
            if(in_array($extension, $this->allowed)) {

                //get cdn service
                $cdbService = App::make('App\Services\CdnServices');

                try {

                    $url = $cdbService->_uploadFileData('upload');

                    $result = [
                        'url'     => $url,
                        'message' => ''
                    ];

                } catch (\Exception $e) {

                    $result = [
                        'url'     => '',
                        'message' => 'Error Upload File CDN'
                    ];
                }
            }
        }

        echo "<script type='text/javascript'>
            window.parent.CKEDITOR.tools.callFunction(" . $funcNum . ", '" . $result['url'] . "', '" . $result['message'] . "');
            window.parent.$('.cke_dialog_ui_fileButton').removeClass('loading');    
            window.close();
        </script>";
    }
}
?>