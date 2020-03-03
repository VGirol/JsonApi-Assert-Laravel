<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiConstant\Members;

/**
 * This trait adds the ability to test fetching relationship response.
 */
trait AssertFetchedRelationships
{
    /**
     * Asserts that the response has "200 Ok" status code and valid content.
     *
     * @param TestResponse $response
     * @param array|null   $expected The expected collection of resource identifier objects.
     * @param boolean      $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertFetchedRelationshipsResponse(TestResponse $response, $expected, $strict)
    {
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
        static::assertResourceLinkageEquals(
            $expected,
            $data,
            $strict
        );
    }
}
