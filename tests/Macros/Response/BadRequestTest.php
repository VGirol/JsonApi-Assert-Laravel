<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class BadRequestTest extends TestCase
{
    /**
     * @test
     * @dataProvider response4xxProvider
     */
    public function response4xx($status)
    {
        $fn = "assertJsonApiResponse{$status}";
        $errors = [
            [
                'status' => strval($status),
                'title' => JsonResponse::$statusTexts[$status],
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

        $response->{$fn}($errors);
    }

    /**
     * @test
     * @dataProvider response4xxProvider
     */
    public function response4xxFailed($status)
    {
        $fn = "assertJsonApiResponse{$status}";
        $errors = [
            [
                'status' => strval($status),
                'title' => JsonResponse::$statusTexts[$status],
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

        $response = Response::create(json_encode($content), $status + 1, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException();

        $response->{$fn}($errors);
    }

    public function response4xxProvider()
    {
        return [
            [400],
            [403],
            [404],
            [406],
            [409],
            [415]
        ];
    }
}
