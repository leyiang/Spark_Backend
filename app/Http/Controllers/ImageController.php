<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Image;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function index() {
        $images = Image::all();
        return Helper::success([
            "images" => $images
        ]);
    }

    public function store(Request $request) {
        $info = request()->validate([
            "image" => "required|exists:images,id,published,0",
            "crop" => "required",
            "tags" => "required|array",
        ]);

        $image = Image::find( $info["image"] );

        if( ! $image->exists() ) {
            throw new FileNotFoundException();
        }

        $image->crop( $info["crop"] );

        // TAG LOGIC

        $image->update([
            "published" => true
        ]);

        return Helper::success();
    }

    public function show(Image $image) {
    }

    public function update(Request $request, Image $image) {
    }

    public function destroy(Image $image) {
    }

    public function fetch() {
        $info = request()->validate([
            "path" => "required"
        ]);

        [$filename, $type ] = Image::fetch( $info["path"] );

        $image = Image::create([
            "file" => $filename,
            "type" => $type
        ]);

        return Helper::success([
            "id" => $image->id,
            "path" => $image->path()
        ]);
    }
}
