<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\Image;
use App\Models\Spark;
use Illuminate\Http\Request;

class SparkController extends Controller
{
    public function index() {
        $sparks = Spark::all();

        return Helper::success(
            Helper::orderAll( $sparks, ["id", "file", "path", "tags"] )
        );
    }


    // Only need one image to create a spark
    public function store(Request $request) {
        $info = request()->validate([
            "image" => "required|file|image"
        ]);

        // Upload Image
        $path = Image::upload( $info["image"] );

        // Create Spark
        $spark = Spark::create([ "path" => $path ]);

        return Helper::success(
            Helper::order( $spark, ["id", "src"] )
        );
    }

    public function show(Spark $spark) {

    }

    public function update(Request $request, Spark $spark) {
        $info = request()->validate([
            "crop" => "required",
            "tags" => "required|array",
        ]);

        // CROP
        $path = Image::crop(
            Image::path( $spark->file, Image::PATH_FILESYSTEM ),
            $info["crop"]
        );

        // Tag
        $spark->updateTags( $info["tags"] );

        return Helper::success();
    }

    public function destroy(Spark $spark) {
        $spark->delete();
        return Helper::success();
    }
}
