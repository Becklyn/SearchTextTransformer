<?php

namespace Becklyn\SearchText;

use Becklyn\SearchText\Text\TextBuffer;
use Becklyn\SearchText\Transformer\DomTransformer;


/**
 * Transforms HTML text to formatted plain text
 */
class SearchTextTransformer
{
    /**
     * @var DomTransformer
     */
    private $domTransformer;



    /**
     */
    public function __construct ()
    {
        $this->domTransformer = new DomTransformer();
    }



    /**
     * Transforms the given HTML string to formatted plain text
     *
     * @param string $html
     *
     * @return string
     */
    public function transform (string $html) : string
    {
        $body = $this->loadStringIntoDom($html);
        return (string) $this->domTransformer->transformNode($body, new TextBuffer());
    }



    /**
     * Loads the given HTML string into a DOM document and returns the body element
     *
     * @param string $html
     *
     * @return \DOMElement
     */
    private function loadStringIntoDom (string $html) : \DOMElement
    {
        $document = new \DOMDocument("1.0", "utf8");
        @$document->loadHTML('
            <html>
                <head>
                    <meta charset="utf-8">
                </head>
                <body>' . $html . '</body>
            </html>
        ');

        return $document->getElementsByTagName("body")->item(0);
    }
}
