<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FileController extends Controller
{
    public function serve($filename)
    {
        // Define the path to the file
        $filePath = ROOTPATH . 'public/uploads/' . $filename;

        // Check if the file exists
        if (file_exists($filePath)) {
            // Get the file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            // Set the correct MIME type for the file
            $mimeType = mime_content_type($filePath);

            // Set the headers to display the file in the browser
            return $this->response->setHeader('Content-Type', $mimeType)
                                  ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                                  ->setBody(file_get_contents($filePath));
        } else {
            // If file does not exist, return a 404 error
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }
    }
}
