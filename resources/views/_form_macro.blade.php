<?php

echo '<style>
    .form-horizontal .form-group {
        margin-right: 0px;
        margin-left: 0px;
    }
    .symbol.required:before {
        content: "*";
        display: inline;
        color: #E6674A;
    }
    .dataTables_length { padding-top: 5px !important; }
    .select2-selection--single { padding: 0 !important; }
    .select2-selection--single .select2-selection__arrow::after { right: 10px; }
    .select2-search--dropdown::after { left: 50px !important; }
    .dataTable thead .sorting::before { content: "" !important; }
</style>';

/**
 * =======================================
 * ======= Custom Inputs (myInput) =======
 * =======================================
*/

// Text
Form::macro('myText', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='text', $name, $title, $value, $attr, $specific);
});

// Password
Form::macro('myPassword', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='password', $name, $title, $value, $attr, $specific);
});

// Email
Form::macro('myEmail', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='email', $name, $title, $value, $attr, $specific);
});

// Checkbox
Form::macro('myCheckbox', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='checkbox', $name, $title, $value, $attr, $specific);
});

// Radio
Form::macro('myRadio', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='radio', $name, $title, $value, $attr, $specific);
});

// Number
Form::macro('myNumber', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='number', $name, $title, $value, $attr, $specific);
});

// File
Form::macro('myFile', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='file', $name, $title, $value, $attr, $specific);
});

// Select
Form::macro('mySelect', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='select', $name, $title, $value, $attr, $specific);
});

// Select Range
Form::macro('mySelectRange', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='selectRange', $name, $title, $value, $attr, $specific);
});

// Select Month
Form::macro('mySelectMonth', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='selectMonth', $name, $title, $value, $attr, $specific);
});

// Textarea
Form::macro('myTextarea', function ($name, $title, $value=null, array $attr=[], $specific=null) {
    return mainInput($type='textarea', $name, $title, $value, $attr, $specific);
});


/**
 * [myInput : create new input, it have label and bootstrap markup]
 * @param  string       $type     [type of input ex. text, select, radio, checkbox, etc.]
 * @param  string       $name     [attribute name of input]
 * @param  string       $title    [attribute name of input]
 * @param               $value    [attribute value of input]
 * @param  array        $attr     [add other attribute of input]
 * @param  array|bool   $specific [specific option of some input
 *                                      select      = list : ['0'=>'Bangkok', '1'=>'Chiang Mai']
 *                                      checkbox    = checked : true | false
 *                                ]
 * @return [object]               [new input]
 */
function mainInput ($type='text', $name, $title, $value=null, array $attr=[], $specific=null)
{
    // Create label
    $title = $title . ((in_array('required', $attr)) ? ' <span class="symbol required"></span>' : '');
    $label = Form::myLabel($name, $title, ['class' => 'col-sm-2 control-label font-bold']);
    
    // Create input
    $input = genInput($type, $name, $value, $attr, $specific);

    // Gen html
    $html  = '<div class="form-group row">';
    $html .= $label;
    switch ($type) {
        case 'file':
            $html .=
                '<div class="fileupload fileupload-new" data-provides="fileupload">' .
                    '<div class="col-sm-10 input-group">' .
                        '<div class="form-control">' .
                            '<i class="fa fa-file fileupload-exists"></i>' .
                            '<span class="fileupload-preview"></span>' .
                        '</div>' .
                        '<div class="input-group-btn">' .
                            '<div class="btn btn-light-grey btn-file">' .
                                '<span class="fileupload-new"><i class="fa fa-folder-open-o"></i> Select file</span>' .
                                '<span class="fileupload-exists"><i class="fa fa-folder-open-o"></i> Change</span>';
            $html .= $input;
            $html .=        '</div>' .
                            '<a href="#" class="btn btn-light-grey fileupload-exists" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>' .
                        '</div>' .
                    '</div>' .
                '</div>' .
            '</div>';
            break;
        default:
            $html .= $input;
            $html .= '</div>';
            break;
    }

    return $html;
}

function genInput ($type, $name, $value, $attr, $specific=null)
{
    // Mapping type and function
    $groupTypeText = ['date', 'datetime', 'datetime-local', 'month', 'time', 'week', 'tel', 'url', 'search', 'color'];
    $typeForGenInput = (in_array($type, $groupTypeText)) ? 'text' : $type;

    // Set html attibute for input
    $isClass = (isset($attr['class'])) ? true: false;
    switch ($typeForGenInput) {

        case 'file':
            $classDefault = 'file-input';
            break;

        default:
            $classDefault = 'form-control';
            break;
    }
    $attr['class'] = ($isClass) ? $classDefault . ' ' . $attr['class'] : $classDefault;

    // Gen Input
    switch ($typeForGenInput) {

        case 'select':
            $attr['class'] = $attr['class'] . ' selectpicker';
            $input = Form::select($name, $specific, $value, $attr); // specific => array_list
            break;

        case 'radio':
        case 'checkbox':
            $input = Form::$type($name, $value, $specific, $attr); // specific => (true = checked)
            break;

        case 'file':
            $input = Form::file($name, $attr);
            break;

        default:
            $input = Form::$typeForGenInput($name, $value, $attr);
            break;
    }

    // Replace original type
    if ( in_array($type, $groupTypeText) ) {
        $input = str_replace('type="text"', 'type="' . $type .'"', $input);
    }

    // Add style date range picker"
    if (($isClass) && ( strpos($attr['class'], 'daterangepicker') || strpos($attr['class'], 'datepicker') )) {
        $input =
                '<div class="input-group row">' .
                    $input .
                    '<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>' .
                '</div>';
    }

    $input = 
        '<div class="col-sm-10 ">' . 
            $input . 
        '</div>';

    return $input;
}

/**
 * =======================================
 * ========= Custom Label (myLabel) ========
 * =======================================
*/
Form::macro('myLabel', function($name, $value=null, $attr=[]) {
    $label = Form::label($name, '%s', $attr);

    return sprintf($label, $value);
});

/**
 * =======================================
 * ========= Custom View (myView) ========
 * =======================================
*/

Form::macro('myView', function ($name, $title, $value, $attr=null)
{
    $input = genInput('text', $name, $value, ['disabled']);

    $html = '<div class="form-group">';
    $html .= '<label for="' . $name . '" class="col-sm-2 control-label">' . $title . ' <span class="symbol"></span></label>';
    $html .= $input;
    $html .= '</div>';

    return $html;
});


/**
 * =======================================
 * =========== Helper Function ===========
 * =======================================
*/

/**
 * [array_lists : format array lists for input selection]
 * @param  array  $arrs     [array index "id" and "name"]
 * @return array  $lists    [array of lists for input selection ]
 *
 * ===== Before =====
 *
 * [
 *     [
 *         "id" => "gearman_normal"
 *         "name" => "Normal"
 *     ],
 *     [
 *         "id" => "gearman_high"
 *         "name" => "High"
 *     ]
 * ]
 *
 * ===== After =====
 * [
 *     'gearman_normal' => 'Normal',
 *     'gearman_high' => 'High'
 * ]
 *
 */
function array_lists(array $arrs = [])
{
    $lists = [];
    if (isset($arrs) && !empty($arrs)) {
        foreach ($arrs as $arr) {
            $lists[$arr['id']] = $arr['name'];
        }
    }

    return $lists;
}
