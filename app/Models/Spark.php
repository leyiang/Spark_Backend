<?php

namespace App\Models;

use App\Helper\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spark extends Model {
    use HasFactory;

    // Get Src
    public function getSrcAttribute() {
        return Image::path( $this->attributes["file"], Image::PATH_URL );
    }

    public function updateTags() {
        $tag_list = [];
        foreach ( $info["tags"] as $content ) {
            $tag = Tag::firstOrCreate(["content" => $content]);
            $tag_list[] = $tag->id;
        }

        $spark->tags()->sync( $tag_list );
    }
}
