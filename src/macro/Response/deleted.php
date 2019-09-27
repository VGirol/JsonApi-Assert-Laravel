<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiDeleted',
    /**
     * @param array<string, mixed>|null $expectedMeta If not null, it is the expected "meta" object.
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expectedMeta = null, $strict = false) {
        Assert::assertIsDeletedResponse($this, $expectedMeta, $strict);
    }
);
