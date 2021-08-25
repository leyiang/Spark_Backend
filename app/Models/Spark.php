<?php

namespace App\Models;

use App\Helper\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spark extends Model {
    use HasFactory;

    protected $guarded = [];

    // Mutators
    public function getSrcAttribute() {
        return Image::path( $this->attributes["file"], Image::PATH_URL );
    }

    // Relations
    public function tags() {
        return $this->belongsToMany( Tag::class, "spark_tags");
    }

    // helpers
    public function updateTags( $tags ) {
        $tag_list = [];

        foreach ( $tags as $content ) {
            $tag = Tag::firstOrCreate([ "content" => $content ]);
            $tag_list[] = $tag->id;
        }

        $this->tags()->sync( $tag_list );
    }
}
