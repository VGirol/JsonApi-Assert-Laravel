<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiFaker\Laravel\Generator;

class NoContentTest extends TestCase
{
    /**
     * @test
     */
    public function responseNoContent()
    {
        $headers = [
            'X-PERSONAL' => ['test']
        ];

        $response = Response::create(null, 204, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertIsNoContentResponse($response);
    }

    /**
     * @test
     * @dataProvider notValidResponseNoContent
     */
    public function responseNoContentFailed($status, $headers, $content, $failureMsg)
    {
        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        Assert::assertIsNoContentResponse($response);
    }

    public function notValidResponseNoContent()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        return [
            'bad status' => [
                201,
                [],
                null,
                'Expected status code 204 but received 201.'
            ],
            'has header' => [
                204,
                $headers,
                null,
                'Unexpected header [Content-Type] is present on response.'
            ],
            'has content' => [
                204,
                [],
                (new Generator)->document()->fakeMeta()->toJson(),
                'Failed asserting that a string is empty.'
            ]
        ];
    }
}
