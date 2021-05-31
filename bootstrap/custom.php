<?php

if (! function_exists('readable_file_size')) {
    function readable_file_size($bytes, $dec = 2) {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) .' '. @$sizes[$factor];
    }
}

if (! function_exists('file_creation_time')) {
    function file_creation_time($file, $format = "d-M-Y h:ia") {
        return date($format, filemtime($file));
    }
}