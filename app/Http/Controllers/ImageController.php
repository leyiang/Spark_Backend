<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\Uploader;
use App\Models\Image;
use App\Models\Tag;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ImageController extends Controller
{
    public function index() {
        $images = Image::where("published", true)->get();

        return Helper::success([
            "images" => Helper::orderAll( ["id", "file", "path", "tags"], $images)
        ]);
    }

}
