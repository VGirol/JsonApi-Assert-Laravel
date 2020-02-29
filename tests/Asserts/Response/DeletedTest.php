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

class DeletedTest extends TestCase
{
    /**
     * @test
     */
    public function responseDeleted()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $strict = false;

        $doc = (new Generator)->document()->fakeMeta();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        Assert::assertIsDeletedResponse($response, $doc->getMeta(), $strict);
    }

    /**
     * @test
     * @dataProvider notValidResponseDeleted
     */
    public function responseDeletedFailed($status, $headers, $content, $meta, $strict, $failureMsg)
    {
        $response = Response::create($content, $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure($failureMsg);

        Assert::assertIsDeletedResponse($response, $meta, $strict);
    }

    public function notValidResponseDeleted()
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        return [
            'wrong status code' => [
                404,
                $headers,
                (new Generator)->document()->fakeErrors()->toJson(),
                null,
                false,
                'Expected status code 200 but received 404.'
            ],
            'bad header' => [
                200,
                [],
                (new Generator)->document()->fakeMeta()->toJson(),
                null,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'structure not valid' => [
                200,
                $headers,
                (new Generator)->document()->setMeta(['not safe' => 'error'])->toJson(),
                null,
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS
            ],
            'not allowed member' => [
                200,
                $headers,
                (new Generator)->document()->fakeMeta()->fakeLinks()->toJson(),
                null,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no meta (structure not valid)' => [
                200,
                $headers,
                (new Generator)->document()->fakeJsonapi()->toJson(),
                null,
                false,
                sprintf(Messages::DOCUMENT_TOP_LEVEL_MEMBERS, implode('", "', [Members::DATA, Members::ERRORS, Members::META]))
            ],
            'meta not as expected' => [
                200,
                $headers,
                (new Generator)->document()->fakeMeta()->toJson(),
                [
                    'anything' => 'to see'
                ],
                false,
                LaravelMessages::META_OBJECT_IS_NOT_AS_EXPECTED
            ]
        ];
    }
}
