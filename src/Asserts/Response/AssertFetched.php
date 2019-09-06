<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Members;

/**
 * Fetched response
 */
trait AssertFetched
{
    /**
     * Asserts that the response has 200 status code and content with primary data
     * corresponding to the provided model and resource type.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param array $expected
     * @param bool $strict
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertFetchedSingleResourceResponse(
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

        // Checks data member
        static::assertHasData($json);
        $data = $json[Members::DATA];
        static::assertResourceObjectEquals(
            $expected,
            $data
        );
    }
}
