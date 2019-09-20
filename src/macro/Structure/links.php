<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiDocumentLinksObjectEquals', function ($expected) {
    Assert::assertDocumentLinksObjectEquals($this, $expected);
});

TestResponse::macro('assertJsonApiDocumentLinksObjectContains', function ($expected) {
    Assert::assertDocumentLinksObjectContains($this, $expected);
});
