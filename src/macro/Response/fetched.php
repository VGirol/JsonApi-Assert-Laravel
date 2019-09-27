<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiFetchedSingleResource',
    /**
     * @param array<string, mixed> $expected The expected resource object
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected, $strict = false) {
        Assert::assertFetchedSingleResourceResponse($this, $expected, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiFetchedResourceCollection',
    /**
     * @param array<string, mixed> $expected The expected collection of resource objects.
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected, $strict = false) {
        Assert::assertFetchedResourceCollectionResponse($this, $expected, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiFetchedRelationships',
    /**
     * @param array<string, mixed>|null $expected The expected collection of resource identifier objects.
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected, $strict = false) {
        Assert::assertFetchedRelationshipsResponse($this, $expected, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiPagination',
    /**
     * @param array<string, mixed> $expectedLinks
     * @param array<string, mixed> $expectedMeta
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expectedLinks, $expectedMeta) {
        Assert::assertResponseHasPagination($this, $expectedLinks, $expectedMeta);
    }
);

TestResponse::macro(
    'assertJsonApiNoPagination',
    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function () {
        Assert::assertResponseHasNoPagination($this);
    }
);

TestResponse::macro('assertJsonApiIncludeContains', function ($expected) {
    Assert::assertResponseIncludeContains($this, $expected);
});
