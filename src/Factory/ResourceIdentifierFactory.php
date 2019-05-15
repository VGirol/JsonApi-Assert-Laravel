<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApiAssert\Factory\ResourceIdentifierFactory as BaseFactory;

class ResourceIdentifierFactory extends BaseFactory
{
    use HasModel;

    /**
     * Undocumented function
     *
     * @param Model|null $model
     * @param string|null $resourceType
     */
    public function __construct($model, ?string $resourceType)
    {
        $this->setModel($model)
            ->setId(is_null($model) ? null : $model->getKey())
            ->setResourceType($resourceType);
    }
}
