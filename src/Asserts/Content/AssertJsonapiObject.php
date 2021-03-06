<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Testing\TestResponse;
use VGirol\JsonApiConstant\Members;

/**
 * This trait adds the ability to test jsonapi object.
 */
trait AssertJsonapiObject
{
    /**
     * Asserts that a jsonapi object equals an expected array.
     *
     * @param TestResponse $response
     * @param array       $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertResponseJsonapiObjectEquals(TestResponse $response, $expected)
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

        static::assertHasMember(Members::JSONAPI, $json);

        $jsonapi = $json[Members::JSONAPI];

        static::assertJsonapiObjectEquals($expected, $jsonapi);
    }
}
