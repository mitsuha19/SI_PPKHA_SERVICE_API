<?php

namespace App\Helpers;

class CaesarCipher
{
  public static function encrypt($text, $shift = 3)
  {
    $result = '';

    foreach (str_split($text) as $char) {
      if (ctype_alpha($char)) {
        $base = ctype_upper($char) ? 'A' : 'a';
        $result .= chr((ord($char) - ord($base) + $shift) % 26 + ord($base));
      } else {
        $result .= $char;
      }
    }

    return $result;
  }

  public static function decrypt($text, $shift = 3)
  {
    return self::encrypt($text, 26 - $shift);
  }
}
