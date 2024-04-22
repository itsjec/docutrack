<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use SimpleSoftwareIO\QrCode\Generator;

class QrCodeGeneratorController extends BaseController
{
    public function index()
    {
        $qrcode = new Generator;
        $qrCodes = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inputUrl'])) {
            $url = $_POST['inputUrl'];
            $qrCodes['customQrCode'] = $qrcode->size(200)->generate($url);
        }
        return view('qr-codes', $qrCodes);
    }
}
