<?php

namespace App\Classes\Tools;

class FileExtensionRetriever
{
    public static function getExtension(string $origin): string
    {
        return pathinfo($origin, PATHINFO_EXTENSION);
    }
}
