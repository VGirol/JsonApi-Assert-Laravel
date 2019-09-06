<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedRelationshipsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedEmptyToManyRelationships()
    {
        $strict = false;
        $status = 200;
        $content = [
            'data' => []
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $resourceType = 'dummy';
        $expected = (new Generator)->riCollection(collect([]), $resourceType)->toArray();

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
     * @dataProvider responseFetchedToManyRelationshipsFailedProvider
     */
    public function responseFetchedToManyRelationshipsFailed(
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

    public function responseFetchedToManyRelationshipsFailedProvider()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $resourceType = 'dummy';
        $collection = $this->createCollection();
        $expected = (new Generator)->riCollection($collection, $resourceType)->toArray();

        return [
            'wrong status' => [
                400,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, $resourceType, true, null)
                ],
                $expected,
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                [
                    'data' => $this->createResourceCollection($collection, $resourceType, true, null)
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'not valid structure' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, $resourceType, true, null),
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
            'not valid collection' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, $resourceType, true, 'value')
                ],
                $expected,
                false,
                null
            ]
        ];
    }
}
