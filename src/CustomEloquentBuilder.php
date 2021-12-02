<?php

namespace Noweh\EloquentDualDatabase;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CustomEloquentBuilder extends Builder
{
    /**
     * @param string[] $columns
     * @return CustomEloquentBuilder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function get($columns = ['*'])
    {
        return $this->safeCall(__FUNCTION__, func_get_args());
    }

    /**
     * @param mixed $id
     * @param string[] $columns
     * @return CustomEloquentBuilder|CustomEloquentBuilder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find($id, $columns = ['*'])
    {
        return $this->safeCall(__FUNCTION__, func_get_args());
    }

    /**
     * @param string[] $columns
     * @return CustomEloquentBuilder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function first($columns = ['*'])
    {
        return $this->safeCall(__FUNCTION__, func_get_args());
    }

    /**
     * @param string $column
     * @param null $key
     * @return \Illuminate\Database\Eloquent\Model|Collection|object|null
     */
    public function pluck($column, $key = null)
    {
        return $this->safeCall(__FUNCTION__, func_get_args());
    }

    /**
     * @param $methodName
     * @param $arguments
     * @return \Illuminate\Database\Eloquent\Model|object|null
     */
    public function safeCall($methodName, $arguments)
    {
        $query = $this->getQuery();
        $result = parent::$methodName(...$arguments);

        $isCollectionResult = $result instanceof Collection;

        // If we are already using the "Write connection",
        // or we got a non-empty result, we return it.
        if ($query->useWritePdo ||
            // If it's a collection, we check that it's not empty
            ($isCollectionResult && $result->isNotEmpty()) ||
            // If it's not a collection, it's a model, so we check it's not null
            (!$isCollectionResult && !is_null($result))
        ) {
            return $result;
        }

        $connectionName = $query->getConnection()->getConfig('name');

        // If the "Write connection" is the same that the "Read connection", return empty result
        if (connectionHasSameReadAndWrite($connectionName)) {
            return $result;
        }

        return $this->useWritePdo()->$methodName(...$arguments);
    }
}
