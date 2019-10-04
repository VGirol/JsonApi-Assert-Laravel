<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Structure;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiAssert\Messages;
use VGirol\JsonApiFaker\Laravel\Generator;

class PaginationTest extends TestCase
{
    /**
     * @test
     */
    public function assertJsonApiPagination()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $links = [
            Members::LINK_PAGINATION_LAST => 'url'
        ];
        $meta = ['key' => 'value'];

        $doc = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->addToMeta(Members::META_PAGINATION, $meta)
            ->fakeLinks()
            ->addLinks($links);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiPagination($links, $meta);
    }

    /**
     * @test
     */
    public function assertResponseHasPaginationFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $expectedLinks = [
            Members::LINK_PAGINATION_LAST => 'url'
        ];
        $expectedMeta = [
            'key' => 'value'
        ];
        $doc = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->fakeLinks()
            ->addToMeta(Members::META_PAGINATION, $expectedMeta);

        $failureMsg = Messages::PAGINATION_LINKS_NOT_EQUAL;

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        $response->assertJsonApiPagination($expectedLinks, $expectedMeta);
    }

    /**
     * @test
     */
    public function assertJsonApiNoPagination()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $doc = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->fakeLinks();

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiNoPagination();
    }

    /**
     * @test
     */
    public function assertResponseHasNoPaginationFailed()
    {
        $status = 200;
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];
        $doc = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->fakeLinks()
            ->addToMeta(Members::META_PAGINATION, ['key' => 'value']);

        $failureMsg = sprintf(Messages::NOT_HAS_MEMBER, Members::META_PAGINATION);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailure($failureMsg);

        $response->assertJsonApiNoPagination();
    }
}
