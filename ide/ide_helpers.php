<?php
namespace {
    exit('This file should not be included, only analyzed by your IDE');
}

namespace Illuminate\Foundation\Testing {

    /**
     * @method void assertJsonApiCreated(Illuminate\Database\Eloquent\Model $model, string $resourceType, $strict = false)
     *      Asserts that the response has 201 status code and content with primary data corresponding to the provided model.
     *
     * @method void assertJsonApiDeleted(array $expectedMeta, boolean strict)
     *      Asserts that the response has 200 status code and content with only "meta" or "jsonapi" members.
     *
     * @method void assertJsonApiNoContent()
     *      Asserts that the response has 204 status code and no content.
     *
     * @method void assertJsonApiUpdated(Illuminate\Database\Eloquent\Model $model, string $resourceType, boolean $strict)
     *      Asserts that the response has 200 status code and content with primary data corresponding to the model if provided, or only "meta" member otherwise.
     *
     * @method void assertJsonApiErrorResponse(int $status, array $errors, boolean $strict)
     *      Asserts that the response has the given status code and the provided errors object.
     *
     * @method void assertJsonApiFetchedResourceCollection(Illuminate\Support\Collection $collection, string $resourceType, boolean $strict)
     *      Asserts that the response has 200 status code and content with primary datat corresponding to the provided collection and resource type.
     *
     * @method void assertJsonApiFetchedSingleResource(Illuminate\Database\Eloquent\Model $model, string $resourceType, boolean $strict)
     *      Asserts that the response has 200 status code and content with primary data corresponding to the provided model and resource type.
     *
     * @method void assertJsonApiNoContent()
     *      Asserts that the response has 204 status code and no content.
     *
     * @method void assertJsonApiFetchedRelationships(Illuminate\Support\Collection|Illuminate\Database\Eloquent\Model|null $expected, string $resourceType, $strict)
     *      Asserts that the response has 200 status code and content with primary data represented as resource identifier objects and corresponding to the provided collection or model and resource type.
     *
     * @method void assertJsonApiResponse400(array $errors)
     *      Asserts that the response has 400 status code and the provided errors objects.
     *
     * @method void assertJsonApiResponse403(array $errors)
     *      Asserts that the response has 403 status code and the provided errors objects.
     *
     * @method void assertJsonApiResponse404(array $errors)
     *      Asserts that the response has 404 status code and the provided errors objects.
     *
     * @method void assertJsonApiResponse406(array $errors)
     *      Asserts that the response has 406 status code and the provided errors objects.
     *
     * @method void assertJsonApiResponse409(array $errors)
     *      Asserts that the response has 409 status code and the provided errors objects.
     *
     * @method void assertJsonApiResponse415(array $errors)
     *      Asserts that the response has 415 status code and the provided errors objects.
     *
     *
     *
     * @method void assertJsonApiNoPaginationLinks() Asserts that the response has no pagination links.
     * @method void assertJsonApiPaginationLinks(array $expected) Asserts that the response has pagination links with a subset equal to the expected links provided.
     * @method void assertJsonApiRelationshipsLinks(array $expected, string $path) Asserts that the json object specified by the path has relationships links with a subset corresponding to the expected links provided.
     * @method void assertJsonApiRelationshipsObjectEquals(Illuminate\Support\Collection $expectedCollection, string $expectedResourceType, string $expectedRelationshipName, array $resource)
     */
    class TestResponse
    { }
}
