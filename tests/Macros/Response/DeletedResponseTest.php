<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class DeletedResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiDeleted()
    {
        $status = 200;
        $strict = false;
        $meta = [
            'message' => 'Deleting succeed'
        ];
        $content = [
            'meta' => $meta
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiDeleted($meta, $strict);
    }

    /**
     * @test
     */
    public function assertJsonApiDeletedFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $content = [
            'meta' => [
                'result not safe' => 'failed'
            ]
        ];
        $meta = null;
        $strict = true;
        $failureMsg = Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiDeleted($meta, $strict);
    }
}
