<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Messages as LaravelMessages;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Laravel\Generator;

class CreatedTest extends TestCase
{
    /**
     * @test
     * @dataProvider responseCreatedProvider
     */
    public function responseCreated($withLocationHeader)
    {
        $status = 201;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $strict = false;

        $resFactory = (new Generator)->resourceObject()
            ->fake()
            ->fakeLinks();
        $doc = (new Generator)->document()
            ->setData($resFactory);

        if ($withLocationHeader) {
            $headers['Location'] = $resFactory->getLinks()[Members::LINK_SELF];
        }

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertIsCreatedResponse($response, $resFactory->toArray(), $strict);
    }

    public function responseCreatedProvider()
    {
        return [
            'with Location header' => [
                true
            ],
            'without Location header' => [
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidResponseCreated
     */
    public function responseCreatedFailed($code, $headers, $content, $expected, $strict, $failureMsg)
    {
        $response = Response::create($content, $code, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        Assert::assertIsCreatedResponse($response, $expected, $strict);
    }

    public function notValidResponseCreated()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $resFactory = (new Generator)->resourceObject()
            ->fake()
            ->fakeLinks();

        return [
            'wrong status code' => [
                200,
                $headers,
                (new Generator)->document()->setData($resFactory)->toJson(),
                $resFactory->toArray(),
                false,
                'Expected status code 201 but received 200.'
            ],
            'no content-type header' => [
                201,
                [],
                (new Generator)->document()->setData($resFactory)->toJson(),
                $resFactory->toArray(),
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                201,
                $headers,
                (new Generator)->document()->setData($resFactory)->setMeta(['not safe' => 'error'])->toJson(),
                $resFactory->toArray(),
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'no data' => [
                201,
                $headers,
                (new Generator)->document()->fakeMeta()->toJson(),
                $resFactory->toArray(),
                false,
                sprintf(Messages::HAS_MEMBER, Members::DATA)
            ],
            'data not valid' => [
                201,
                $headers,
                (new Generator)->document()->fakeData()->toJson(),
                $resFactory->toArray(),
                false,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
            ],
            'location header not valid' => [
                201,
                [
                    HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE],
                    'Location' => 'bad'
                ],
                (new Generator)->document()->setData($resFactory)->setMeta(['not safe' => 'error'])->toJson(),
                $resFactory->toArray(),
                false,
                LaravelMessages::LOCATION_HEADER_IS_NOT_AS_EXPECTED
            ]
        ];
    }
}
