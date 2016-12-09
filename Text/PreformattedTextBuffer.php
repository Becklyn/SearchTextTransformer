<?php

namespace Becklyn\SearchText\Text;

/**
 * A preformatted buffer, that doesn't post-process inline text
 */
class PreformattedTextBuffer extends TextBuffer
{
    /**
     * @inheritDoc
     */
    public function addInlineText (string $text)
    {
        $this->addRawInlineText($text);
    }



    /**
     * All nested buffers of a preformatted buffer are preformatted
     *
     * @return TextBuffer
     */
    public function addNewBuffer () : TextBuffer
    {
        return $this->addNewPreformattedBuffer();
    }
}
