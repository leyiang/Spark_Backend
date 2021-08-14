<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function index() {
    }

    public function store(Request $request) {
        $info = request()->validate([
            "image" => "required|file",
            "crop" => "required",
            "tags" => "required|array",
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

        $filename = Image::fetch( $info["path"] );
        $image = Image::create([ "file" => $filename ]);

        return Helper::success([
            "id" => $image->id,
            "path" => url("/images/" . $filename )
        ]);
    }
}
