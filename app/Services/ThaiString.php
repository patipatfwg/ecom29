<?php
namespace App\Services;


class ThaiString
{
    // public function __construct()
    // {
       
    // }

    // Convert a string to an array with multibyte string
    function getMBStrSplit($string, $split_length = 1){
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8'); 
        
        $split_length = ($split_length <= 0) ? 1 : $split_length;
        $mb_strlen = mb_strlen($string, 'utf-8');
        $array = array();
        $i = 0; 
        
        while($i < $mb_strlen)
        {
            $array[] = mb_substr($string, $i, $split_length);
            $i = $i+$split_length;
        }
        
        return $array;
    }

    // Get string length for Character Thai
    function getStrLenTH($string)
    {
        $array = $this->getMBStrSplit($string);
        $count = 0;

        $list = ['”'];
        foreach ($array as $value) {
            if (in_array($value, $list)) {
                $count += 1;
            } else {
               $ascii = ord(iconv("UTF-8", "TIS-620//IGNORE", $value ));
                if( !( $ascii == 209 ||  ($ascii >= 212 && $ascii <= 218 ) || ($ascii >= 231 && $ascii <= 238 )) ) {
                    $count += 1;
                }
            }
        }

        return $count;
    }
    /*
     * Get part of string for Character Thai
     * prarams
     * $string [String] text
     * $length [Int] length text
     * $fixed [Int] length row (null = infinity)
     */
    function addLineStringTH($string, $length, $fixed_row = null)
    {
        // remove string special
        $string = $this->removeStringSpecial($string);

        $str       = '';
        $index     = 0;
        $text_line = [];

        $array_str = explode(" ", $string);

        if (!empty($array_str)) {

            if (count($array_str) == 1) {

                $array_string   = $this->getMBStrSplit($array_str[0]);

                if (!empty($array_string)) {
                    foreach ($array_string as $oData) {
                        $str .= $oData;

                        if ($this->getStrLenTH($str) > $length) {
                            if ($fixed_row === null || ($index < ($fixed_row - 1))) {
                                $str = $oData;
                                $index++;
                            }
                        }
                        $text_line[$index] = $str;
                    }
                }
            } else {
                foreach ($array_str as $oData) {

                    if ($this->getStrLenTH($oData) > $length) {
                        $result    = $this->addLineStringTH($oData, $length);
                        if(!empty($result)) {
                            $text_line = array_merge($text_line , $result);
                            $index     = count($text_line) - 1;
                            $str       = end($result) . " ";
                        }
                    } else {
                        $str .= $oData . " ";

                        if ($this->getStrLenTH($str) > $length) {
                            if ($fixed_row === null || ($index < ($fixed_row - 1))) {
                                $str = $oData . " ";
                                $index++;
                            }
                        }
                        $text_line[$index] = $str;
                    }
                }
            }

        }

        return $text_line;

    }

    function checkSara($text){
         $ascii = ord(iconv("UTF-8", "TIS-620", $text ));
                if( 
                        $ascii == 209 ||
                        ($ascii >= 212 && $ascii <= 218 ) ||
                        ($ascii >= 231 && $ascii <= 238 ) 
               ){
                    return true;
                }
                return false;
    }
    function checkSara_last($text){
         $ascii = ord(iconv("UTF-8", "TIS-620", $text ));
                if( 
                    $ascii == 208 ||
                    $ascii == 210 ||
                    $ascii == 211 ||
                    $ascii == 229 ||
                    $ascii == 230 
                       
               ){
                    return true;
                }
                return false;
    }


    function ThaiDate($date){
        $convTime = \DateTime::createFromFormat('d/m/Y', $date);
        $date = $convTime->format('Y-m-d');
        $thaimonth = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"); 
        $thaidate = date('d',strtotime($date)).' '.$thaimonth[(date('m',strtotime($date))-1)].' พ.ศ. '.(date('Y',strtotime($date))+543);
        return $thaidate;
    }

