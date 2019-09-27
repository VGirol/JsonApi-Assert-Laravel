<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Members;

/**
 * This trait adds the ability to test fetching response.
 */
trait AssertFetched
{
    /**
     * Asserts that the response has "200 Ok" status code and valid content.
     *
     * @param TestResponse $response
     * @param array<string, mixed> $expected The expected resource object
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
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
