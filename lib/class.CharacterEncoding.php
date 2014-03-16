<?php

/**
 * Character encoding enumeration
 */
class CharacterEncoding {

    const UCS_4 = "UCS-4";
    /* UCS-4BE
      UCS-4LE
      UCS-2
      UCS-2BE
      UCS-2LE
      UTF-32
      UTF-32BE
      UTF-32LE
      UTF-16
      UTF-16BE
      UTF-16LE
      UTF-7
      UTF7-IMAP */
    const UTF_8 = "UTF-8";
    const ASCII = "ASCII";
    /* EUC-JP
      SJIS
      eucJP-win
      SJIS-win
      ISO-2022-JP
      ISO-2022-JP-MS
      CP932
      CP51932
      SJIS-mac
      SJIS-Mobile#DOCOMO** (alias: SJIS-DOCOMO)
      SJIS-Mobile#KDDI** (alias: SJIS-KDDI)
      SJIS-Mobile#SOFTBANK** (alias: SJIS-SOFTBANK)
      UTF-8-Mobile#DOCOMO** (alias: UTF-8-DOCOMO)
      UTF-8-Mobile#KDDI-A**
      UTF-8-Mobile#KDDI-B** (alias: UTF-8-KDDI)
      UTF-8-Mobile#SOFTBANK** (alias: UTF-8-SOFTBANK)
      ISO-2022-JP-MOBILE#KDDI** (alias: ISO-2022-JP-KDDI)
      JIS
      JIS-ms
      CP50220
      CP50220raw
      CP50221
      CP50222 */
    const ISO_8859_1 = "ISO-8859-1";
    const ISO_8859_2 = "ISO-8859-2";
    const ISO_8859_3 = "ISO-8859-3";
    const ISO_8859_4 = "ISO-8859-4";
    const ISO_8859_5 = "ISO-8859-5";
    const ISO_8859_6 = "ISO-8859-6";
    const ISO_8859_7 = "ISO-8859-7";
    const ISO_8859_8 = "ISO-8859-8";
    const ISO_8859_9 = "ISO-8859-9";
    const ISO_8859_10 = "ISO-8859-10";
    const ISO_8859_13 = "ISO-8859-13";
    const ISO_8859_14 = "ISO-8859-14";
    const ISO_8859_15 = "ISO-8859-15";
    /*  byte2be
      byte2le
      byte4be
      byte4le
      BASE64
      HTML-ENTITIES
      7bit
      8bit
      EUC-CN
      CP936
      GB18030
      "HZ"
      "EUC-TW"
      "CP950"
      "BIG-5"
      "EUC-KR"
      "UHC"
      "CP949"
      "ISO-2022-KR" */
    const Windows_1251 = "Windows-1251";
    const CP1251 = "CP1251";
    const Windows_1252 = "Windows-1252";
    const CP1252 = "CP1252";

    /*
      "CP866"
      "IBM866"
      "KOI8-R"
     */

    public static function getEncodings() {
        $x = new ReflectionClass("CharacterEncoding");
        return(array_values($x->getConstants()));
    }

}

?>