<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macro\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiFetchedSingleResource()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();

        $status = 200;
        $content = [
            'data' => [
                'type' => $resourceType,
                'id' => strval($model->getKey()),
                'attributes' => $model->toArray()
            ]
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $expected = (new Generator)->resourceObject($model, $resourceType)->toArray();

        $response->assertJsonApiFetchedSingleResource($expected);
    }

    /**
     * @test
     */
    public function assertJsonApiFetchedSingleResourceFailed()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();

        $expected = (new Generator)->resourceObject($model, $resourceType)->toArray();

        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $content = [
            'data' => [
                'type' => $resourceType,
                'id' => strval($model->getKey()),
                'attributes' => $model->getAttributes(),
                'anything' => 'not valid'
            ]
        ];
        $strict = true;
        $failureMsg = Messages::ONLY_ALLOWED_MEMBERS;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedSingleResource($expected);
    }

    /**
     * @test
     */
    public function responseFetchedCollection()
    {
        $strict = false;
        $resourceType = 'dummy';
        $collection = $this->createCollection();
        $status = 200;
        $content = [
            'data' => $this->createResourceCollection($collection, $resourceType, false, null)
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = (new Generator)->roCollection($collection, $resourceType)->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedResourceCollection($expected, $strict);
    }

    /**
     * @test
     */
    public function responseFetchedCollectionFailed()
    {
        $resourceType = 'dummy';
        $collection = $this->createCollection();
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = (new Generator)->roCollection($collection, $resourceType)->toArray();

        $content = [
            'data' => $this->createResourceCollection($collection, $resourceType, false, null),
            'anything' => 'not valid'
        ];
        $strict = false;
        $failureMsg = Messages::ONLY_ALLOWED_MEMBERS;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedResourceCollection($expected, $strict);
    }
}
