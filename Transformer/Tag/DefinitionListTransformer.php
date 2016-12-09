<?php

namespace Becklyn\SearchText\Transformer\Tag;

use Becklyn\SearchText\Text\TextBuffer;
use Becklyn\SearchText\Transformer\ElementTransformer;


class DefinitionListTransformer extends ElementTransformer
{
    /**
     * @inheritDoc
     */
    public function transform (\DOMElement $element, TextBuffer $buffer) : TextBuffer
    {
        $listBuffer = $buffer->addNewBuffer();

        $childNodes = $this->filterDomElements($element->childNodes);
        $maxIndex = count($childNodes) - 1;

        for ($i = 0; $i <= $maxIndex; $i++)
        {
            $childNode = $childNodes[$i];
            $next = $i <= $maxIndex
                ? $childNodes[$i + 1]
                : null;

            if (("dt" === $childNode->tagName) && (null !== $next) && ("dd" === $next->tagName))
            {
                $dtBuffer = $this->transformChildNode($childNode, new TextBuffer());
                $ddBuffer = $this->transformChildNode($next, new TextBuffer());

                $combinedBuffer = $listBuffer->addNewPreformattedBuffer();
                $combinedBuffer->addInlineText(
                    (string) $dtBuffer . "\n" . (string) $ddBuffer
                );

                $i = $i + 1;
            }
            else
            {
                $this->transformChildNode($childNode, $listBuffer);
            }

        }

        return $buffer;
    }
}
