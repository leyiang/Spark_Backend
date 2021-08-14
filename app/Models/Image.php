<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class Image extends Model {
    use HasFactory;

    protected static $TypeTable = [
        IMAGETYPE_GIF => "gif",
        IMAGETYPE_JPEG => "jpeg",
        IMAGETYPE_PNG => "png",
    ];

    public static function getType( $file ) {
        $type = exif_imagetype( $file );

        if( ! isset( self::$TypeTable[ $type ] ) ) {
            throw ValidationException::withMessages([
                "image" => "type invalid"
            ]);
        }

        return self::$TypeTable[ $type ];
    }

    public static function fetch( $uri ) {
        // Store image as tmp file
        $tmp = storage_path( "tmp/" . time() . ".tmp" );
        file_put_contents( $tmp, file_get_contents($uri) );

        // Get File Type and MD5
        $md5  = md5_file( $tmp );
        $type = self::getType( $tmp );

        // Set correct extension
        $filename = $md5 . "." . $type;
        File::move( $tmp, storage_path("tmp/" . $filename) );

        return $filename;
    }
}
