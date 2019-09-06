<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedRelationshipsTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedEmptyToOneRelationships()
    {
        $status = 200;
        $content = [
            'data' => null
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships(null);
    }

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
     * @dataProvider responseFetchedToOneRelationshipsFailedProvider
     */
    public function responseFetchedToOneRelationshipsFailed(
        $status,
        $headers,
        $content,
        $expected,
        $strict,
        $failureMsg
    ) {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedRelationships($expected, $strict);
    }

    public function responseFetchedToOneRelationshipsFailedProvider()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $resourceType = 'dummy';
        $model = $this->createModel();
        $expected = (new Generator)->resourceIdentifier($model, $resourceType)->toArray();

        return [
            'wrong status' => [
                400,
                $headers,
                [
                    'data' => $this->createResource($model, $resourceType, true, null)
                ],
                $expected,
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                [
                    'data' => $this->createResource($model, $resourceType, true, null)
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'structure not valid' => [
                $status,
                $headers,
                [
                    'data' => $this->createResource($model, $resourceType, true, null),
                    'anything' => 'not valid'
                ],
                $expected,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no data member' => [
                $status,
                $headers,
                [
                    'errors' => [
                        [
                            'status' => '400'
                        ]
                    ]
                ],
                $expected,
                false,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'resource linkage not valid' => [
                $status,
                $headers,
                [
                    'data' => $this->createResource($model, $resourceType, true, 'value')
                ],
                $expected,
                false,
                null
            ]
        ];
    }
}
