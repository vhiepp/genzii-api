<?php

namespace App\Helpers;

class FileHelper
{
    public function saveMedia($file, string $uid = '__')
    {
        $folder = 'public/images/' . $uid . '/media';
        $filename = $file->hashName();
        $url = '/storage/images/' . $uid . '/media/' . $filename;
        $file->store($folder);
        return $url;
    }
}
