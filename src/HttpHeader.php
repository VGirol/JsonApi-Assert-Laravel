<?php

namespace VGirol\JsonApiAssert\Laravel;

/**
 * This abstract class provides values for HTTP headers.
 */
abstract class HttpHeader
{
    const HEADER_NAME = "Content-Type";
    const MEDIA_TYPE = "application/vnd.api+json";
}
