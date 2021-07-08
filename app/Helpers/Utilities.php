<?php

namespace App\Helpers;

use Image;

class Utilities
{
    public function uniqueRandomNumber($number_of_digits = 0)
    {
        if ($number_of_digits > 0) {
            $code = substr(number_format(microtime(true) * mt_rand(), 0, '', ''), 0, $number_of_digits);
            if ($number_of_digits <= strlen($code)) {
                return substr($code, 0, $number_of_digits);
            }
            return $code . $this->random_num($number_of_digits - strlen($code));
        }

        return number_format(microtime(true) * mt_rand(), 0, '', '');
    }

    function random_num($length = 8)
    {
        $pool = '0123456789';

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
        }
        return $str;
    }


    public function getTransactionCode($prefix)
    {
        $code = $this->uniqueRandomNumber(6);
        return $prefix . $code;
    }


    public function randomCode()
    {
        return md5(uniqid(rand(), true));
    }

    public function resizeImage($folder, $image, $sizes = [])
    {
        //get default sizes
        if (count($sizes) == 0) {
            $sizes = [
                '150x150',
                '128x128'
            ];
        }

        $image_path = public_path() . '/uploads/' . $folder . '/' . $image;
        $thumbs_path = public_path() . '/uploads/' . $folder . '/' . 'thumbs';

        foreach ($sizes as $size) {
            $s = explode('x', $size);
            $width = $s[0];
            $height = $s[1];

            $name_arr = explode('.', $image);
            $thumb = $thumbs_path . '/' . $name_arr[0] . '-' . $width . 'x' . $height . '.' . $name_arr[1];

            $img = Image::make($image_path);
            $img->fit($width, $height, null, 'bottom-left')->save($thumb);
        }

    }

    public function getCodePrefix($merchant_name)
    {
        $prefix = '';
        if (!$merchant_name) {
            return '';
        }
        $words = explode(' ', $merchant_name);
        foreach ($words as $word) {
            if ($word) {
                $prefix .= $word[0];
            }
        }
        return substr($prefix, 0, 2);
    }
}
