<?php

namespace App\Classes\Tools;

use Exception;

class ImageResizer
{
    /*
     *  parametres: orig => image d'origine
     *              dest => image de destination
     *              et les dimensions
     */
    public static function resize(string $origin, string $destination, int $width, int $maxHeight): bool
    {
        $type = FileExtensionRetriever::getExtension($origin);
        $pngFamily = ['PNG', 'png'];
        $jpegFamily = ['jpeg', 'jpg', 'JPG'];
        if (in_array($type, $jpegFamily)) {
            $type = 'jpeg';
        } elseif (in_array($type, $pngFamily)) {
            $type = 'png';
        }
        $function = 'imagecreatefrom' . $type;

        if (!is_callable($function)) {
            return false;
        }

        $image = $function($origin);

        $imageWidth = \imagesx($image);
        if ($imageWidth < $width) {
            if (!copy($origin, $destination)) {
                throw new Exception("Impossible de copier le fichier {$origin} vers {$destination}");
            }
        } else {
            $imageHeight = \imagesy($image);
            $height = (int) (($width * $imageHeight) / $imageWidth);
            if ($height > $maxHeight) {
                $height = $maxHeight;
                $width = (int) (($height * $imageWidth) / $imageHeight);
            }
            $newImage = \imagecreatetruecolor($width, $height);

            if ($newImage !== false) {
                \imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);

                $function = 'image' . $type;

                if (!is_callable($function)) {
                    return false;
                }

                $function($newImage, $destination);

                \imagedestroy($newImage);
                \imagedestroy($image);
            }
        }

        return true;
    }
}
