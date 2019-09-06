<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiJsonapiObject', function ($expected) {
    Assert::assertResponseJsonapiObjectEquals($this, $expected);
});
