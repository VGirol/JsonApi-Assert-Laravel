<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Laravel\Generator;

class CreatedResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiCreated()
    {
        $status = 201;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $strict = false;

        $resFactory = (new Generator)->resourceObject()
            ->fake()
            ->fakeLinks();
        $doc = (new Generator)->document()
            ->setData($resFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiCreated($resFactory->toArray(), $strict);
    }

    /**
     * Response has no "data" member.
     * @test
     */
    public function assertJsonApiCreatedFailed()
    {
        $status = 201;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $strict = false;

        $roFactory = (new Generator)->resourceObject()
            ->fake()
            ->fakeLinks();
        $doc = (new Generator)->document()
            ->fakeMeta();

        $failureMsg = sprintf(Messages::HAS_MEMBER, Members::DATA);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        $response->assertJsonApiCreated($roFactory->toArray(), $strict);
    }
}
