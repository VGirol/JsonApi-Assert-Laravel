<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class ErrorsTest extends TestCase
{
    /**
     * @test
     */
    public function errorResponse()
    {
        $status = 406;
        $errors = [
            [
                'status' => strval($status),
                'title' => 'Not Acceptable',
                'details' => 'description'
            ]
        ];
        $content = [
            'errors' => $errors
        ];
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiErrorResponse($status, $errors);
    }

    /**
     * @test
     * @dataProvider errorResponseFailedProvider
     */
    public function errorResponseFailed($status, $headers, $content, $expectedStatus, $expectedErrors, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiErrorResponse($expectedStatus, $expectedErrors);
    }

    public function errorResponseFailedProvider()
    {
        return [
            'wrong status code' => [
                400,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                404,
                [],
                'Expected status code 404 but received 400.'
            ],
            'bad headers' => [
                404,
                [],
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                404,
                [],
                'Header [Content-Type] not present on response.'
            ],
            'not valid structure' => [
                404,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ],
                    'meta' => [
                        'key+' => 'not valid'
                    ]
                ],
                404,
                [],
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no errors member' => [
                404,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'meta' => [
                        'bad' => 'response'
                    ]
                ],
                404,
                [],
                sprintf(Messages::HAS_MEMBER, 'errors')
            ],
            'not enough errors' => [
                404,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                404,
                [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'details' => 'description'
                    ],
                    [
                        'status' => '405',
                        'title' => 'Not Found 2',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'expected error not present' => [
                404,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '410',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                404,
                [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'details' => 'description'
                    ]
                ],
                null
            ],
        ];
    }

    /**
     * @test
     */
    public function errorResponseWithInvalidArguments()
    {
        $status = 404;
        $headers = [
            'Content-Type' => [HttpHeader::MEDIA_TYPE]
        ];
        $content = [
            'errors' => [
                [
                    'status' => '404',
                    'title' => 'Not Found',
                    'details' => 'description'
                ]
            ]
        ];
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $expectedErrors = [
            'bad' => 'response'
        ];
        $this->setInvalidArgumentException(1, 'errors object', $expectedErrors);

        $response->assertJsonApiErrorResponse($status, $expectedErrors);
    }
}
