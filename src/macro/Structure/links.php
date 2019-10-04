<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiDocumentLinksObjectEquals',
    /**
     * @param array $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected) {
        Assert::assertDocumentLinksObjectEquals($this, $expected);
    }
);

TestResponse::macro(
    'assertJsonApiDocumentLinksObjectContains',
    /**
     * @param array $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected) {
        Assert::assertDocumentLinksObjectContains($this, $expected);
    }
);
