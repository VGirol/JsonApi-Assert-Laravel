<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedSingleResource()
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

        $expected = Generator::getInstance()->resourceObject($model, $resourceType)->toArray();

        $response->assertJsonApiFetchedSingleResource($expected);
    }

    /**
     * @test
     * @dataProvider responseFetchedSingleResourceFailedProvider
     */
    public function responseFetchedSingleResourceFailed($status, $headers, $content, $expected, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedSingleResource($expected);
    }

    public function responseFetchedSingleResourceFailedProvider()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();

        $expected = Generator::getInstance()->resourceObject($model, $resourceType)->toArray();

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
                null
            ]
        ];
    }
}
