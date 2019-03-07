<?php

namespace VGirol\JsonApiAssert\Laravel;

use Illuminate\Http\JsonResponse;
use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiAssertCrud
{
    // public static function assertResourceIdentifierObjectEqualsModel($model, $resource)
    // {
    //     static::checkIfImplementsJsonApiModelInterface($model);

    //     PHPUnit::assertEquals(
    //         $model->getResourceType(),
    //         $resource['type']
    //     );

    //     PHPUnit::assertEquals(
    //         $model->getKey(),
    //         $resource['id']
    //     );
    // }

    // public static function assertResourceObjectEqualsModel($model, $resource)
    // {
    //     static::checkIfImplementsJsonApiModelInterface($model);

    //     static::assertResourceIdentifierObjectEqualsModel($model, $resource);

    //     JsonApiAssert::assertHasAttributes($resource);
    //     PHPUnit::assertEquals(
    //         $model->getAttributes(),
    //         $resource['attributes']
    //     );

    //     JsonApiAssert::assertHasLinks($resource);
    // }

    // public static function assertResourceObjectListEqualsCollection($collection, $data, $options)
    // {
    //     $options = static::mergeOptionsWithDefault($options);

    //     JsonApiAssert::assertIsArrayOfObjects($data);
    //     PHPUnit::assertEquals($options['dataCount'], count($data));

    //     list($dataIndex, $colIndex) = static::getListOfIndex($options);
    //     for ($i = 0; $i < count($dataIndex); $i++) {
    //         static::assertResourceObjectEqualsModel($collection[$colIndex[$i]], $data[$dataIndex[$i]]);
    //     }
    // }

    // public static function assertResponseMetaObjectSubset($expected, $json)
    // {
    //     JsonApiAssert::assertHasMeta($json);
    //     $meta = $json['meta'];
    //     PHPUnit::assertArraySubset($expected, $meta);
    // }

    // public static function assertResponseLinksObjectSubset($expected, $json)
    // {
    //     JsonApiAssert::assertHasLinks($json);
    //     $links = $json['links'];
    //     PHPUnit::assertArraySubset($expected, $links);
    // }

    public static function assertResponseLinksObjectContains($expected, $json)
    {
        JsonApiAssert::assertHasLinks($json);
        $links = $json['links'];
        foreach ($expected as $key => $value) {
            PHPUnit::assertArrayHasKey($key, $links);
            if (!is_null($links[$key])) {
                PHPUnit::assertStringContainsString($value, $links[$key]);
            }
        }
    }

    public static function assertResponseErrorObjectEquals($error, $statusCode)
    {
        PHPUnit::assertEquals(strval($statusCode), $error['status']);
        PHPUnit::assertEquals(JsonResponse::$statusTexts[$statusCode], $error['title']);
        JsonApiAssert::assertHasMember($error, 'details');
        if (config('app.debug')) {
            JsonApiAssert::assertHasMeta($error);
        }
    }

    // public static function assertResponseSingleResourceLinkageEquals($expected, $resLinkage)
    // {
    //     JsonApiAssert::assertIsValidResourceLinkage($resLinkage);
    //     JsonApiAssert::assertIsNotArrayOfObjects($resLinkage);
    //     static::assertResourceIdentifierObjectEqualsModel($expected, $resLinkage);
    // }

    // public static function assertResponseResourceLinkageListEqualsCollection($collection, $data, $options)
    // {
    //     $options = static::mergeOptionsWithDefault($options);

    //     JsonApiAssert::assertIsValidResourceLinkage($data);
    //     JsonApiAssert::assertIsArrayOfObjects($data);

    //     list($dataIndex, $colIndex) = static::getListOfIndex($options);
    //     for ($i = 0; $i < count($dataIndex); $i++) {
    //         static::assertResourceIdentifierObjectEqualsModel($collection[$colIndex[$i]], $data[$dataIndex[$i]]);
    //     }
    // }

    // public static function mergeOptionsWithDefault($options = [])
    // {
    //     foreach (static::getDefaultOptions() as $key => $value) {
    //         if (!isset($options[$key])) {
    //             $options[$key] = $value;
    //         }
    //     }

    //     $options['pageCount'] = self::getPageCount($options['colCount'], $options['itemPerPage']);
    //     $options['dataCount'] = self::getDataCount($options['pageCount'], $options['page'], $options['colCount'], $options['itemPerPage']);

    //     return $options;
    // }

    // private static function getDefaultOptions()
    // {
    //     return [
    //         'colCount' => null,
    //         'dataCount' => null,
    //         'pageCount' => null,
    //         'page' => 1,
    //         'itemPerPage' => config('json-api-paginate.max_results'),
    //         'resourceType' => null,
    //         'check-all' => true
    //     ];
    // }

    // private static function getPageCount($colCount, $itemPerPage)
    // {
    //     if (is_null($itemPerPage) || ($itemPerPage == 0)) {
    //         $pageCount = 1;
    //     } else {
    //         $pageCount = intdiv($colCount, $itemPerPage);
    //         if ($colCount % $itemPerPage != 0) {
    //             $pageCount++;
    //         }
    //     }

    //     return $pageCount;
    // }

    // private static function getDataCount($pageCount, $page, $colCount, $itemPerPage)
    // {
    //     if (is_null($pageCount)) {
    //         return null;
    //     }

    //     if ($pageCount > 1) {
    //         if ($page == $pageCount) {
    //             $dataCount = $colCount - ($page - 1) * $itemPerPage;
    //         } else {
    //             $dataCount = $itemPerPage;
    //         }
    //     } else {
    //         $dataCount = $colCount;
    //     }

    //     return $dataCount;
    // }

    // private static function getListOfIndex($options)
    // {
    //     if ($options['check-all']) {
    //         $min = ($options['page'] - 1) * $options['itemPerPage'];
    //         if ($options['pageCount'] > 1) {
    //             if ($options['page'] == $options['pageCount']) {
    //                 $nb = $options['colCount'] - ($options['page'] - 1) * $options['itemPerPage'];
    //             } else {
    //                 $nb = $options['itemPerPage'];
    //             }
    //         } else {
    //             $nb = $options['colCount'];
    //         }
    //         $dataIndex = range(0, $nb - 1, 1);
    //         $colIndex = range($min, $min + $nb - 1, 1);
    //     } else {
    //         $dataIndex = [mt_rand(0, $options['dataCount'] - 1)];
    //         $colIndex = [($options['page'] - 1) * $options['itemPerPage'] + $dataIndex];
    //     }

    //     return [$dataIndex, $colIndex];
    // }

    // private static function checkIfImplementsJsonApiModelInterface($model)
    // {
    //     if (!method_exists($model, 'getResourceType')) {
    //         throw new \Exception('Model object must implement "getResourceType" method.');
    //     }
    //     if (!method_exists($model, 'getKey')) {
    //         throw new \Exception('Model object must implement "getKey" method.');
    //     }
    // }
}
