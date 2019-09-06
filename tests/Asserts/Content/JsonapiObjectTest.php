<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class JsonapiObjectTest extends TestCase
{
    /**
     * @test
     */
    public function jsonapiObjectEquals()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $jsonapi = (new Generator)->jsonapiObject()
            ->fake()
            ->toArray();
        $content = [
            Members::JSONAPI => $jsonapi
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiJsonapiObject($jsonapi);
    }

    /**
     * @test
     * @dataProvider jsonapiObjectEqualsFailedProvider
     */
    public function jsonapiObjectEqualsFailed($content, $expected, $failureMsg)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiJsonapiObject($expected);
    }

    public function jsonapiObjectEqualsFailedProvider()
    {
        $jsonapi = (new Generator)
            ->jsonapiObject()
            ->fake()
            ->toArray();

        return [
            'no "jsonapi" member' => [
                [
                    'anything' => 'error'
                ],
                $jsonapi,
                sprintf(Messages::HAS_MEMBER, Members::JSONAPI)
            ],
            'not equals' => [
                [
                    Members::JSONAPI => [
                        'meta' => [
                            'anything' => 'error'
                        ]
                    ]
                ],
                $jsonapi,
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function jsonapiObjectEqualsInvalidArguments()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $jsonapi = (new Generator)->jsonapiObject()
            ->fake()
            ->toArray();
        $content = [
            Members::JSONAPI => $jsonapi
        ];
        $invalidExpected = 'error';

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setInvalidArgumentException(2, 'array', $invalidExpected);

        $response->assertJsonApiJsonapiObject($invalidExpected);
    }
}
