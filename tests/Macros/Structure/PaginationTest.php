<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Structure;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Members;
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

        $content = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->addToMeta(Members::META_PAGINATION, $meta)
            ->fakeLinks()
            ->addLinks($links)
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
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
        $content = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->fakeLinks()
            ->addToMeta(Members::META_PAGINATION, $expectedMeta)
            ->toArray();

        $failureMsg = null;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

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

        $content = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->fakeLinks()
            ->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
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
        $content = (new Generator)
            ->document()
            ->fakeData()
            ->fakeMeta()
            ->fakeLinks()
            ->addToMeta(Members::META_PAGINATION, ['key' => 'value'])
            ->toArray();

        $failureMsg = null;

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiNoPagination();
    }
}
