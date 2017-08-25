<?php
namespace App\NanaSystem;

class Common

{
    public static function convertEOL($string, $to = "<br/>")
    {
        return preg_replace("/\r\n|\r|\n/", $to, $string);
    }
}