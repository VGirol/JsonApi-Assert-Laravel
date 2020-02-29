<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macro\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiFetchedSingleResource()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = true;

        $resourceFactory = (new Generator)->resourceObject()
            ->fake();
        $doc = (new Generator)->document()
            ->setData($resourceFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedSingleResource($resourceFactory->toArray(), $strict);
    }

    /**
     * Response has no "data" member.
     * @test
     */
    public function assertJsonApiFetchedSingleResourceFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = true;
        $failureMsg = sprintf(Messages::HAS_MEMBER, Members::DATA);

        $resourceFactory = (new Generator)->resourceObject()
            ->fake();
        $doc = (new Generator)->document()
            ->fakeMeta();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        $response->assertJsonApiFetchedSingleResource($resourceFactory->toArray(), $strict);
    }

    /**
     * @test
     */
    public function responseFetchedCollection()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $strict = false;

        $collectionFactory = (new Generator)->collection()
            ->fake();
        $doc = (new Generator)->document()
            ->setData($collectionFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedResourceCollection($collectionFactory->toArray(), $strict);
    }

    /**
     * Response has no "data" member.
     * @test
     */
    public function responseFetchedCollectionFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;
        $failureMsg = sprintf(Messages::HAS_MEMBER, Members::DATA);

        $resourceFactory = (new Generator)->resourceObject()
            ->fake();
        $doc = (new Generator)->document()
            ->fakeMeta();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        $response->assertJsonApiFetchedResourceCollection($resourceFactory->toArray(), $strict);
    }
}
