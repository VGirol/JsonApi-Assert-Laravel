<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Members;

/**
 * This trait adds the ability to test error response.
 */
trait AssertErrorResponse
{
    /**
     * Asserts that an error response (status code 4xx) is valid.
     *
     * @param TestResponse $response
     * @param integer $expectedStatusCode
     * @param array<array> $expectedErrors An array of the expected error objects.
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsErrorResponse(
        TestResponse $response,
        int $expectedStatusCode,
        $expectedErrors,
        bool $strict
    ) {
        $response->assertStatus($expectedStatusCode);
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

        // Checks errors member
        static::assertHasErrors($json);
        $errors = $json[Members::ERRORS];
        static::assertErrorsContains(
            $expectedErrors,
            $errors,
            $strict
        );
    }
}
