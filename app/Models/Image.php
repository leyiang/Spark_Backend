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

    public function path( $filesystem = false ) {
        $folder = $this->published ? "images" : "tmp";
        $file = $folder . DIRECTORY_SEPARATOR . $this->file;

        if( $filesystem ) {
            return public_path( $file );
        }

        return url( $file );
    }

    public function crop( $info ) {
        $type = $this->type;
        $image = call_user_func("imagecreatefrom". $type, $this->path(true) );
        $result = imagecrop( $image, $info );

        if( $result === FALSE ) throw new \Exception();

        call_user_func("image" . $type, $result, public_path("/images/" . $this->file ) );
        imagedestroy( $result );
        imagedestroy( $image );
    }

    public function getPathAttribute() {
        return $this->path();
    }

    public function tags() {
        return $this->belongsToMany( Tag::class, "image_tags" );
    }
}
