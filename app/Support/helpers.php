<?php

function random_generation($len = 6) {
    $chars = "abcdefghjkmnpqrstuvwxyz23456789";
    $input_length = strlen($chars);
    $random_string = '';
    for($i = 0; $i < $len; $i++) {
        $random_character = $chars[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}
