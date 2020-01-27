<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiResponse400',
    /**
     * @param array   $expectedErrors An array of the expected error objects.
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 400, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse403',
    /**
     * @param array   $expectedErrors An array of the expected error objects.
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 403, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse404',
    /**
     * @param array   $expectedErrors An array of the expected error objects.
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 404, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse406',
    /**
     * @param array   $expectedErrors An array of the expected error objects.
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 406, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse409',
    /**
     * @param array   $expectedErrors An array of the expected error objects.
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 409, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse415',
    /**
     * @param array   $expectedErrors An array of the expected error objects.
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 415, $expectedErrors, $strict);
    }
);
