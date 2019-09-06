<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Members;

/**
 * Updated response
 */
trait AssertUpdated
{
    /**
     * Asserts that a response object is a valid '200 OK' response following an update request.
     *
     * @param TestResponse $response
     * @param array $expected
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsUpdatedResponse(
        TestResponse $response,
        $expected,
        bool $strict
    ) {
        $response->assertStatus(200);
        $response->assertHeader(
            HttpHeader::HEADER_NAME,
            HttpHeader::MEDIA_TYPE
        );

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        static::assertHasValidStructure(
            $json,
            $strict
        );

        // Checks presence of "meta" or "data" member
        static::assertContainsAtLeastOneMember(
            [
                Members::META,
                Members::DATA
            ],
            $json
        );

        // Checks data member
        if (isset($json[Members::DATA])) {
            $data = $json[Members::DATA];
            static::assertResourceObjectEquals(
                $expected,
                $data
            );
        }
    }
}
