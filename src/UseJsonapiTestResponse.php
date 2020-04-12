<?php

namespace VGirol\JsonApiAssert\Laravel;

trait UseJsonapiTestResponse
{
    protected function createTestResponse($response)
    {
        return TestResponse::fromBaseResponse($response);
    }
}
