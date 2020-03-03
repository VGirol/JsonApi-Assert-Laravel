<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
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
            ->set(Members::ERROR_STATUS, strval($status))
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

        $this->setAssertionFailure($failureMsg);

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
                    Members::ERRORS => [
                        [
                            Members::ERROR_STATUS => '406',
                            Members::ERROR_TITLE => 'Not Acceptable',
                            Members::ERROR_DETAILS => 'description',
                            Members::META => [
                                'not strict' => 'error when infection change default value for $strict parameter'
                            ]
                        ]
                    ]
                ],
                406,
                [
                    [
                        Members::ERROR_STATUS => '406',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
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
                    Members::ERRORS => [
                        [
                            Members::ERROR_STATUS => '406',
                            Members::ERROR_TITLE => 'Not Acceptable',
                            Members::ERROR_DETAILS => 'description'
                        ]
                    ]
                ],
                406,
                [
                    [
                        Members::ERROR_STATUS => '406',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                false,
                $this->formatAsRegex('Header [%s] was found, but value [%s] does not match [%s].')
            ],
            'not valid structure' => [
                404,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    Members::ERRORS => [
                        [
                            Members::ERROR_STATUS => 404,
                            Members::ERROR_TITLE => 'Not Found',
                            Members::ERROR_DETAILS => 'description'
                        ]
                    ],
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                404,
                [
                    [
                        Members::ERROR_STATUS => '404',
                        Members::ERROR_TITLE => 'Not Found',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_NOT_VALID . "\n" . Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'no errors member' => [
                406,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    Members::META => [
                        'key' => 'value'
                    ]
                ],
                406,
                [
                    [
                        Members::ERROR_STATUS => '406',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
                    ]
                ],
                false,
                sprintf(Messages::HAS_MEMBER, Members::ERRORS)
            ],
            'no error' => [
                406,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    Members::ERRORS => []
                ],
                406,
                [
                    [
                        Members::ERROR_STATUS => '406',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description'
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
                    Members::ERRORS => [
                        [
                            Members::ERROR_STATUS => '404',
                            Members::ERROR_TITLE => 'Not Found',
                            Members::ERROR_DETAILS => 'description'
                        ]
                    ]
                ],
                404,
                [
                    [
                        Members::ERROR_STATUS => '404',
                        Members::ERROR_TITLE => 'Not Found',
                        Members::ERROR_DETAILS => 'description'
                    ],
                    [
                        Members::ERROR_STATUS => '405',
                        Members::ERROR_TITLE => 'Not Found 2',
                        Members::ERROR_DETAILS => 'description'
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
                    Members::ERRORS => [
                        [
                            Members::ERROR_STATUS => '404',
                            Members::ERROR_TITLE => 'Not Found',
                            Members::ERROR_DETAILS => 'description'
                        ]
                    ]
                ],
                404,
                [
                    [
                        Members::ERROR_STATUS => '404',
                        Members::ERROR_TITLE => 'Not Found',
                        Members::ERROR_DETAILS => 'another description'
                    ]
                ],
                false,
                $this->formatAsRegex(Messages::ERRORS_OBJECT_DOES_NOT_CONTAIN_EXPECTED_ERROR)
            ],
            'not strict' => [
                406,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    Members::ERRORS => [
                        [
                            Members::ERROR_STATUS => '406',
                            Members::ERROR_TITLE => 'Not Acceptable',
                            Members::ERROR_DETAILS => 'description',
                            Members::META => [
                                'not strict' => 'error'
                            ]
                        ]
                    ]
                ],
                406,
                [
                    [
                        Members::ERROR_STATUS => '406',
                        Members::ERROR_TITLE => 'Not Acceptable',
                        Members::ERROR_DETAILS => 'description',
                        Members::META => [
                            'not strict' => 'error'
                        ]
                    ]
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
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
