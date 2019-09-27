<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedCollectionTest extends TestCase
{
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

        Assert::assertFetchedResourceCollectionResponse($response, $expected, $strict);
    }

    /**
     * @test
     * @dataProvider responseFetchedCollectionFailedProvider
     */
    public function responseFetchedCollectionFailed(
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

        Assert::assertFetchedResourceCollectionResponse($response, $expected, $strict);
    }

    public function responseFetchedCollectionFailedProvider()
    {
        $resourceType = 'dummy';
        $collection = $this->createCollection();
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expected = (new Generator)->roCollection($collection, $resourceType)->toArray();

        return [
            'bad status' => [
                400,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, $resourceType, false, null)
                ],
                $expected,
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                [
                    'data' => $this->createResourceCollection($collection, $resourceType, false, null)
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, $resourceType, false, null),
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
            ]
        ];
    }

    /**
     * @test
     */
    public function responseFetchedCollectionFailedNext()
    {
        $resourceType = 'dummy';
        $collection = $this->createCollection();
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $content = [
            'data' => $this->createResourceCollection($collection, $resourceType, false, 'value')
        ];
        $expected = (new Generator)->roCollection($collection, $resourceType)->toArray();
        $strict = false;
        $failureMsg = '/'.sprintf(preg_quote(Messages::RESOURCE_IS_NOT_EQUAL), '.*', '.*').'.*/s';

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureExceptionRegex($failureMsg);

        Assert::assertFetchedResourceCollectionResponse($response, $expected, $strict);
    }
}
