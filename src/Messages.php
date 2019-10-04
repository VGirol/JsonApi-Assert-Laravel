<?php

declare(strict_types=1);

namespace VGirol\JsonApiAssert\Laravel;

/**
 * All the messages
 */
abstract class Messages
{
    const PAGINATION_META_IS_NOT_AS_EXPECTED =
    'Failed asserting that the "pagination" member of the "meta" object equals expected value.';
    const LOCATION_HEADER_IS_NOT_AS_EXPECTED =
    'Failed asserting that the "location" header equals expected value.';
    const META_OBJECT_IS_NOT_AS_EXPECTED =
    'Failed asserting that the "meta" object equals expected value.';
}