    /*function num2wordsThai($num){   
        $num=str_replace(",","",$num);
        $num_decimal=explode(".",$num);
        $num=$num_decimal[0];
        $returnNumWord = "";   
        $lenNumber=strlen($num);   
        $lenNumber2=$lenNumber-1;   
        $kaGroup=array("","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน");   
        $kaDigit=array("","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า");   
        $kaDigitDecimal=array("ศูนย์","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า");   
        $ii=0;   
        for($i=$lenNumber2;$i>=0;$i--){   
            $kaNumWord[$i]=substr($num,$ii,1);   
            $ii++;   
        }   
        $ii=0;   
        for($i=$lenNumber2;$i>=0;$i--){   
            if(($kaNumWord[$i]==2 && $i==1) || ($kaNumWord[$i]==2 && $i==7)){   
                $kaDigit[$kaNumWord[$i]]="ยี่";   
            }else{   
                if($kaNumWord[$i]==2){   
                    $kaDigit[$kaNumWord[$i]]="สอง";        
                }   
                if(($kaNumWord[$i]==1 && $i<=2 && $i==0) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==6)){   
                    if($kaNumWord[$i+1]==0){   
                        $kaDigit[$kaNumWord[$i]]="หนึ่ง";      
                    }else{   
                        $kaDigit[$kaNumWord[$i]]="เอ็ด";       
                    }   
                }elseif(($kaNumWord[$i]==1 && $i<=2 && $i==1) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==7)){   
                    $kaDigit[$kaNumWord[$i]]="";   
                }else{   
                    if($kaNumWord[$i]==1){   
                        $kaDigit[$kaNumWord[$i]]="หนึ่ง";   
                    }   
                }   
            }   
            if($kaNumWord[$i]==0){   
                if($i!=6){
                    $kaGroup[$i]="";   
                }
            }   
            $kaNumWord[$i]=substr($num,$ii,1);   
            $ii++;   
            $returnNumWord.=$kaDigit[$kaNumWord[$i]].$kaGroup[$i];   
        }      
        if(isset($num_decimal[1])){
            $returnNumWord.="จุด";
            for($i=0;$i<strlen($num_decimal[1]);$i++){
                    $returnNumWord.=$kaDigitDecimal[substr($num_decimal[1],$i,1)];  
            }
        }       
        return $returnNumWord;   
    }*/

    // function getRowAddress($data,$length){
    //     $description = '';
    //     $description2 = '';
    //     $address = [];
    //     $row = 0;
    //     foreach($data as $oData){
    //         $description .= $oData . " "; 
    //         if(mb_strlen($description, 'UTF-8') < $length){
    //             $address[$row] = $description;
    //         }
    //         else{   
    //             $description2 .= $oData . " "; 
    //             $address[$row+1] = $description2;
    //         }
    //     }
    //     return $address;
    // }

    function getRowPromotionName($data, $length){
        $str = '';
        $row = 0;
        $promotion_name = [];

        foreach($data as $oData){
            $str .= $oData;

            if(mb_strlen($str, 'UTF-8') > $length){
                $str = '';
                $row++;
            }
            $promotion_name[$row] = $str;
        }
        return $promotion_name;
    }

    //Ref. https://github.com/Rundiz/number

    public $number = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่','ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
    public $number_scale = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
    
