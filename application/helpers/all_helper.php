<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function IsDateTime($aDateTime)
{
    try {
        $fTime = new DateTime($aDateTime);
        $fTime->format('m/d/Y H:i:s');
        return true;
    } catch (Exception $e) {
        return null;
    }
}


function TurkishUpper($Text)
{
    $Find = array('ı', 'i', 'ğ', 'ü', 'ş', 'ö', 'ç');
    $Change = array('I', 'I', 'G', 'U', 'S', 'O', 'C');
    $Text = str_replace($Find, $Change, $Text);

    $Find = array('İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç');
    $Change = array('I', 'G', 'U', 'S', 'O', 'C');
    $Text = str_replace($Find, $Change, $Text);
    return $Text;
}


?>