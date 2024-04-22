<?php

namespace App\Controllers;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeController extends BaseController
{
    public function index()
    {
        // Load the view with an empty data if no QR code is generated yet
        return view('qr_code_view', ['qrCodeSvg' => null]);
    }

    public function generate()
    {
        $text = $this->request->getVar('text', FILTER_SANITIZE_STRING);

        // Set QR Code options
        $options = new QROptions([
            'version'    => 7,    // QR Code version number
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,  // Output in SVG format
            'eccLevel'   => QRCode::ECC_L,              // Error correction level
        ]);

        // Generate QR code
        $qrCode = new QRCode($options);
        $qrSvg = $qrCode->render($text);

        // Pass the generated QR code to the view
        return view('qr_code_view', ['qrCodeSvg' => $qrSvg]);
    }
}
