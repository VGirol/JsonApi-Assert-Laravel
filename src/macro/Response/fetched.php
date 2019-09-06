<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiFetchedSingleResource', function ($expected, $strict = false) {
    Assert::assertFetchedSingleResourceResponse($this, $expected, $strict);
});

TestResponse::macro('assertJsonApiFetchedResourceCollection', function ($expected, $strict = false) {
    Assert::assertFetchedResourceCollectionResponse($this, $expected, $strict);
});

TestResponse::macro('assertJsonApiFetchedRelationships', function ($expected, $strict = false) {
    Assert::assertFetchedRelationshipsResponse($this, $expected, $strict);
});

TestResponse::macro('assertJsonApiPagination', function ($expectedLinks, $expectedMeta) {
    Assert::assertResponseHasPagination($this, $expectedLinks, $expectedMeta);
});

TestResponse::macro('assertJsonApiNoPagination', function () {
    Assert::assertResponseHasNoPagination($this);
});

// TestResponse::macro('assertJsonApiPaginationLinks', function ($expected) {
//     Assert::assertPaginationLinks($this, $expected);
// });

// TestResponse::macro(
//     'assertJsonApiNoPaginationLinks',
//     function () {
//         Assert::assertNoPaginationLinks($this);
//     }
// );

// TestResponse::macro('assertJsonApiRelationshipsLinks', function ($expected, $path = null) {
//     Assert::assertRelationshipsLinks($this, $expected, $path);
// });

// TestResponse::macro(
//    'assertResourceObjectContainsRelationship',
// function ($expectedCollection, $expectedResourceType, $expectedRelationshipName, $resource, $strict = false) {
//     Assert::assertResourceObjectContainsRelationship(
//     $this,
//     $expectedCollection,
//     $expectedResourceType,
//     $expectedRelationshipName,
//     $resource,
//     $strict
// );
// }
// );

TestResponse::macro('assertJsonApiIncludeContains', function ($expected) {
    Assert::assertResponseIncludeContains($this, $expected);
});
