<?php

class TextFileSepChars {

    const mTab = "tab";
    const mComma = "comma";
    const mSemicolon = "semicolon";

    protected $mCharArray = array(
        "tab" => "\t",
        "comma" => ",",
        "semicolon" => ";"
    );

    function getSepChar($pSepChar = self::mSemicolon) {
        return $mCharArray[$pSepChar];
    }

}

?>
