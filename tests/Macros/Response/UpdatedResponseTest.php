<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class UpdatedResponseTest extends TestCase
{
    /**
     * @test
     * @dataProvider assertJsonApiUpdatedProvider
     */
    public function assertJsonApiUpdated($content, $expected, $strict)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiUpdated($expected, $strict);
    }

    public function assertJsonApiUpdatedProvider()
    {
        $selfUrl = 'url';
        $additional = [
            'links' => [
                'self' => $selfUrl
            ]
        ];

        $resourceType = 'dummy';
        $model = $this->createModel();
        $content = [
            'data' => $this->createResource($model, $resourceType, false, null, $additional)
        ];

        $expected = (new Generator)->resourceObject($model, $resourceType)
            ->addLink('self', $selfUrl)
            ->toArray();

        return [
            'with data' => [
                $content,
                $expected,
                false
            ],
            'with meta' => [
                [
                    'meta' => [
                        'valid' => 'response'
                    ]
                ],
                null,
                false
            ]
        ];
    }

    /**
     * @test
     */
    public function assertJsonApiUpdatedFailed()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();

        $expected = (new Generator)->resourceObject($model, $resourceType)->toArray();

        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $content = [
            'data' => $this->createResource($model, $resourceType, false, 'structure', null)
        ];
        $strict = false;
        $failureMsg = Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiUpdated($expected, $strict);
    }

    public function responseUpdatedFailedProvider()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();

        $expected = (new Generator)->resourceObject($model, $resourceType)->toArray();

        return [
            'wrong status code' => [
                202,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'data' => $this->createResource($model, $resourceType, false, null, null)
                ],
                $expected,
                false,
                'Expected status code 200 but received 202.'
            ],
            'no content-type header' => [
                200,
                [],
                [
                    'data' => $this->createResource($model, $resourceType, false, null, null)
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'data' => $this->createResource($model, $resourceType, false, 'structure', null)
                ],
                $expected,
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'no meta nor data member' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '406',
                            'title' => 'Not Acceptable',
                            'details' => 'description'
                        ]
                    ]
                ],
                $expected,
                false,
                null
            ],
            'data attributes member not valid' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'data' => $this->createResource($model, $resourceType, false, 'value', null)
                ],
                $expected,
                false,
                null
            ]
        ];
    }
}
