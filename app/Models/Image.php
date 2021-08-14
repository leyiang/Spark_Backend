<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class Image extends Model {
    use HasFactory;

    protected $guarded = [];

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
        $tmp = public_path( "tmp" . DIRECTORY_SEPARATOR . time() . ".tmp" );
        file_put_contents( $tmp, file_get_contents($uri) );

        // Get File Type and MD5
        $md5  = md5_file( $tmp );
        $type = self::getType( $tmp );

        // Set correct extension
        $filename = $md5 . "." . $type;
        File::move( $tmp, public_path("tmp" . DIRECTORY_SEPARATOR . $filename) );

        return [ $filename, $type ];
    }

    public function exists() {
        return file_exists( $this->path(true) );
    }

    public function path( $filesystem = false ) {
        $folder = $this->published ? DIRECTORY_SEPARATOR . "images" : DIRECTORY_SEPARATOR . "tmp";
        $file = $folder . DIRECTORY_SEPARATOR . $this->file;

        if( $filesystem ) {
            return public_path( $file );
        }

        return url( $file );
    }

//    public function getFile() {
//        return
//    }
    public function crop( $info ) {
        $image = imagecreatefromjpeg( $this->path(true ) );
        $result = imagecrop( $image, $info );

        if( $result === FALSE ) throw new \Exception();

        imagejpeg( $result, "test.png" );
        imagedestroy( $result );
        imagedestroy( $image );
    }
}
