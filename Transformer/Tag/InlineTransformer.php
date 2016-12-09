<?php

namespace Becklyn\SearchText\Transformer\Tag;

use Becklyn\SearchText\Text\TextBuffer;
use Becklyn\SearchText\Transformer\ElementTransformer;


class InlineTransformer extends ElementTransformer
{
    /**
     * @inheritDoc
     */
    public function transform (\DOMElement $element, TextBuffer $buffer) : TextBuffer
    {
        foreach ($element->childNodes as $childNode)
        {
            $this->transformChildNode($childNode, $buffer);
        }

        return $buffer;
    }
}
