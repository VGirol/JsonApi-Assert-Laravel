<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Structure;

use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
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

        $response->assertJsonApiJsonapiObject($doc->getJsonapi()->toArray());
    }

    /**
     * @test
     */
    public function jsonapiObjectEqualsFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $doc = (new Generator)->document()->fakeMeta();

        $expected = (new Generator)
            ->jsonapiObject()
            ->fake()
            ->toArray();

        $failureMsg = sprintf(Messages::HAS_MEMBER, Members::JSONAPI);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        $response->assertJsonApiJsonapiObject($expected);
    }
}
