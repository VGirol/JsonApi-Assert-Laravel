<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Factory\Options;
use VGirol\JsonApiFaker\Laravel\Generator;

class UpdatedTest extends TestCase
{
    /**
     * @test
     * @dataProvider responseUpdatedProvider
     */
    public function responseUpdated($content, $expected, $relationship, $strict)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertIsUpdatedResponse($response, $expected, $relationship, $strict);
    }

    public function responseUpdatedProvider()
    {
        $roFactory = (new Generator)->resourceObject()
            ->fake()
            ->fakeLinks();
        $riCollection = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_IDENTIFIER | Options::FAKE_COLLECTION);

        return [
            'with data' => [
                (new Generator)->document()
                    ->setData($roFactory)
                    ->toJson(),
                $roFactory->toArray(),
                false,
                false
            ],
            'with meta' => [
                (new Generator)->document()
                    ->fakeMeta()
                    ->toJson(),
                null,
                false,
                false
            ],
            'with relationship' => [
                (new Generator)->document()
                    ->setData($riCollection)
                    ->toJson(),
                $riCollection->toArray(),
                true,
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider responseUpdatedFailedProvider
     */
    public function responseUpdatedFailed($status, $headers, $content, $expected, $relationship, $strict, $failureMsg)
    {
        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        Assert::assertIsUpdatedResponse($response, $expected, $relationship, $strict);
    }

    public function responseUpdatedFailedProvider()
    {
        $roFactory = (new Generator)->resourceObject()
            ->fake();
        $riCollection = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_IDENTIFIER | Options::FAKE_COLLECTION);

        return [
            'wrong status code' => [
                202,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->setData($roFactory)->toJson(),
                $roFactory->toArray(),
                false,
                false,
                'Expected status code 200 but received 202.'
            ],
            'no content-type header' => [
                200,
                [],
                (new Generator)->document()->setData($roFactory)->toJson(),
                $roFactory->toArray(),
                false,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->setData($roFactory)->addToMeta('not safe', 'error')->toJson(),
                $roFactory->toArray(),
                false,
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no meta nor data member' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->fakeErrors()->toJson(),
                $roFactory->toArray(),
                false,
                false,
                sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', ['meta', 'data']))
            ],
            'data attributes member not valid' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->fakeData()->toJson(),
                $roFactory->toArray(),
                false,
                false,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
            ],
            'data relationship not valid' => [
                200,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
                ],
                (new Generator)->document()->fakeData()->toJson(),
                $riCollection->toArray(),
                true,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }
}
