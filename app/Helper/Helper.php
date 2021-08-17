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

    public static function order( $keys, $from ) {
        $result = [];

        foreach ( $keys as $key ) {
            $result[ $key ] = $from[ $key ];
        }

        return $result;
    }

    public static function orderAll( $keys, $from_list ) {
        $result = [];

        foreach ( $from_list as $from ) {
            $result[] = Helper::order( $keys, $from );
        }

        return $result;
    }
}
