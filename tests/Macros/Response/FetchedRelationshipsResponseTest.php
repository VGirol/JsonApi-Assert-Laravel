<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macro\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedRelationshipsResponseTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedToOneRelationships()
    {
        $strict = false;
        $resourceType = 'dummy';
        $model = $this->createModel();
        $status = 200;
        $content = [
            'data' => $this->createResource($model, $resourceType, true, null)
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = (new Generator)->resourceIdentifier($model, $resourceType)->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($expected, $strict);
    }

    /**
     * @test
     */
    public function responseFetchedToManyRelationships()
    {
        $strict = false;
        $resourceType = 'dummy';
        $collection = $this->createCollection();
        $status = 200;
        $content = [
            'data' => $this->createResourceCollection($collection, $resourceType, true, null)
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = (new Generator)->riCollection($collection, $resourceType)->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($expected, $strict);
    }

    /**
     * @test
     */
    public function assertJsonApiFetchedRelationshipsFailed()
    {
        $resourceType = 'dummy';
        $collection = $this->createCollection();
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = (new Generator)->riCollection($collection, $resourceType)->toArray();

        $content = [
            'data' => $this->createResourceCollection($collection, $resourceType, true, null),
            'anything' => 'not valid'
        ];
        $strict = false;
        $failureMsg = Messages::ONLY_ALLOWED_MEMBERS;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedRelationships($expected, $strict);
    }
}
