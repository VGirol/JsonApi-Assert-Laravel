<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class ErrorTest extends TestCase
{
    /**
     * @test
     */
    public function assertIsErrorResponse()
    {
        $status = 406;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $errorFactory = (new Generator)->error()
            ->fake()
            ->set('status', strval($status))
            ->setMeta([
                'not strict' => 'error when infection change default value for $strict parameter'
            ]);
        $doc = (new Generator)->document()->AddError($errorFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertIsErrorResponse($response, $status, [$errorFactory->toArray()], $strict);
    }

    /**
     * @test
     * @dataProvider assertIsErrorResponseFailedProvider
     */
    public function assertIsErrorResponseFailed(
        $status,
        $headers,
        $content,
        $expectedStatus,
        $expectedErrors,
        $strict,
        $failureMsg
    ) {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertIsErrorResponse($response, $expectedStatus, $expectedErrors, $strict);
    }

    public function assertIsErrorResponseFailedProvider()
    {
        return [
            'bad status' => [
                412,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '406',
                            'title' => 'Not Acceptable',
                            'details' => 'description',
                            'meta' => [
                                'not strict' => 'error when infection change default value for $strict parameter'
                            ]
                        ]
                    ]
                ],
                406,
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                false,
                'Expected status code 406 but received 412.'
            ],
            'bad header' => [
                406,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE . '; param=value']
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
                406,
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                false,
                null
            ],
            'not valid structure' => [
                404,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => 404,
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ],
                    'meta' => [
                        'key+' => 'not valid'
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
                false,
                Messages::ERROR_STATUS_IS_NOT_STRING
            ],
            'no errors member' => [
                406,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'meta' => [
                        'key' => 'value'
                    ]
                ],
                406,
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                false,
                sprintf(Messages::HAS_MEMBER, 'errors')
            ],
            'no error' => [
                406,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => []
                ],
                406,
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                false,
                Messages::ERRORS_OBJECT_CONTAINS_NOT_ENOUGH_ERRORS
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
                false,
                Messages::ERRORS_OBJECT_CONTAINS_NOT_ENOUGH_ERRORS
            ],
            'expected error not present' => [
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
                        'details' => 'another description'
                    ]
                ],
                false,
                null
            ],
            'not strict' => [
                406,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '406',
                            'title' => 'Not Acceptable',
                            'details' => 'description',
                            'meta' => [
                                'not strict' => 'error'
                            ]
                        ]
                    ]
                ],
                406,
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description',
                        'meta' => [
                            'not strict' => 'error'
                        ]
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function errorResponseWithInvalidArguments()
    {
        $status = 404;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $doc = (new Generator)->document()->fakeErrors();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $expectedErrors = [
            'bad' => 'response'
        ];
        $this->setInvalidArgumentException(1, 'errors object', $expectedErrors);

        Assert::assertIsErrorResponse($response, $status, $expectedErrors, $strict);
    }
}
