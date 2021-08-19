<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ["created_at", "updated_at", "pivot"];

    public function images() {
        return $this->belongsToMany( Image::class, "image_tags" );
    }
}
