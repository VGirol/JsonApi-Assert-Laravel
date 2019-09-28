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
    public function assertJsonApiUpdated($doc, $expected, $strict)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiUpdated($expected, $strict);
    }

    public function assertJsonApiUpdatedProvider()
    {
        $resourceFactory = (new Generator)->resourceObject()
            ->fake()
            ->addLink('self', 'url');

        return [
            'with data' => [
                (new Generator)->document()
                    ->setData(
                        $resourceFactory
                    ),
                $resourceFactory->toArray(),
                false
            ],
            'with meta' => [
                (new Generator)->document()->fakeMeta(),
                null,
                false
            ]
        ];
    }

    /**
     * Response has no "data" nor "meta" top-level member.
     *
     * @test
     */
    public function assertJsonApiUpdatedFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $resourceFactory = (new Generator)->resourceObject()
            ->fake();

        $doc = (new Generator)->document()
            ->fakeJsonapi();

        $strict = false;
        $failureMsg = '/' . str_replace('%s', '.*', Messages::TOP_LEVEL_MEMBERS) . '/';

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureExceptionRegex($failureMsg);

        $response->assertJsonApiUpdated($resourceFactory->toArray(), $strict);
    }
}
