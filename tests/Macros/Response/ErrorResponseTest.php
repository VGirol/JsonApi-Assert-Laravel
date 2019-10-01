<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class ErrorResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiErrorResponse()
    {
        $status = 406;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $errorFactory = (new Generator)->error()
            ->fake()
            ->set('status', strval($status))
            ->setMeta([
                'not strict' => 'error when infection change default value for $strict parameter'
            ]);
        $doc = (new Generator)->document()->AddError($errorFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiErrorResponse($status, [$errorFactory->toArray()]);
    }

    /**
     * @test
     */
    public function assertJsonApiErrorResponseFailed()
    {
        $status = 404;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $errorFactory = (new Generator)->error()
            ->fake()
            ->set('status', strval($status))
            ->setMeta([
                'not strict' => 'error when infection change default value for $strict parameter'
            ]);
        $doc = (new Generator)->document()->fakeErrors();

        $failureMsg = $this->formatAsRegex(Messages::ERRORS_OBJECT_DOES_NOT_CONTAIN_EXPECTED_ERROR);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        $response->assertJsonApiErrorResponse($status, [$errorFactory->toArray()]);
    }
}
