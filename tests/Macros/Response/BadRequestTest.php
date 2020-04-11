<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Macros\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\HttpHeader;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiFaker\Laravel\Generator;

class BadRequestTest extends TestCase
{
    /**
     * @test
     * @dataProvider response4xxProvider
     */
    public function response4xx($status)
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $errorFactory = (new Generator)->error()
            ->fake()
            ->set(Members::ERROR_STATUS, strval($status))
            ->set(Members::ERROR_TITLE, JsonResponse::$statusTexts[$status])
            ->setMeta([
                'not strict' => 'error when infection change default value for $strict parameter'
            ]);
        $doc = (new Generator)->document()->AddError($errorFactory);

        $response = Response::create($doc->toJson(), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $fn = "assertJsonApiResponse{$status}";

        $response->{$fn}([$errorFactory->toArray()]);
    }

    /**
     * @test
     * @dataProvider response4xxProvider
     */
    public function response4xxFailed($status)
    {
        $headers = [
            HttpHeader::HEADER_NAME => [HttpHeader::MEDIA_TYPE]
        ];

        $errorFactory = (new Generator)->error()
            ->fake()
            ->set(Members::ERROR_STATUS, strval($status))
            ->set(Members::ERROR_TITLE, JsonResponse::$statusTexts[$status])
            ->setMeta([
                'not strict' => 'error when infection change default value for $strict parameter'
            ]);
        $doc = (new Generator)->document()->AddError($errorFactory);

        $response = Response::create($doc->toJson(), $status + 1, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setAssertionFailure();

        $fn = "assertJsonApiResponse{$status}";
        $response->{$fn}([$errorFactory->toArray()]);
    }

    public function response4xxProvider()
    {
        return [
            [400],
            [403],
            [404],
            [406],
            [409],
            [415]
        ];
    }
}
