<?php

namespace Becklyn\SearchText\Text;

class TextBuffer
{
    private $parts = [];



    /**
     * Nests a new buffer
     *
     * @return TextBuffer
     */
    public function addNewBuffer () : TextBuffer
    {
        $buffer = new TextBuffer();
        $this->parts[] = $buffer;
        return $buffer;
    }



    /**
     * Nests a new preformatted buffer
     *
     * @return TextBuffer
     */
    public function addNewPreformattedBuffer () : TextBuffer
    {
        $buffer = new PreformattedTextBuffer();
        $this->parts[] = $buffer;
        return $buffer;
    }



    /**
     * Appends inline text
     *
     * @param string $text
     */
    public function addInlineText (string $text)
    {
        // strip line breaks
        $text = preg_replace('~\\r?\\n~', ' ', $text);

        // collapse multiple spaces
        $text = str_replace('  ', ' ', $text);

        $this->addRawInlineText($text);
    }



    /**
     * Appends preformatted inline text
     *
     * @param string $text
     */
    protected function addRawInlineText (string $text)
    {
        $lastIndex = count($this->parts) - 1;

        if ($lastIndex >= 0 && is_string($this->parts[$lastIndex]))
        {
            $this->parts[$lastIndex] .= $text;
        }
        else
        {
            $this->parts[] = $text;
        }
    }



    /**
     * @inheritDoc
     */
    public function __toString ()
    {
        return implode(
            "\n\n",
            array_filter(
                array_map(
                    function ($part)
                    {
                        return $this->normalizeText((string) $part);
                    },
                    $this->parts
                )
            )
        );
    }



    /**
     * Normalizes the text
     *
     * @param string $text
     *
     * @return string
     */
    private function normalizeText (string $text)
    {
        // collapse multiple spaces
        $text = str_replace("  ", " ", $text);

        return trim($text);
    }
}
