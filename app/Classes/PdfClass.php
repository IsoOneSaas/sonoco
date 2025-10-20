<?php namespace App\Classes;

class PdfClass
{

    private $htmlTemplate;
    private $localUri;
    private $serveUri1;    
    private $serveUri2;

    public function __construct($template)
    {
        $this->htmlTemplate = $template;
        $this->localUri = 'http://127.0.0.1:8000';
        $this->serveUri1 = 'http//localhost';
        $this->serveUri2 = env('APP_URL'); 
    }

    /**
     * Renderiza HTML de un DOCUMENTO para generar PDF
     * @param  array $data datos del documento
     * @param  collection $enclose datos de archivos adjuntos del documento
     * @return string    HTML del documento
     */
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
        $html = str_replace($this->serveUri1, public_path(), $html);        
        $html = str_replace($this->serveUri2, public_path(), $html);

        return $html;
    } // render

    /**
     * Renderiza HTML de un REGISTRO para generar PDF
     * @param  array $data datos del registro
     * @param  array $document datos del documento relacionado
     * @param  array $setup parámetros de configuración de la hoja
     * @return string  HTML del registro
     */
    public function renderRecord($data, $document, $setup)
    {
        $html = View($this->htmlTemplate, [
            'size' => $setup['size'] .'-'. $setup['orientation'],
            'document' => $document,
            'DATA' => $data,
        ]);

        // Colocar todo el código en una sóla línea
        $html = preg_replace('/\>\s+\</m', '><', $html);

        // Corrección del URI para los links de archivos incorporados al HTML
        $html = str_replace($this->localUri, public_path(), $html);
        $html = str_replace($this->serveUri1, public_path(), $html);        
        $html = str_replace($this->serveUri2, public_path(), $html);

        return $html;
    } // renderRecord    

} // class

// http://127.0.0.1:8000/assets/css/head.css