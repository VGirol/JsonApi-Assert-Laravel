<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class BadRequestTest extends TestCase
{
    /**
     * @test
     */
    public function response406()
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
            'Content-Type' => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiResponse406($errors);
    }

    /**
     * @test
     * @dataProvider response406FailedProvider
     */
    public function response406Failed($status, $headers, $content, $errors, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiResponse406($errors);
    }

    public function response406FailedProvider()
    {
        return [
            'bad status' => [
                412,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE]
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
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'bad header' => [
                406,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE . '; param=value']
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
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'no errors member' => [
                406,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE]
                ],
                [],
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'no error' => [
                406,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => []
                ],
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
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
    public function response415()
    {
        $status = 415;
        $errors = [
            [
                'status' => strval($status),
                'title' => 'Unsupported Media Type',
                'details' => 'description'
            ]
        ];
        $content = [
            'errors' => $errors
        ];
        $headers = [
            'Content-Type' => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiResponse415($errors);
    }

    /**
     * @test
     * @dataProvider response415FailedProvider
     */
    public function response415Failed($status, $headers, $content, $errors, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiResponse415($errors);
    }

    public function response415FailedProvider()
    {
        return [
            'bad status' => [
                412,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE]
                ],
                [
                    'errors' => [
                        [
                            'status' => '415',
                            'title' => 'Unsupported Media Type',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '415',
                        'title' => 'Unsupported Media Type',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'bad header' => [
                415,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE . '; param=value']
                ],
                [
                    'errors' => [
                        [
                            'status' => '415',
                            'title' => 'Unsupported Media Type',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '415',
                        'title' => 'Unsupported Media Type',
                        'details' => 'description'
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function response404()
    {
        $status = 404;
        $errors = [
            [
                'status' => strval($status),
                'title' => 'Not Found',
                'details' => 'description'
            ]
        ];
        $content = [
            'errors' => $errors
        ];
        $headers = [
            'Content-Type' => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiResponse404($errors);
    }

    /**
     * @test
     * @dataProvider response404FailedProvider
     */
    public function response404Failed($status, $headers, $content, $errorDetails, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiResponse404($errorDetails);
    }

    public function response404FailedProvider()
    {
        return [
            'bad status' => [
                412,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE]
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
                'description',
                null
            ],
            'bad header' => [
                404,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE . '; param=value']
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
                'description',
                null
            ],
            'no errors member' => [
                404,
                [
                    'Content-Type' => [HttpHeader::MEDIA_TYPE]
                ],
                [],
                'description',
                null
            ]
        ];
    }
}
