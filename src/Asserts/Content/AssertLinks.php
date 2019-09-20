<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Members;

trait AssertLinks
{
    /**
     * Asserts that a links object equals an expected array of links.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param array $expected
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
     * Asserts that a links object contains an expected array of links.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param array $expected
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
