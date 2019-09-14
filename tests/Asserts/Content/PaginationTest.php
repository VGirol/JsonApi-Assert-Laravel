<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Members;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Factory\DocumentFactory;

class PaginationTest extends TestCase
{
    /**
     * @test
     */
    public function documentLinksObjectEquals()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $doc = (new DocumentFactory)
            ->fakeMeta()
            ->fakeLinks();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiDocumentLinksObjectEquals($doc->links);
    }

    /**
     * @test
     * @dataProvider documentLinksObjectEqualsFailedProvider
     */
    public function documentLinksObjectEqualsFailed($content, $expected, $failureMsg)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiDocumentLinksObjectEquals($expected);
    }

    public function documentLinksObjectEqualsFailedProvider()
    {
        $doc = (new DocumentFactory)
            ->fakeMeta()
            ->fakeLinks();

        return [
            'no "links" member' => [
                [
                    'anything' => 'error'
                ],
                $doc->links,
                sprintf(Messages::HAS_MEMBER, Members::LINKS)
            ],
            'not equals' => [
                [
                    Members::LINKS => [
                        'about' => 'url'
                    ]
                ],
                $doc->links,
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function documentLinksObjectEqualsInvalidArguments()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $doc = (new DocumentFactory)
            ->fakeMeta()
            ->fakeLinks();

        $invalidExpected = 'error';

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setInvalidArgumentException(2, 'array', $invalidExpected);

        $response->assertJsonApiDocumentLinksObjectEquals($invalidExpected);
    }

    /**
     * @test
     */
    public function documentLinksObjectContains()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $doc = (new DocumentFactory)
            ->fakeMeta()
            ->fakeLinks()
            ->addLink('test', 'url');

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiDocumentLinksObjectContains(['test' => 'url']);
    }

    /**
     * @test
     * @dataProvider documentLinksObjectContainsFailedProvider
     */
    public function documentLinksObjectContainsFailed($content, $expected, $failureMsg)
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiDocumentLinksObjectContains($expected);
    }

    public function documentLinksObjectContainsFailedProvider()
    {
        $doc = (new DocumentFactory)
            ->fakeMeta()
            ->fakeLinks();

        return [
            'no "links" member' => [
                [
                    'anything' => 'error'
                ],
                $doc->links,
                sprintf(Messages::HAS_MEMBER, Members::LINKS)
            ],
            'does not contain' => [
                [
                    Members::LINKS => [
                        'about' => 'url'
                    ]
                ],
                $doc->links,
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function documentLinksObjectContainsInvalidArguments()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $doc = (new DocumentFactory)
            ->fakeMeta()
            ->fakeLinks();

        $invalidExpected = 'error';

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setInvalidArgumentException(2, 'array', $invalidExpected);

        $response->assertJsonApiDocumentLinksObjectContains($invalidExpected);
    }
}
