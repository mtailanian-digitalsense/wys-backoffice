<?php

namespace App\Utils;
class CustomValidations
{
    public static function isFile($attribute, $value, $params, $validator) {
        $image = base64_decode(preg_replace('#^data:\w+/\w+;base64,#i', '', $value));
        $f = finfo_open();
        $result = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
        preg_match('#^data:(\w+/\w+);base64,#i', $value, $matches);
        return !in_array($result, ['application/x-empty','text/plain', 'application/octet-stream'])&& !empty($matches[1]) && $matches[1] == $result;
    }
    public static function maxFileSize($attribute, $value, $params, $validator) {
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
        return strlen($image)/1024 < $params[0];
    }

}
 