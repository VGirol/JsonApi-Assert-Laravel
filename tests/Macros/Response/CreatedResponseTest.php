<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class CreatedResponseTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiCreated()
    {
        $strict = false;
        $resourceType = 'dummy';
        $model = $this->createModel();
        $status = 201;
        $selfUrl = 'url';
        $content = [
            'data' => $this->createResource($model, $resourceType, false, null, [
                'links' => [
                    'self' => $selfUrl
                ]
            ])
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $expected = (new Generator)->resourceObject($model, $resourceType)
            ->addLink('self', $selfUrl)
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiCreated($expected, $strict);
    }

    /**
     * @test
     */
    public function assertJsonApiCreatedFailed()
    {
        $strict = false;
        $status = 201;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $resourceType = 'dummy';
        $model = $this->createModel();
        $selfUrl = 'url';
        $content = [
            'data' => $this->createResource($model, $resourceType, false, 'structure', [
                'links' => [
                    'self' => $selfUrl
                ]
            ])
        ];
        $expected = (new Generator)->resourceObject($model, $resourceType)
            ->addLink('self', $selfUrl)
            ->toArray();
        $failureMsg = Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiCreated($expected, $strict);
    }
}
