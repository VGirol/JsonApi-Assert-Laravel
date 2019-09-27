<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class DeletedTest extends TestCase
{
    /**
     * @test
     */
    public function responseDeleted()
    {
        $status = 200;
        $strict = false;
        $meta = [
            'message' => 'Deleting succeed'
        ];
        $content = [
            'meta' => $meta
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertIsDeletedResponse($response, $meta, $strict);
    }

    /**
     * @test
     * @dataProvider notValidResponseDeleted
     */
    public function responseDeletedFailed($status, $headers, $content, $meta, $strict, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertIsDeletedResponse($response, $meta, $strict);
    }

    public function notValidResponseDeleted()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        return [
            'wrong status code' => [
                404,
                $headers,
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                null,
                false,
                'Expected status code 200 but received 404.'
            ],
            'bad header' => [
                200,
                [],
                [
                    'meta' => [
                        'result' => 'it works'
                    ]
                ],
                null,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'structure not valid' => [
                200,
                $headers,
                [
                    'meta' => [
                        'result not safe' => 'failed'
                    ]
                ],
                null,
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'not allowed member' => [
                200,
                $headers,
                [
                    'data' => [
                        'type' => 'anything',
                        'id' => '1'
                    ],
                    'meta' => [
                        'result' => 'it works'
                    ]
                ],
                null,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no meta (structure not valid)' => [
                200,
                $headers,
                [
                    'jsonapi' => [
                        'version' => '1.0'
                    ]
                ],
                null,
                false,
                sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', ['data', 'errors', 'meta']))
            ],
            'meta not has expected' => [
                200,
                $headers,
                [
                    'meta' => [
                        'anything' => 'wrong'
                    ]
                ],
                [
                    'anything' => 'to see'
                ],
                false,
                null
            ]
        ];
    }
}
