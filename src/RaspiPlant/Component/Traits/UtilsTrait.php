<?php

namespace RaspiPlant\Component\Traits;

/**
 * Trait UtilsTrait
 * @package RaspiPlant\Component\Traits
 */
trait UtilsTrait
{

    /**
     *
     * @param string $string
     * @return boolean|string
     */
    protected function makeSlug($string)
    {
        $replaced = preg_replace('~[^\pL\d]+~u', '-', $string);
        $translited = iconv('utf-8', 'us-ascii//TRANSLIT', $replaced);
        $transReplaced = preg_replace('~[^-\w]+~', '', $translited);
        $trimmed = trim($transReplaced, '-');
        $trimReplaced = preg_replace('~-+~', '-', $trimmed);
        $slug = strtolower($trimReplaced);

        if (empty($slug)) {
            return false;
        }

        return $slug;
    }

    /**
     * @return string
     */
    protected function randomKey($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}
