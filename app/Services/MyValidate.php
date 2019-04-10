<?php 

namespace App\Services;

use Validator;

class MyValidate
{

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function manageError($validate)
    {
        // Define validate output
        $result  = [];

        if ( $validate->fails() ) {
            foreach ($validate->messages()->all() as $message) {
                $result = array('error' => $message);
                break;
            }
        }
        return $result;

    }

    private function setDefault($inputs, $defualts)
    {
        foreach ($defualts as $key => $value) {
            if (empty($inputs[$key])) {
                $inputs[$key] = $value;
            }
        }
        return $inputs;
    }

    public function validator($inputs, $rules, $defaults)
    {
        // Validate input
        $validate = Validator::make($inputs, $rules);
        // Get message if error
        $validateRes = $this->manageError($validate);

        if (isset($validateRes['error'])) {
            return $validateRes;
        }

        return $this->setDefault($inputs, $defaults);

    }

}
