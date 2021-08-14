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

        return Helper::success([
            "path" => url("/images/" . $filename )
        ]);
    }
}
