<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macro\Response;

use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Factory\Options;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedRelationshipsResponseTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedToOneRelationships()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $riFactory = (new Generator)->resourceIdentifier()
            ->fake();
        $doc = (new Generator)->document()
            ->setData($riFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($riFactory->toArray(), $strict);
    }

    /**
     * @test
     */
    public function responseFetchedToManyRelationships()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $riCollection = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_IDENTIFIER);
        $doc = (new Generator)->document()
            ->setData($riCollection);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($riCollection->toArray(), $strict);
    }

    /**
     * Response has no "data" member.
     * @test
     */
    public function assertJsonApiFetchedRelationshipsFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;
        $failureMsg = sprintf(Messages::HAS_MEMBER, Members::DATA);

        $riCollection = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_IDENTIFIER);
        $doc = (new Generator)->document()
            ->fakeMeta();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        $response->assertJsonApiFetchedRelationships($riCollection->toArray(), $strict);
    }
}
