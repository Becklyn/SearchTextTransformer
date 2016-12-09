<?php

namespace Becklyn\SearchText\Transformer;

use Becklyn\SearchText\SearchTextTransformer;
use Becklyn\SearchText\Text\TextBuffer;


/**
 * Base class for all element transformers
 */
abstract class ElementTransformer
{
    /**
     * @var SearchTextTransformer
     */
    private $transformer;



    /**
     * @param DomTransformer $transformer
     */
    public function __construct (DomTransformer $transformer)
    {
        $this->transformer = $transformer;
    }



    /**
     * Transforms the given dom element
     *
     * @param \DOMElement $element
     * @param TextBuffer  $buffer
     *
     * @return TextBuffer
     */
    abstract public function transform (\DOMElement $element, TextBuffer $buffer) : TextBuffer;



    /**
     * Recursively transforms the child nodes
     *
     * @param \DOMNode   $node
     * @param TextBuffer $buffer
     *
     * @return TextBuffer
     */
    protected function transformChildNode (\DOMNode $node, TextBuffer $buffer) : TextBuffer
    {
        return $this->transformer->transformNode($node, $buffer);
    }



    /**
     * Filters out all nodes except dom elements
     *
     * @param \DOMNodeList $list
     *
     * @return \DOMElement[]
     */
    protected function filterDomElements (\DOMNodeList $list)
    {
        $filtered = [];

        foreach ($list as $element)
        {
            if ($element instanceof \DOMElement)
            {
                $filtered[] = $element;
            }
        }

        return $filtered;
    }
}

