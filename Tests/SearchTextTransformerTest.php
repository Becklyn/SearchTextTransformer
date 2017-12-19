<?php

namespace Tests\Becklyn\SearchText;

use Becklyn\SearchText\SearchTextTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;


class SearchTextTransformerTest extends TestCase
{
    /**
     * @var SearchTextTransformer
     */
    private $transformer;


    /**
     * @var string
     */
    private $fixturesDir = __DIR__ ."/fixtures";


    public function setUp ()
    {
        $this->transformer = new SearchTextTransformer();
    }



    /**
     * @dataProvider parseDataProvider
     *
     * @param string $html
     * @param string $expectedText
     * @param string $message
     */
    public function testParsing (string $html, string $expectedText, string $message)
    {
        $result = $this->transformer->transform($html);

        self::assertEquals($expectedText, $result, $message);
    }



    /**
     * Parses all test case files
     *
     * @return array
     */
    public function parseDataProvider ()
    {
        $finder = new Finder();

        $finder
            ->files()
            ->in($this->fixturesDir)
            ->name("*.test")
            ->ignoreUnreadableDirs();

        $tests = [];

        /** @var \SplFileInfo $file */
        foreach ($finder as $file)
        {
            $parsedTest = $this->parseTestFile($file);

            if (null === $parsedTest)
            {
                self::assertTrue(
                    false,
                    sprintf(
                        "File '%s' has invalid test case syntax.",
                        $file->getPathname()
                    )
                );
            }
            else
            {
                $tests[] = $parsedTest;
            }
        }

        return $tests;
    }



    /**
     * @param \SplFileInfo $file
     *
     * @return array|null
     */
    private function parseTestFile (\SplFileInfo $file)
    {
        $content = file_get_contents($file->getPathname());

        if (1 === preg_match('~^(--TEST--\\n(?P<message>.*?)\\n)?--HTML--\\n(?P<html>.*?)\\n--EXPECT--\\n(?P<result>.*?)\\n?$~s', $content, $matches))
        {
            $message = $matches["message"];

            if (empty($message))
            {
                $message = sprintf(
                    "Running test file '%s'.",
                     $this->getRelativeFileName($file->getPathname())
                );
            }

            return [
                $matches["html"],
                $matches["result"],
                $message,
            ];
        }

        return null;
    }



    /**
     * Makes the file name relative
     *
     * @param string $path
     *
     * @return string
     */
    private function getRelativeFileName (string $path)
    {
        $filesystem = new Filesystem();
        return rtrim($filesystem->makePathRelative($path, $this->fixturesDir), "/");
    }

}
