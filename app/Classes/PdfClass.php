<?php namespace App\Classes;

class PdfClass
{

    private $htmlTemplate;
    private $localUri;
    private $serveUri;    

    public function __construct($template)
    {
        $this->htmlTemplate = $template;
        $this->localUri = 'http://127.0.0.1:8000';
        $this->serveUri = 'https//iso-one.com';        
    }

    public function render($data, $enclosed)
    {
        $html = View($this->htmlTemplate, [
            'document' => $data,
            'attachment' => $enclosed,
        ]);

        // Colocar todo el código en una sóla línea
        $html = preg_replace('/\>\s+\</m', '><', $html);

        // Corrección del URI para los links de archivos incorporados al HTML
        $html = str_replace($this->localUri, public_path(), $html);
        $html = str_replace($this->serveUri, public_path(), $html);        

        return $html;
    } // render

} // class

// http://127.0.0.1:8000/assets/css/head.css