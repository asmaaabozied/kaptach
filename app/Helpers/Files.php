<?php

namespace App\Helpers;

use Image;

class Files
{

    /**
     * upload and resize image
     * @param $image from request
     * @param string $path without / at first and end
     * @param int $width
     * @param string $oldImage , specify it to overwrite
     * @return string
     */
    public function uploadAndResizeImage($image, $path = 'uploads', $width = 200, $oldImage = null)
    {
        //create directory if not exists
        $this->createPath($path, true);

        //delete old image if exists
        if ($oldImage) {
            $this->deleteImage($path, $oldImage);
        }
        $file_name = 'img-' . time() . '.' . $image->extension();
        Image::make($image)->save(public_path() . '/' . $path . '/' . $file_name)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path() . '/' . $path . '/thumbs/' . $file_name);
        return $file_name;
    }

    /*
     * delete image with its thumbnail
     * @param $path
     * @param $image
     */
    public function deleteImage($path, $image)
    {
        if (!$image) {
            return false;
        }
        //delete thumb
        $this->deleteFile($path . '/thumbs', $image);
        //delete original image
        $this->deleteFile($path, $image);
        $image_sizes = [
            '150x150'
        ];
        //delete all thumbs if they are exists
        foreach ($image_sizes as $size) {
            $img = explode('.', $image);
            $thumb = $img[0] . '-' . $size . '.' . $img[1];
            $this->deleteFile($path . '/thumbs', $thumb);
        }
    }

    public function deleteFile($path, $file)
    {
        try {
            $file = public_path() . '/' . $path . '/' . $file;

            if ($file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        } catch (\Exception $ex) {

        }
    }


    public function saveBase64Image($image64, $path = 'uploads', $resize = false, $width = 200)
    {
        //create the path if it doesn't exist
        $this->createPath($path);
        if (!str_contains($image64, 'base64')) {
            $image64 = 'data:image/jpeg;base64,' . $image64;
        }
        $image = Image::make($image64);
        //get extension
        $mime = $image->mime();  //edited due to updated to 2.x
        if ($mime == 'image/jpeg')
            $extension = '.jpg';
        elseif ($mime == 'image/png')
            $extension = '.png';
        elseif ($mime == 'image/gif')
            $extension = '.gif';
        else
            $extension = '.jpeg';

        $name = 'img-' . rand(1, 999) . time() . $extension;
        //upload image
        $image->save($path . '/' . $name);
        if ($resize) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/thumbs/' . $name);
        }

        return $name;
    }

    public function saveBase64ImageWithName($image64, $name, $path = 'uploads', $resize = false, $width = 200)
    {
        //create the path if it doesn't exist
        $this->createPath($path);
        if (!str_contains($image64, 'base64')) {
            $image64 = 'data:image/jpeg;base64,' . $image64;
        }
        $image = Image::make($image64);
        //get extension
//        $mime = $image->mime();  //edited due to updated to 2.x
//        if ($mime == 'image/jpeg')
//            $extension = '.jpg';
//        elseif ($mime == 'image/png')
//            $extension = '.png';
//        elseif ($mime == 'image/gif')
//            $extension = '.gif';
//        else
//            $extension = '.jpeg';

        $name = $name . '.png';
        //upload image
        $image->save($path . '/' . $name);
        if ($resize) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/thumbs/' . $name);
        }

        return $name;
    }

    protected function createPath($path, $thumbFolder = false)
    {
        if (!is_dir($path)) {
            //Directory does not exist, so lets create it.
            mkdir($path, 0755, true);
            if ($thumbFolder)
                mkdir($path . '/thumbs', 0755, true);
        }
    }
}
