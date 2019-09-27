<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class ErrorResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiErrorResponse()
    {
        $status = 406;
        $errors = [
            [
                'status' => strval($status),
                'title' => 'Not Acceptable',
                'details' => 'description',
                'meta' => [
                    'not strict' => 'error when infection change default value for $strict parameter'
                ]
            ]
        ];
        $content = [
            'errors' => $errors
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiErrorResponse($status, $errors);
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
        $content = [
            'errors' => [
                [
                    'status' => 404,
                    'title' => 'Not Found',
                    'details' => 'description'
                ]
            ],
            'meta' => [
                'key+' => 'not valid'
            ]
        ];
        $expectedStatus = 404;
        $expectedErrors = [
            [
                'status' => '404',
                'title' => 'Not Found',
                'details' => 'description'
            ]
        ];
        $strict = false;
        $failureMsg = Messages::ERROR_STATUS_IS_NOT_STRING;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiErrorResponse($expectedStatus, $expectedErrors);
    }
}
