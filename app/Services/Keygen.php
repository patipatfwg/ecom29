<?php namespace App\Services;

class Keygen
{
    protected $alphabet;
    protected $alphabetPlatformContact;

    public function __construct()
    {
        $this->alphabet                = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $this->alphabetPlatformContact = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        // $this->alphabetPlatformContact = 'abcdefghij';
    }

    public function encode($input, $length = 8)
    {
        $key      = '';
        $input    = md5($input).$length;
        $input    = substr($input, 0, $length);
        $alphabet = $this->alphabet . $this->alphabet;

        foreach (str_split($input) as $s) {
            $alphabet  .= $this->alphabet;
            $int_alpha  = ord($s);
            $alphabet   = substr($alphabet, $int_alpha);
            $key       .= substr($alphabet, 0, 1);
        }

        return $key;
    }

    public function encodePlatformContact()
    {
        $sixdigit = substr(time(), 4);
        $last     = substr($sixdigit, -1, 1);
        $checksum = 0;

        for ($i = 0; $i < 6; $i++) {
            $checksum += ($sixdigit[$i] % 2);
        }

       return $this->alphabetPlatformContact[$last].$sixdigit.$checksum;
    }
}