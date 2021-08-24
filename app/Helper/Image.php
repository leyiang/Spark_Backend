<?php

namespace App\Helper;

use GdImage;

class Image {

    const PATH_URL        = 0;
    const PATH_FILESYSTEM = 1;

    const Types = [
        IMAGETYPE_GIF => "gif",
        IMAGETYPE_JPEG => "jpeg",
        IMAGETYPE_PNG => "png",
        IMAGETYPE_WEBP => "webp"
    ];

    public static function type( $file ) {
        $type = exif_imagetype( $file );

        if( ! isset( self::Types[ $type ] ) ) {
            return null;
        }

        return self::Types[ $type ];
    }

    public static function getImageByPath( $path, $type ) {
        return call_user_func( "imagecreatefrom". $type, $path );
    }

    public static function saveImageByPath( $image, $path, $type ) {
        return call_user_func("image" . $type, $image, $path );
    }

    public static function getImageMD5( $image, $type ) {
        $path = storage_path("tmp" . DIRECTORY_SEPARATOR . "tmp." . $type );
        Image::saveImageByPath( $image, $path, $type );

        // Get MD5
        $result = md5_file( $path );
        // Remove File
        unlink( $path );
        // return
        return $result;
    }

    public static function crop( $path, $size, $type ) {
        // get file
        $image   = Image::getImageByPath( $path, $type );

        // crop
        $cropped = imagecrop( $image, $size );

        if( ! $cropped ) throw new \Exception();

        // get cropped filename
        $md5 = Image::getImageMD5($cropped, $type);
        $filename = Image::getFileName( $md5, $type );

        // Save Image
        Image::saveImageByPath( $cropped, Image::path( $filename, Image::PATH_FILESYSTEM ), $type );

        // Destroy tmp files
        imagedestroy( $image );
        imagedestroy( $cropped );

        return $filename;
    }

    public static function path( $filename, $type=self::PATH_URL ) {
        $folder = "images";
        $separator = $type === self::PATH_URL ? "/" : DIRECTORY_SEPARATOR;
        $path = $folder . $separator . $filename;

        switch ( $type ) {
            case self::PATH_URL:
                return url( $path );

            case self::PATH_FILESYSTEM:
                return public_path( $path );

            default:
                return null;
        }
    }

    public static function getFileName( $name, $type ) {
        return $name . "." . $type;
    }

    public static function upload( $image ) {
        // Check Type
        $type = Image::type( $image );

        // Get File Name
        $md5 = md5_file( $image->getRealPath() );
        $filename = Image::getFileName( $md5, $type );

        // Save
        $image->move( public_path("images"), $filename );

        // Return
        return [$type, $filename];
    }
}
