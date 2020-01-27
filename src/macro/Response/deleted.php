<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiDeleted',
    /**
     * @param array|null $expectedMeta If not null, it is the expected "meta" object.
     * @param boolean    $strict       If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedMeta = null, $strict = false) {
        Assert::assertIsDeletedResponse($this, $expectedMeta, $strict);
    }
);
