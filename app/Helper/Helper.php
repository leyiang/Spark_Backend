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

    public static function order( $from, $keys ) {
        $result = [];

        foreach ( $keys as $key ) {
            if( strpos($key, ":") !== false ) {
                [$new_from, $new_keys_raw] = explode(":", $key );
                $new_keys = explode(",", $new_keys_raw);
                $result[ $new_from ] = Helper::orderAll( $from[ $new_from ], $new_keys );
            } else {
                $result[ $key ] = $from[ $key ];
            }
        }

        return $result;
    }

    public static function orderAll( $from_list, $keys ) {
        $result = [];

        foreach ( $from_list as $from ) {
            $result[] = Helper::order( $from, $keys );
        }

        return $result;
    }
}
