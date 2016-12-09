<?php

namespace Becklyn\SearchText\Transformer;

use Becklyn\SearchText\Text\TextBuffer;
use Becklyn\SearchText\Transformer\Tag\BlockTransformer;
use Becklyn\SearchText\Transformer\Tag\DefinitionListTransformer;
use Becklyn\SearchText\Transformer\Tag\InlineTransformer;
use Becklyn\SearchText\Transformer\Tag\ListTransformer;
use Becklyn\SearchText\Transformer\Tag\PreTransformer;


class DomTransformer
{
    /**
     * @var (ElementTransformer|string)[]
     */
    private $supportedTags = [];



    /**
     */
    public function __construct ()
    {
        $block = new BlockTransformer($this);
        $inline = new InlineTransformer($this);
        $pre = new PreTransformer($this);
        $list = new ListTransformer($this);
        $definitionList = new DefinitionListTransformer($this);


        // some of the elements are commented out
        // this is intentional: unknown elements are ignored, they are listed for completeness' sake.
        // For some elements, that includes invalid markup:
        // <table> is supported, but the table elements are only supported inside the TableTransformer.
        // so encountering them without the transformer is ignored.
        $this->supportedTags = [
            "body" => $block,

            // --> Sections
            "article" => $block,
            "section" => $block,
            "nav" => $block,
            "aside" => $block,
            "h1" => $block,
            "h2" => $block,
            "h3" => $block,
            "h4" => $block,
            "h5" => $block,
            "h6" => $block,
            "header" => $block,
            "footer" => $block,
            "address" => $block,

            // --> Grouping content
            "p" => $block,
            // "hr"
            "pre" => $pre,
            "blockquote" => $block,
            "ol" => $list,
            "ul" => $list,
            "li" => $block,
            "dl" => $definitionList,
            "dt" => $block,
            "dd" => $block,
            // "figure"
            "figcaption" => $inline,
            "div" => $block,
            "main" => $block,

            // --> Text level semantics
            "a" => $inline,
            "em" => $inline,
            "strong" => $inline,
            "small" => $inline,
            "s" => $inline,
            "cite" => $inline,
            "q" => $inline,
            "dfn" => $inline,
            "abbr" => $inline,
            "data" => $inline,
            "time" => $inline,
            "code" => $inline,
            "var" => $inline,
            "samp" => $inline,
            "kbd" => $inline,
            "sub" => $inline,
            "sup" => $inline,
            "i" => $inline,
            "b" => $inline,
            "u" => $inline,
            "mark" => $inline,
            "ruby" => $inline,
            "rb" => $inline,
            "rt" => $inline,
            "rtc" => $inline,
            "rp" => $inline,
            "bdi" => $inline,
            "bdo" => $inline,
            "span" => $inline,
            "br" => "\n",
            "wbr" => " ",

            // --> Edits
            "ins" => $inline,
            "del" => $inline,

            // --> Embedded content
            // "img"
            // "iframe"
            // "embed"
            // "object"
            // "param"
            // "video"
            // "audio"
            // "source"
            // "track"
            // "map"
            // "area"

            // --> Table
            "table" => $block,
            // "caption"
            // "colgroup"
            // "col"
            "tbody" => $block,
            "thead" => $block,
            "tfoot" => $block,
            "tr" => $block,
            "td" => $inline,
            "th" => $inline,

            // --> Form
            // completely ignore
            // "form"
            // "label"
            // "input"
            // "button"
            // "select"
            // "datalist"
            // "optgroup"
            // "option"
            // "textarea"
            // "keygen"
            // "output"
            // "progress"
            // "meter"
            // "fieldset"
            // "legend"

            // --> Scripting
            // complete ignore
            // "script"
            // "noscript"
            // "template"
            // "canvas"

            // --> Deprecated elements
            // "applet"
            "marquee" => $inline,
        ];
    }



    /**
     * Transforms the given node
     *
     * @param \DOMNode   $node
     * @param TextBuffer $buffer
     *
     * @return TextBuffer
     */
    public function transformNode (\DOMNode $node, TextBuffer $buffer) : TextBuffer
    {
        switch (true)
        {
            case ($node instanceof \DOMText):
                return $this->transformDomText($node, $buffer);

            case ($node instanceof \DOMElement):
                return $this->transformDomElement($node, $buffer);

            default:
                throw new \InvalidArgumentException(sprintf(
                    "Unsupported element: %s",
                    get_class($node)
                ));
        }
    }



    /**
     * Transforms a DOM element
     *
     * @param \DOMElement $node
     * @param TextBuffer  $buffer
     *
     * @return TextBuffer
     */
    private function transformDomElement (\DOMElement $node, TextBuffer $buffer) : TextBuffer
    {
        /** @var ElementTransformer $transformer */
        $transformer = $this->supportedTags[$node->tagName] ?? "";

        if (is_string($transformer))
        {
            $buffer->addInlineText($transformer);
            return $buffer;
        }

        return $transformer->transform($node, $buffer);
    }



    /**
     * Transforms a DOM text node
     *
     * @param \DOMText   $node
     * @param TextBuffer $buffer
     *
     * @return TextBuffer
     */
    private function transformDomText (\DOMText $node, TextBuffer $buffer) : TextBuffer
    {
        $buffer->addInlineText($node->nodeValue);
        return $buffer;
    }
}
