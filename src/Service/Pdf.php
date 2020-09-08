<?php 
//src/Service/Pdf.php
namespace App\Service;

final class Pdf
{
    public function __construct(DompdfWrapperInterface $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function stream()
    {
        // ...
        $html = '<h1>Sample Title</h1><p>Lorem Ipsum</p>';

        $response = $this->wrapper->getStreamResponse($html, "document.pdf");
        $response->send();
        // ...
    }

    public function binaryContent()
    {
        // ...
        return $this->wrapper->getPdf($html);
        // ...
    }
}