<?php

namespace App\Helpers;

use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeHelper
{
    public static function generateBarcode($barcodeData)
    {
        $generator = new BarcodeGeneratorPNG();
        return '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($barcodeData, $generator::TYPE_CODE_128)) . '" alt="Barcode">';
    }
}
