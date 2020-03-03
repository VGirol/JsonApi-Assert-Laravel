<?php

use Illuminate\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiCreated',
    /**
     * @param array   $expected The expected newly created resource object
     * @param boolean $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expected, $strict = false) {
        Assert::assertIsCreatedResponse($this, $expected, $strict);
    }
);
