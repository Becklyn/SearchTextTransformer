<?php

namespace Becklyn\SearchText\Transformer\Tag;

use Becklyn\SearchText\Text\TextBuffer;
use Becklyn\SearchText\Transformer\ElementTransformer;


class BlockTransformer extends ElementTransformer
{
    /**
     * @inheritDoc
     */
    public function transform (\DOMElement $element, TextBuffer $buffer) : TextBuffer
    {
        $nestedBuffer = $buffer->addNewBuffer();

        foreach ($element->childNodes as $childNode)
        {
            $this->transformChildNode($childNode, $nestedBuffer);
        }

        return $buffer;
    }
}
