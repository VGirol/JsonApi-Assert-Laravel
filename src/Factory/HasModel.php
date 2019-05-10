<?php
declare (strict_types = 1);

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    /**
     * Undocumented variable
     *
     * @var Model
     */
    protected $model;

    /**
     * Undocumented function
     *
     * @param Model $model
     * @return static
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
