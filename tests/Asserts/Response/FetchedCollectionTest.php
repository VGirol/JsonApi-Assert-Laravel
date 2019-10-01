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

class FetchedCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedCollection()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $collectionFactory = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_OBJECT);
        $doc = (new Generator)->document()
            ->setData($collectionFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertFetchedResourceCollectionResponse($response, $collectionFactory->toArray(), $strict);
    }

    /**
     * @test
     * @dataProvider responseFetchedCollectionFailedProvider
     */
    public function responseFetchedCollectionFailed(
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

        Assert::assertFetchedResourceCollectionResponse($response, $expected, $strict);
    }

    public function responseFetchedCollectionFailedProvider()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $collectionFactory = (new Generator)->collection()->fake(Options::FAKE_RESOURCE_OBJECT);

        return [
            'bad status' => [
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
            'no valid structure' => [
                $status,
                $headers,
                (new Generator)->document()->setData($collectionFactory)->setMeta(['not safe' => 'error]'])->toJson(),
                $collectionFactory->toArray(),
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no data member' => [
                $status,
                $headers,
                (new Generator)->document()->fakeMeta()->toJson(),
                $collectionFactory->toArray(),
                false,
                sprintf(Messages::HAS_MEMBER, 'data')
            ]
        ];
    }

    /**
     * @test
     */
    public function responseFetchedCollectionFailedNext()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $collectionfactory = (new Generator)->collection()
            ->fake(Options::FAKE_RESOURCE_OBJECT);
        $doc = (new Generator)->document()->fakeData();

        $failureMsg = $this->formatAsRegex(Messages::RESOURCE_IS_NOT_EQUAL);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        Assert::assertFetchedResourceCollectionResponse($response, $collectionfactory->toArray(), $strict);
    }
}
