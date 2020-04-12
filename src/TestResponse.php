<?php

namespace VGirol\JsonApiAssert\Laravel;

use Illuminate\Support\Arr;
use Illuminate\Testing\Assert;
use Illuminate\Testing\TestResponse as IlluminateTestResponse;

class TestResponse extends IlluminateTestResponse
{
    public const ERROR_STATUS = 'Expected status code %d but received %d.';

    public function assertStatus($status)
    {
        $actual = $this->getStatusCode();

        $message = sprintf(self::ERROR_STATUS, $status, $actual);
        if ($actual >= 300) {
            $data = $this->baseResponse->getData(true);
            if (Arr::exists($data, 'errors')) {
                $errors = Arr::get($data, 'errors');
                if (count($errors)) {
                    $error = Arr::first($errors);
                    if (Arr::exists($error, 'details')) {
                        $message .= "\n" . Arr::get($error, 'details');
                    }
                }
            }
        }

        Assert::assertSame(
            $actual,
            $status,
            $message
        );

        return $this;
    }
}
