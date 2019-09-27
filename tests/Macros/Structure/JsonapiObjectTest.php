<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Structure;

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

        $doc = (new Generator)->document()
            ->fakeJsonapi();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiJsonapiObject($doc->jsonapi->toArray());
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
        $content = [
            'anything' => 'error'
        ];

        $expected = (new Generator)
            ->jsonapiObject()
            ->fake()
            ->toArray();

        $failureMsg = sprintf(Messages::HAS_MEMBER, Members::JSONAPI);

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiJsonapiObject($expected);
    }
}
