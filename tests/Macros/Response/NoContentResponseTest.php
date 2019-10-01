<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class NoContentResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiNoContent()
    {
        $headers = [
            'X-PERSONAL' => ['test']
        ];

        $response = Response::create(null, 204, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiNoContent();
    }

    /**
     * @test
     */
    public function assertJsonApiNoContentFailed()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $status = 204;

        $failureMsg = 'Unexpected header [Content-Type] is present on response.';

        $response = Response::create('', $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        $response->assertJsonApiNoContent();
    }
}
