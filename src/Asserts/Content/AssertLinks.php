<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiConstant\Members;

/**
 * This trait adds the ability to test links object.
 */
trait AssertLinks
{
    /**
     * Asserts that a document links object equals an expected array of links.
     *
     * @param TestResponse $response
     * @param array        $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertDocumentLinksObjectEquals(TestResponse $response, $expected)
    {
        if (!\is_array($expected)) {
            static::invalidArgument(
                2,
                'array',
                $expected
            );
        }

        // Decode JSON response
        $json = $response->json();

        static::assertHasLinks($json);

        $links = $json[Members::LINKS];

        static::assertLinksObjectEquals($expected, $links);
    }

    /**
     * Asserts that a document links object contains an expected array of links.
     *
     * @param TestResponse $response
     * @param array        $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertDocumentLinksObjectContains(TestResponse $response, $expected)
    {
        if (!\is_array($expected)) {
            static::invalidArgument(
                2,
                'array',
                $expected
            );
        }

        // Decode JSON response
        $json = $response->json();

        static::assertHasLinks($json);

        $links = $json[Members::LINKS];

        foreach ($expected as $name => $value) {
            static::assertLinksObjectContains($name, $value, $links);
        }
    }
}
