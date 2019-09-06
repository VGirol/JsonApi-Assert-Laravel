<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class CreatedTest extends TestCase
{
    /**
     * @test
     * @dataProvider responseCreatedProvider
     */
    public function responseCreated($withLocationHeader)
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
        if ($withLocationHeader) {
            $headers['Location'] = ['url'];
        }
        $expected = Generator::getInstance()->resourceObject($model, $resourceType)
            ->addLink('self', $selfUrl)
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiCreated($expected, $strict);
    }

    public function responseCreatedProvider()
    {
        return [
            'with Location header' => [
                true
            ],
            'without Location header' => [
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidResponseCreated
     */
    public function responseCreatedFailed($code, $headers, $content, $expected, $strict, $failureMsg)
    {
        $response = Response::create(json_encode($content), $code, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiCreated($expected, $strict);
    }

    public function notValidResponseCreated()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $resourceType = 'dummy';
        $model = $this->createModel();
        $selfUrl = 'url';
        $expected = Generator::getInstance()->resourceObject($model, $resourceType)
            ->addLink('self', $selfUrl)
            ->toArray();

        return [
            'wrong status code' => [
                200,
                $headers,
                [
                    'data' => $this->createResource($model, $resourceType, false, null, [
                        'links' => [
                            'self' => $selfUrl
                        ]
                    ])
                ],
                $expected,
                false,
                'Expected status code 201 but received 200.'
            ],
            'no content-type header' => [
                201,
                [],
                [
                    'data' => $this->createResource($model, $resourceType, false, null, [
                        'links' => [
                            'self' => $selfUrl
                        ]
                    ])
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                201,
                $headers,
                [
                    'data' => $this->createResource($model, $resourceType, false, 'structure', [
                        'links' => [
                            'self' => $selfUrl
                        ]
                    ])
                ],
                $expected,
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'no data' => [
                201,
                $headers,
                [
                    'meta' => [
                        'bad' => 'response'
                    ]
                ],
                $expected,
                false,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'data not valid' => [
                201,
                $headers,
                [
                    'data' => $this->createResource($model, $resourceType, false, 'value', [
                        'links' => [
                            'self' => $selfUrl
                        ]
                    ])
                ],
                $expected,
                false,
                null
            ],
            'location header not valid' => [
                201,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE],
                    'Location' => 'bad'
                ],
                [
                    'data' => $this->createResource($model, $resourceType, false, null, [
                        'links' => [
                            'self' => $selfUrl
                        ]
                    ])
                ],
                $expected,
                false,
                null
            ]
        ];
    }
}
