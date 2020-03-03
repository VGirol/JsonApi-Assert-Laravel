<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Laravel\Generator;

class FetchedRelationshipsTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedEmptyToOneRelationships()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;
        $riFactory = (new Generator)->resourceIdentifier();
        $doc = (new Generator)->document()
            ->setData($riFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertFetchedRelationshipsResponse($response, $riFactory->toArray(), $strict);
    }

    /**
     * @test
     */
    public function responseFetchedToOneRelationships()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $riFactory = (new Generator)->resourceIdentifier()->fake();
        $doc = (new Generator)->document()
            ->setData($riFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertFetchedRelationshipsResponse($response, $riFactory->toArray(), $strict);
    }

    /**
     * @test
     * @dataProvider responseFetchedToOneRelationshipsFailedProvider
     */
    public function responseFetchedToOneRelationshipsFailed(
        $status,
        $headers,
        $content,
        $expected,
        $strict,
        $failureMsg
    ) {
        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        Assert::assertFetchedRelationshipsResponse($response, $expected, $strict);
    }

    public function responseFetchedToOneRelationshipsFailedProvider()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $riFactory = (new Generator)->resourceIdentifier()->fake();

        return [
            'wrong status' => [
                400,
                $headers,
                (new Generator)->document()->setData($riFactory)->toJson(),
                $riFactory->toArray(),
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                (new Generator)->document()->setData($riFactory)->toJson(),
                $riFactory->toArray(),
                false,
                'Header [Content-Type] not present on response.'
            ],
            'structure not valid' => [
                $status,
                $headers,
                (new Generator)->document()->setData($riFactory)->setMeta(['not+safe' => 'error'])->toJson(),
                $riFactory->toArray(),
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'no data member' => [
                $status,
                $headers,
                (new Generator)->document()->fakeMeta()->toJson(),
                $riFactory->toArray(),
                false,
                sprintf(Messages::HAS_MEMBER, Members::DATA)
            ],
            'resource linkage not valid' => [
                $status,
                $headers,
                (new Generator)->document()->fakeData()->toJson(),
                $riFactory->toArray(),
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }
}
