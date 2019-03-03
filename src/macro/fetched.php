<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiFetchedSingleResource', function ($expectedModel, $resourceType) {
    AssertResponse::assertFetchedSingleResource($this, $expectedModel, $resourceType);
});

TestResponse::macro('assertJsonApiFetchedResourceCollection', function ($expectedCollection, $options) {
    AssertResponse::assertFetchedResourceCollection($this, $expectedCollection, $options);
});

TestResponse::macro('assertJsonApiPaginationLinks', function ($expected, $path = null) {
    AssertResponse::assertPaginationLinks($this, $expected, $path);
});