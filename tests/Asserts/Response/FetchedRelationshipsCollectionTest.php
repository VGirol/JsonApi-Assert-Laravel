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

class FetchedRelationshipsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedEmptyToManyRelationships()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $collectionFactory = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_IDENTIFIER, 0);
        $doc = (new Generator)->document()
            ->setData($collectionFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertFetchedRelationshipsResponse($response, $collectionFactory->toArray(), $strict);
    }

    /**
     * @test
     */
    public function responseFetchedToManyRelationships()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $collectionFactory = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_IDENTIFIER);
        $doc = (new Generator)->document()
            ->setData($collectionFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertFetchedRelationshipsResponse($response, $collectionFactory->toArray(), $strict);
    }

    /**
     * @test
     * @dataProvider responseFetchedToManyRelationshipsFailedProvider
     */
    public function responseFetchedToManyRelationshipsFailed(
        $status,
        $headers,
        $content,
        $expected,
        $strict,
        $failureMsg
    ) {
        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        Assert::assertFetchedRelationshipsResponse($response, $expected, $strict);
    }

    public function responseFetchedToManyRelationshipsFailedProvider()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $collectionFactory = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_IDENTIFIER);

        return [
            'wrong status' => [
                400,
                $headers,
                (new Generator)->document()->setData($collectionFactory)->toJson(),
                $collectionFactory->toArray(),
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                (new Generator)->document()->setData($collectionFactory)->toJson(),
                $collectionFactory->toArray(),
                false,
                'Header [Content-Type] not present on response.'
            ],
            'not valid structure' => [
                $status,
                $headers,
                (new Generator)->document()->setData($collectionFactory)->setMeta(['not safe' => 'error'])->toJson(),
                $collectionFactory->toArray(),
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no data member' => [
                $status,
                $headers,
                (new Generator)->document()->fakeMEta()->toJson(),
                $collectionFactory->toArray(),
                false,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'not valid collection' => [
                $status,
                $headers,
                (new Generator)->document()->fakeData()->toJson(),
                $collectionFactory->toArray(),
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }
}
