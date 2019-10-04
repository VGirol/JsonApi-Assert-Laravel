<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\Messages;
use VGirol\JsonApiConstant\Members;

/**
 * This trait adds the ability to test pagination informations (links and meta).
 */
trait AssertPagination
{
    /**
     * Asserts that a document have no pagination links.
     *
     * @param TestResponse $response
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResponseHasNoPaginationLinks(TestResponse $response): void
    {
        // Decode JSON response
        $json = $response->json();

        if (!isset($json[Members::LINKS])) {
            static::assertNotHasMember(Members::LINKS, $json);

            return;
        }

        $links = $json[Members::LINKS];
        static::assertHasNoPaginationLinks($links);
    }

    /**
     * Asserts that a document have the expected pagination links.
     *
     * @param TestResponse $response
     * @param array<string, mixed> $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResponseHasPaginationLinks(TestResponse $response, $expected)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertHasLinks($json);
        $links = $json[Members::LINKS];
        static::assertPaginationLinksEquals($expected, $links);
    }

    /**
     * Asserts that a document have no pagination meta.
     *
     * @param TestResponse $response
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResponseHasNoPaginationMeta(TestResponse $response): void
    {
        // Decode JSON response
        $json = $response->json();

        if (!isset($json[Members::META])) {
            static::assertNotHasMember(Members::META, $json);

            return;
        }

        $meta = $json[Members::META];
        static::assertHasNoPaginationMeta($meta);
    }

    /**
     * Asserts that a document have pagination meta.
     *
     * @param TestResponse $response
     * @param array<string, mixed> $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResponseHasPaginationMeta(TestResponse $response, $expected)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertHasMeta($json);
        $meta = $json[Members::META];

        static::assertHasMember(Members::META_PAGINATION, $meta);

        $pagination = $meta[Members::META_PAGINATION];
        PHPUnit::assertEquals(
            $expected,
            $pagination,
            Messages::PAGINATION_META_IS_NOT_AS_EXPECTED
        );
    }

    /**
     * Asserts that a document have no pagination informations (links and meta).
     *
     * @param TestResponse $response
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResponseHasNoPagination(TestResponse $response)
    {
        static::assertResponseHasNoPaginationLinks($response);
        static::assertResponseHasNoPaginationMeta($response);
    }

    /**
     * Asserts that a document have pagination informations (links and meta).
     *
     * @param TestResponse $response
     * @param array<string, mixed> $expectedLinks
     * @param array<string, mixed> $expectedMeta
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertResponseHasPagination(TestResponse $response, $expectedLinks, $expectedMeta)
    {
        static::assertResponseHasPaginationLinks($response, $expectedLinks);
        static::assertResponseHasPaginationMeta($response, $expectedMeta);
    }
}
