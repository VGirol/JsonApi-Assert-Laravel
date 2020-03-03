<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Laravel\Generator;

class UpdatedResponseTest extends TestCase
{
    /**
     * @test
     * @dataProvider assertJsonApiUpdatedProvider
     */
    public function assertJsonApiUpdated($doc, $expected, $relationship, $strict)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiUpdated($expected, $relationship, $strict);
    }

    public function assertJsonApiUpdatedProvider()
    {
        $resourceFactory = (new Generator)->resourceObject()
            ->fake()
            ->addLink(Members::LINK_SELF, 'url');

        return [
            'with data' => [
                (new Generator)->document()
                    ->setData(
                        $resourceFactory
                    ),
                $resourceFactory->toArray(),
                false,
                false
            ],
            'with meta' => [
                (new Generator)->document()->fakeMeta(),
                null,
                false,
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

        $relationship = false;
        $strict = false;
        $failureMsg = '/' . str_replace('%s', '.*', Messages::DOCUMENT_TOP_LEVEL_MEMBERS) . '/';

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        $response->assertJsonApiUpdated($resourceFactory->toArray(), $relationship, $strict);
    }
}
