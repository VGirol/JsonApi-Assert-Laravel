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
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $strict = false;

        $doc = (new Generator)->document()
            ->fakeMeta();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiDeleted($doc->getMeta(), $strict);
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
        $strict = true;

        $doc = (new Generator)->document()
            ->setMeta([
                'result not safe' => 'failed'
            ]);

        $failureMsg = Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS;

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        $response->assertJsonApiDeleted($doc->getMeta(), $strict);
    }
}
