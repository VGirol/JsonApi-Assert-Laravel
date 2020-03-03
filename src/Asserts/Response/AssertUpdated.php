<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiConstant\Members;

/**
 * This trait adds the ability to test response returned after resource update.
 */
trait AssertUpdated
{
    /**
     * Asserts that a response object is a valid '200 OK' response following an update request.
     *
     * @param TestResponse $response
     * @param array        $expected     The expected updated resource object
     * @param boolean      $relationship If true, response content must be valid resource linkage
     * @param boolean      $strict       If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public static function assertIsUpdatedResponse(
        TestResponse $response,
        $expected,
        bool $relationship,
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
        if (!array_key_exists(Members::DATA, $json)) {
            return;
        }

        $data = $json[Members::DATA];

        // Check if the response contains resource linkage ...
        if ($relationship) {
            static::assertResourceLinkageEquals($expected, $data, $strict);

            return;
        }

        // ... or a single resource
        static::assertIsNotArrayOfObjects($data);
        static::assertResourceObjectEquals(
            $expected,
            $data
        );
    }
}
