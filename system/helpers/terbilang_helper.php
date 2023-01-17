<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('numb_to_alphabet')) {

    function numb_to_alphabet($x) {
        $x = abs($x);
        $angka = array("", "satu ", "dua ", "tiga ", "empat ", "lima ",
            "enam ", "tujuh ", "delapan ", "sembilan ", "sepuluh ", "sebelas ");
        $temp = "";
        if ($x < 12) {
            $temp = " " . $angka[$x];
        } else if ($x < 20) {
            $temp = numb_to_alphabet($x - 10) . " belas";
        } else if ($x < 100) {
            $temp = numb_to_alphabet($x / 10) . " puluh" . numb_to_alphabet($x % 10);
        } else if ($x < 200) {
            $temp = " seratus" . numb_to_alphabet($x - 100);
        } else if ($x < 1000) {
            $temp = numb_to_alphabet($x / 100) . " ratus" . numb_to_alphabet($x % 100);
        } else if ($x < 2000) {
            $temp = " seribu" . numb_to_alphabet($x - 1000);
        } else if ($x < 1000000) {
            $temp = numb_to_alphabet($x / 1000) . " ribu" . numb_to_alphabet($x % 1000);
        } else if ($x < 1000000000) {
            $temp = numb_to_alphabet($x / 1000000) . " juta" . numb_to_alphabet($x % 1000000);
        } else if ($x < 1000000000000) {
            $temp = numb_to_alphabet($x / 1000000000) . " milyar" . numb_to_alphabet(fmod($x, 1000000000));
        } else if ($x < 1000000000000000) {
            $temp = numb_to_alphabet($x / 1000000000000) . " trilyun" . numb_to_alphabet(fmod($x, 1000000000000));
        }
        return $temp;
    }

}

if (!function_exists('terbilang')) {

    function terbilang($x) {
        if ($x < 0) {
            $hasil = "minus " . trim(numb_to_alphabet($x));
        } else {
            $poin = trim(tkoma($x));
            $hasil = trim(numb_to_alphabet($x));
            if ($poin) {
                $hasil = ucwords($hasil) . " koma " . $poin;
            } else {
                $hasil = ucwords($hasil);
            }
        }
        return $hasil;
    }

}

function tkoma($x) {
    $x = strstr($x, ".");
    $angka = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");
    $temp = " ";
    $pjg = strlen($x);
    $pos = 1;
    while ($pos < $pjg) {
        $char = substr($x, $pos, 1);
        $pos++;
        $temp .=" " . $angka[$char];
    }
    return $temp;
}

function simply_thousand($number) {
    if ($number >= 1000) {
        return $number / 1000 . "K";   // NB: you will want to round this
    } else {
        return $number;
    }
}

function rupiah($jumlah,$book=null) {
    if ($jumlah <> 0 or !empty($jumlah)) {
        if ($jumlah < 0) {
            $jumlah = substr($jumlah,1);
            if (isset($book) and $book == 1) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'> -".number_format($jumlah, 2, ",", ".").'</span>';
            else if (isset($book) and $book == 2) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'>(-".number_format($jumlah, 2, ",", ".").')</span>';
            else $return = "Rp -".number_format($jumlah, 2, ",", ".");
        } else {
            if (isset($book) and $book == 1) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'> ".number_format($jumlah, 2, ",", ".").'</span>';
            else if (isset($book) and $book == 2) $return = "<span class='pull-left'>Rp &nbsp;</span> <span class='pull-right'>(".number_format($jumlah, 2, ",", ".").')</span>';
            else $return = "Rp ".number_format($jumlah, 2, ",", ".");   
        }
    } else {
        if (isset($book)) $return = "<span class='pull-left'>Rp &nbsp;</span><span class='pull-right'>0,00</span>";
        else $return = "Rp 0,00";
    }
    return $return;
}

function numberToCurrency($a,$tipe = null) {
    if ($a < 0) {
        $a = substr($a,1);
        $col = '-'.number_format($a, 0, ".", ".");
    } else {
        $col = number_format($a, 0, ".", ".");
    }
    if (!empty($tipe)) $col = '<span class="pull-right">'.$col.'</span>';
    return $col;
}

function currencyToNumber($a){
    $b=str_ireplace(".", "", $a);
    return str_replace(",",".",$b);
}

?>
