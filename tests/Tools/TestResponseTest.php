<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\UseJsonapiTestResponse;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Laravel\Generator;

class TestResponseTest extends TestCase
{
    use UseJsonapiTestResponse;

    /**
     * @test
     */
    public function assertStatusSucceed()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $actual = 204;
        $expected = 204;
        $doc = (new Generator)->document();

        $response = $this->createTestResponse(
            JsonResponse::create($doc->toJson(), $actual, $headers)
        );

        $obj = $response->assertStatus($expected);

        PHPUnit::assertSame($response, $obj);
    }

    /**
     * @test
     */
    public function assertStatusFailed()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $actual = 204;
        $expected = 200;

        $doc = (new Generator)->document();

        $message = sprintf(TestResponse::ERROR_STATUS, $expected, $actual);

        $response = $this->createTestResponse(
            JsonResponse::create($doc->toJson(), $actual, $headers)
        );

        $this->setFailure(ExpectationFailedException::class, $message);

        $response->assertStatus($expected);
    }

    /**
     * @test
     * @dataProvider assertStatusFailedWithErrorMessageProvider
     */
    public function assertStatusFailedWithErrorMessage($actual)
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $expected = 204;

        $errorFactory = (new Generator)->error()
            ->fake()
            ->set(Members::ERROR_STATUS, strval($actual))
            ->set(Members::ERROR_TITLE, JsonResponse::$statusTexts[$actual])
            ->set(Members::ERROR_DETAILS, 'test');
        $doc = (new Generator)->document()->AddError($errorFactory);

        $message = sprintf(TestResponse::ERROR_STATUS, $expected, $actual) . "\ntest";

        $response = $this->createTestResponse(
            JsonResponse::create($doc->toArray(), $actual, $headers)
        );

        $this->setFailure(ExpectationFailedException::class, $message);

        $response->assertStatus($expected);
    }

    public function assertStatusFailedWithErrorMessageProvider()
    {
        return [
            [300],
            [400]
        ];
    }
}
