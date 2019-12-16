<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiUpdated',
    /**
     * @param array   $expected     The expected updated resource object
     * @param boolean $relationship If true, response content must be valid resource linkage
     * @param boolean $strict       If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected, $relationship = false, $strict = false) {
        Assert::assertIsUpdatedResponse($this, $expected, $relationship, $strict);
    }
);
