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
            Helper::orderAll( $sparks, ["id", "link", "src", "tags"] )
        );
    }


    // Only need one image to create a spark
    public function store(Request $request) {
        $info = request()->validate([
            "image" => "required|file|image"
        ]);

        // Upload Image
        [$type, $filename] = Image::upload( $info["image"] );

        // Create Spark
        $spark = Spark::create([
            "type" => $type,
            "file" => $filename
        ]);

        return Helper::success([
            "id" => $spark->id
        ]);
    }

    public function show(Spark $spark) {
        return Helper::success(
            Helper::order( $spark, ["id", "src", "link", "tags"] )
        );
    }

    public function update(Request $request, Spark $spark) {
        $info = request()->validate([
            "crop" => "required",
            "tags" => "required|array",
        ]);

        $properties = request()->validate([
            "link" => "required",
        ]);

        // CROP
        $properties["file"] = Image::crop(
            Image::path( $spark->file, Image::PATH_FILESYSTEM ),
            $info["crop"], $spark->type
        );

        // Tag
        $spark->updateTags( $info["tags"] );

        // Update Properties
        $spark->update( $properties );

        return Helper::success();
    }

    public function destroy(Spark $spark) {
        $spark->delete();
        return Helper::success();
    }
}
