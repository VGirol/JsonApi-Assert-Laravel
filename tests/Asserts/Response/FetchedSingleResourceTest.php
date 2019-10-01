<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedSingleResourceTest extends TestCase
{
    /**
     * @test
     * @dataProvider fetchedSingleResourceProvider
     */
    public function fetchedSingleResource($content, $expected)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertFetchedSingleResourceResponse($response, $expected, $strict);
    }

    public function fetchedSingleResourceProvider()
    {
        $roFactory = (new Generator)->resourceObject()
            ->fake();

        return [
            'resource object' => [
                (new Generator)->document()->setData($roFactory)->toJson(),
                $roFactory->toArray()
            ],
            'null' => [
                (new Generator)->document()->setData(null)->toJson(),
                null
            ]
        ];
    }

    /**
     * @test
     * @dataProvider fetchedSingleResourceFailedProvider
     */
    public function fetchedSingleResourceFailed($status, $headers, $content, $expected, $strict, $failureMsg)
    {
        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        Assert::assertFetchedSingleResourceResponse($response, $expected, $strict);
    }

    public function fetchedSingleResourceFailedProvider()
    {
        $roFactory = (new Generator)->resourceObject()->fake();

        return [
            'bad status' => [
                400,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->setData($roFactory)->toJson(),
                $roFactory->toArray(),
                true,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                200,
                [],
                (new Generator)->document()->setData($roFactory)->toJson(),
                $roFactory->toArray(),
                true,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->setData($roFactory)->setMeta(['not valid' => 'error'])->toJson(),
                $roFactory->toArray(),
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no data member' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->fakeMeta()->toJson(),
                $roFactory->toArray(),
                true,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'data attributes member not valid' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->fakeData()->toJson(),
                $roFactory->toArray(),
                true,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
            ]
        ];
    }
}
