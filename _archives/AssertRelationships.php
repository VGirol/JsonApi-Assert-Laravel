<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Members;

/**
 * Fetched response
 */
trait AssertRelationships
{
    public static function assertResourceObjectContainsRelationships(TestResponse $response, $expected, $resource)
    { }

    public static function assertResourceObjectContainsRelationship(TestResponse $response, $expected, $resource)
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

        static::assertHasMember(Members::INCLUDED, $json);

        $included = $json[Members::INCLUDED];

        static::assertIncludeObjectContains($expected, $included);
    }
}