    // arabic and thai number
    public $arabic_number = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    public $thai_number = array('๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙');
    /**
     * convert from arabic number to thai number.
     * 
     * @param string $input string that contain number to convert
     * @return string return string with converted number.
     */
    public function arabicToThaiNumber($input)
    {
        $input = strval($input);
        
        return str_replace($this->arabic_number, $this->thai_number, $input);
    }// arabicToThaiNumber
    /**
     * convert Thai Baht number to text.
     * 
     * @param number $num input the money number. negative or positive.
     * @param boolean $display_net display net (ถ้วน). true to display, false to not display.
     * @return string return converted number to Thai Baht and Satang string.
     */
    public function num2wordsThai($num, $display_net = true)
    {
        // make input as string.
        $num = strval($num);
        if (strpos($num, '.') !== false) {
            list($num, $dec) = explode('.', $num);
        } else {
            $dec = 0;
        }
        $output = '';
        if ($num{0} == '-') {
            $output .= 'ลบ';
            $num = ltrim($num, '-');
        } elseif ($num{0} == '+') {
            $output .= 'บวก';
            $num = ltrim($num, '+');
        }
        if ($num{0} == '0') {
            $output .= 'ศูนย์';
        } else {
            $output .= $this->convertNumberWithScale($num);
        }
        $output .= 'บาท';
        if ($dec > 0) {
            // if there is decimal (.)
            $dec_str = '';
            
            // convert number normally for decimal.
            $dec_str = $this->convertNumberWithScale($dec);
            
            if ($dec_str != null) {
                $output .= $dec_str . 'สตางค์';
            }
        }
        
        if (!isset($dec_str) || (isset($dec_str) && $dec_str == null) && $display_net === true) {
            $output .= 'ถ้วน';
        }
        
        unset($dec, $dec_str);
        return $output;
    }// convertBaht
    /**
     * match number to text.
     * 
     * @param number $digit only one digit per request.
     * @return string return translated number for each digit requested.
     */
    public function convertDirectNum($digit)
    {
        if (isset($this->number[$digit])) {
            return $this->number[$digit];
        }
        return $digit;
    }// convertDirectNum
    /**
     * convert the number (and with dot).
     * 
     * @param number $num number integer or decimal. negative or positive.
     * @return string translated number to text in Thai language.
     */
    public function convertNumber($num)
    {
        // make input as string.
        $num = strval($num);
        if (strpos($num, '.') !== false) {
            list($num, $dec) = explode('.', $num);
        } else {
            $dec = 0;
        }
        $output = '';
        if ($num{0} == '-') {
            $output .= 'ลบ';
            $num = ltrim($num, '-');
        } elseif ($num{0} == '+') {
            $output .= 'บวก';
            $num = ltrim($num, '+');
        }
        if ($num{0} == '0') {
            $output .= 'ศูนย์';
        } else {
            $output .= $this->convertNumberWithScale($num);
        }
        if ($dec > 0) {
            // if there is decimal (.)
            $output .= 'จุด';
            if ($dec{0} == '0') {
                // first digit after dot is zero. read number directly
                for ($i = 0; $i < strlen($dec); $i++) {
                    $output .= $this->convertDirectNum($dec{$i});
                }
            } else {
                // read number normally.
                $output .= $this->convertNumberWithScale($dec);
            }
        }
        return $output;
    }// convertNumber
    /**
     * convert the number to text with scale. (ten, hundred, thousand, ...) in Thai language.
     * 
     * @param number $digits number only. no negative or positive sign. no dot.
     * @return string
     */
    private function convertNumberWithScale($digits)
    {
        $length_digit = strlen($digits);
        $count = 1;
        $pos = 0;// หลักเลข 1=หน่วย, 2=สิบ, 3=ร้อย, ...
        $output = '';
        $tmp_output = '';
        $tmp_output_scale = '';
        for($i=$length_digit-1; $i > -1 ; --$i) {
            if ($pos == 7) {
                $pos = 1;
            }
            $tmp_output = $this->convertDirectNum($digits{$i});
            if ($pos >= 0 && $digits{$i} == 0 && $length_digit > $count) {
                // หากหลักมากกว่าหน่วย และตัวเลขที่เจอเป็นศูนย์ ไม่ให้แสดงตัวอักษรคำว่าศูนย์ เพราะไม่อ่านสิบศูนย์ หรือ ร้อยศูนย์ศูนย์
                $tmp_output = '';
            } elseif ($pos == 1 && $digits{$i} == 1) {
                // หากเป็นหลักสิบ และตัวเลขที่เจอเป็น 1 ไม่ให้แสดงตัวอักษร คำว่า หนึ่ง เนื่องจากเราจะไม่อ่านว่า หนึ่งสิบ
                $tmp_output = '';
            } elseif ($pos == 1 && $digits{$i} == 2) {
                // หน่วยสิบ เลขคือ 2
                $tmp_output = 'ยี่';
            } elseif (($pos == 0 || $pos == 6) && $digits{$i} == 1 && $length_digit > $count) {
                // หากเป็นหลักหน่วย หรือหลักล้าน และตัวเลขที่พบคือ 1 และยังมีหลักที่มากกว่าหลักหน่วยปัจจุบัน ให้แสดงเป็น เอ็ด แทน หนึ่ง
                $tmp_output = 'เอ็ด';
            }
            if (isset($this->number_scale[$pos])) {
                // generate number scale (สิบ ร้อย พัน ...)
                $tmp_output_scale = $this->number_scale[$pos];
            }
            if ($digits{$i} == 0 && $pos != 6) {
                // ถ้าตัวเลขที่พบเป็น 0 และไม่ใช่หลักล้าน ไม่ให้แสดงอักษรของหลัก
                $tmp_output_scale = '';
            }
            $output = $tmp_output . $tmp_output_scale . $output;
            $count++;
            $pos++;
            $tmp_output = '';
            $tmp_output_scale = '';
        }
        unset($count, $i, $length_digit, $pos, $tmp_output, $tmp_output_scale);
        return $output;
    }// convertNumberWithScale
    /**
     * convert from thai number to arabic number.
     * 
     * @param string $input input string that contain number to convert.
     * @return string return string with converted number
     */
    public function thaiToArabicNumber($input)
    {
        $input = strval($input);

        return str_replace($this->thai_number, $this->arabic_number, $input);
    }// thaiToArabicNumber

    function removeStringSpecial($string)
    {
        $string = preg_replace("/\s|&nbsp;/",' ',$string);
        $string = htmlentities($string);
        $string = preg_replace("/\s|&nbsp;/",' ',$string);

        $output       = '';
        $array_string = $this->getMBStrSplit($string);

        if (!empty($array_string)) {
            foreach ($array_string as $value) {
                $ascii = ord(iconv("UTF-8", "TIS-620//IGNORE", $value));
                if ($ascii > 0) {
                    $output .= $value;
                }
            }
        }
        return $output;
    }
}