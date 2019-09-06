<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;

trait AssertPagination
{
    public static function assertResponseHasNoPaginationLinks(TestResponse $response): void
    {
        // Decode JSON response
        $json = $response->json();

        if (!isset($json[Members::LINKS])) {
            PHPUnit::assertTrue(true);

            return;
        }

        $links = $json[Members::LINKS];
        static::assertHasNoPaginationLinks($links);
    }

    public static function assertResponseHasPaginationLinks(TestResponse $response, $expected)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertHasLinks($json);
        $links = $json[Members::LINKS];
        static::assertPaginationLinksEquals($expected, $links);
    }

    public static function assertResponseHasNoPaginationMeta(TestResponse $response): void
    {
        // Decode JSON response
        $json = $response->json();

        if (!isset($json[Members::META])) {
            PHPUnit::assertTrue(true);

            return;
        }

        $meta = $json[Members::META];
        static::assertHasNoPaginationMeta($meta);
    }

    public static function assertResponseHasNoPagination(TestResponse $response)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertResponseHasNoPaginationLinks($response);

        if (isset($json[Members::META])) {
            $meta = $json[Members::META];
            JsonApiAssert::assertNotHasMember('pagination', $meta);
        }
    }

    public static function assertResponseHasPagination(TestResponse $response, $expectedLinks, $expectedMeta)
    {
        static::assertResponseHasPaginationLinks($response, $expectedLinks);
        static::assertPaginationMeta($response, $expectedMeta);
    }

    private static function assertPaginationMeta(TestResponse $response, $expected, $path = null)
    {
        // Decode JSON response
        $json = $response->json();

        if ($path !== null) {
            $json = static::getJsonFromPath($json, $path);
        }

        static::assertHasMeta($json);
        $meta = $json[Members::META];
        static::assertHasMember('pagination', $meta);
        $pagination = $meta['pagination'];
        PHPUnit::assertEquals($expected, $pagination);
    }
}
