<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Model
 * @package App\Models
 *
 * @method static \Illuminate\Database\Eloquent\Model make(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder withGlobalScope($identifier, $scope)
 * @method static \Illuminate\Database\Eloquent\Builder withoutGlobalScope($scope)
 * @method static \Illuminate\Database\Eloquent\Builder withoutGlobalScopes(array $scopes = null)
 * @method static array removedScopes()
 * @method static \Illuminate\Database\Eloquent\Builder whereKey($id)
 * @method static \Illuminate\Database\Eloquent\Builder whereKeyNot($id)
 * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|static orWhere($column, $operator = null, $value = null)
 * @method static \Illuminate\Database\Eloquent\Collection hydrate(array $items)
 * @method static \Illuminate\Database\Eloquent\Collection fromQuery($query, $bindings = [])
 * @method static \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null find($id, $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection findMany($ids, $columns = ['*'])
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
 * @method static # findOrFail($id, $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Model findOrNew($id, $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Model firstOrNew(array $attributes, array $values = [])
 * @method static \Illuminate\Database\Eloquent\Model firstOrCreate(array $attributes, array $values = [])
 * @method static \Illuminate\Database\Eloquent\Model updateOrCreate(array $attributes, array $values = [])
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
 * @method static # firstOrFail($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Model|static|mixed firstOr($columns = ['*'], Closure $callback = null)
 * @method static mixed value($column)
 * @method static \Illuminate\Database\Eloquent\Collection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Model[] getModels($columns = ['*'])
 * @method static array eagerLoadRelations(array $models)
 * @method static \Illuminate\Database\Eloquent\Relations\Relation getRelation($name)
 * @method static \Generator cursor()
 * @method static bool chunkById($count, callable $callback, $column = null, $alias = null)
 * @method static \Illuminate\Support\Collection pluck($column, $key = null)
 * @throws \InvalidArgumentException
 * @method static # paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder forceCreate(array $attributes)
 * @method static void onDelete(\Closure $callback)
 * @method static mixed scopes(array $scopes)
 * @method static \Illuminate\Database\Eloquent\Builder|static applyScopes()
 * @method static \Illuminate\Database\Eloquent\Builder with($relations)
 * @method static \Illuminate\Database\Eloquent\Builder without($relations)
 * @method static \Illuminate\Database\Eloquent\Model newModelInstance($attributes = [])
 * @method static \Illuminate\Database\Query\Builder getQuery()
 * @method static \Illuminate\Database\Eloquent\Builder setQuery($query)
 * @method static \Illuminate\Database\Query\Builder toBase()
 * @method static array getEagerLoads()
 * @method static \Illuminate\Database\Eloquent\Builder setEagerLoads(array $eagerLoad)
 * @method static \Illuminate\Database\Eloquent\Model getModel()
 * @method static \Illuminate\Database\Eloquent\Builder setModel(Model $model)
 * @method static \Closure getMacro($name)
 *
 * @see \Illuminate\Database\Eloquent\Model
 * @see \Illuminate\Database\Eloquent\Builder
 * @see \Illuminate\Database\Query\Builder
 */
class Common extends Model
{
    public $timestamps = false;
    protected $fillable = [];
//    protected $table = '';
}
