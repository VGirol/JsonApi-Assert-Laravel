<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Messages;
use VGirol\JsonApiConstant\Members;

/**
 * This trait adds the ability to test response returned after resource deletion.
 */
trait AssertDeleted
{
    /**
     * Asserts that a response object is a valid "200 OK" response following a deletion request.
     *
     * @param TestResponse $response
     * @param array|null   $expectedMeta If not null, it is the expected "meta" object.
     * @param boolean      $strict       If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsDeletedResponse(
        TestResponse $response,
        $expectedMeta,
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

        static::assertContainsOnlyAllowedMembers(
            [
                Members::META,
                Members::JSONAPI
            ],
            $json
        );

        // Checks meta object
        $meta = $json[Members::META];
        if ($expectedMeta !== null) {
            PHPUnit::assertEquals(
                $expectedMeta,
                $meta,
                Messages::META_OBJECT_IS_NOT_AS_EXPECTED
            );
        }
    }
}
