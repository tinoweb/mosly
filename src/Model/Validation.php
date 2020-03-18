<?php

namespace MoslyApp\Model;

final class Validations {

    public static function validarString(string $string){
        return strlen($string)>=3 && !is_numeric($string);
    }

    public static function validarEmail(string $email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validarInteiro(int $inteiro){
        return filter_var($email, FILTER_VALIDATE_INT);
    }
}

