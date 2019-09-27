<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedSingleResourceTest extends TestCase
{
    /**
     * @test
     */
    public function fetchedSingleResource()
    {
        $strict = false;
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

        Assert::assertFetchedSingleResourceResponse($response, $expected, $strict);
    }

    /**
     * @test
     * @dataProvider fetchedSingleResourceFailedProvider
     */
    public function fetchedSingleResourceFailed($status, $headers, $content, $expected, $strict, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertFetchedSingleResourceResponse($response, $expected, $strict);
    }

    public function fetchedSingleResourceFailedProvider()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();

        $expected = (new Generator)->resourceObject($model, $resourceType)->toArray();

        return [
            'bad status' => [
                400,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'data' => [
                        'type' => $resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes()
                    ]
                ],
                $expected,
                true,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                200,
                [],
                [
                    'data' => [
                        'type' => $resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes()
                    ]
                ],
                $expected,
                true,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'data' => [
                        'type' => $resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes(),
                        'anything' => 'not valid'
                    ]
                ],
                $expected,
                true,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no data member' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '400'
                        ]
                    ]
                ],
                $expected,
                true,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'data attributes member not valid' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'data' => [
                        'type' => $resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => [
                            'TST_ID' => $model->getKey(),
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 666,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                $expected,
                true,
                null
            ]
        ];
    }
}
