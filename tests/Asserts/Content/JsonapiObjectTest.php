<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
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

        $doc = (new Generator)->document()
            ->fakeJsonapi();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertResponseJsonapiObjectEquals($response, $doc->jsonapi->toArray());
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

        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        Assert::assertResponseJsonapiObjectEquals($response, $expected);
    }

    public function jsonapiObjectEqualsFailedProvider()
    {
        $jsonapi = (new Generator)
            ->jsonapiObject()
            ->fake()
            ->toArray();

        return [
            'no "jsonapi" member' => [
                (new Generator)->document()->fakeMeta()->toJson(),
                $jsonapi,
                sprintf(Messages::HAS_MEMBER, Members::JSONAPI)
            ],
            'not equals' => [
                (new Generator)->document()->fakeJsonapi()->toJson(),
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

        $doc = (new Generator)->document()
            ->fakeJsonapi();

        $invalidExpected = 'error';

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setInvalidArgumentException(2, 'array', $invalidExpected);

        Assert::assertResponseJsonapiObjectEquals($response, $invalidExpected);
    }
}
