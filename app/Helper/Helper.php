<?php

namespace App\Helper;


class Helper {

    public static function success( $data=null ) {
        $info = [ "msg" => "success" ];

        if( isset($data) ) {
            $info["data"] = $data;
        }

        return response()->json( $info, 200 );
    }

}
