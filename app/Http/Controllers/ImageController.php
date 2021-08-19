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

    public function store(Request $request) {
        $info = request()->validate([
            "crop" => "required",
            "tags" => "required|array",
            "image" => "required|exists:images,id",
        ]);

        $image = Image::find( $info["image"] );

        if( ! $image->exists() ) {
            throw new FileNotFoundException();
        }

        $image->crop( $info["crop"] );

        // TAG LOGIC
        $tag_list = [];
        foreach ( $info["tags"] as $content ) {
            $tag = Tag::firstOrCreate(["content" => $content]);
            $tag_list[] = $tag->id;
        }

        $image->tags()->sync( $tag_list );


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
        $image->delete();
        return Helper::success();
    }

    public function upload() {
        $detail = request()->validate([
            "file" => "required|file|image"
        ]);

        $file = $detail["file"];

        $md5  = md5_file( $file->getRealPath() );
        $type = Image::getType( $file );
        $filename = $md5 . "." . $type;

        $file->move( public_path("tmp"), $filename );

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
