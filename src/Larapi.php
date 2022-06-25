<?php

namespace Andr3a\Larapi;

use Illuminate\Database\Eloquent\Builder;

class Larapi
{
    public function filterIndex($model, $request, $customFilters = [])
    {
        try {
            $hiddenFields = (new $model())->getHidden();

            $query = $model::query();

            // $customFilters (e.g. ['is_online' => true])
            foreach ($customFilters as $key => $value) {
                $query->where($key, $value);
            }

            // where (e.g. ?where[firstname]=foo&where[lastname]=bar)
            if (isset($request->where)) {
                foreach ($request->where as $key => $value) {
                    $query->where($key, $value);
                }
            }

            // whereNot (e.g. ?whereNot[firstname]=foo&whereNot[firstname]=bar)
            if (isset($request->whereNot)) {
                foreach ($request->whereNot as $key => $value) {
                    $query->where($key, '!=', $value);
                }
            }

            // like (e.g. ?like[city]=erlin)
            if (isset($request->like)) {
                foreach ($request->like as $key => $value) {
                    $query->where($key, 'like', '%'.$value.'%');
                }
            }

            // startsWith (e.g. ?startsWith[city]=Berl)
            if (isset($request->startsWith)) {
                foreach ($request->startsWith as $key => $value) {
                    $query->where($key, 'like', $value.'%');
                }
            }

            // endsWith (e.g. ?endsWith[city]=lin)
            if (isset($request->endsWith)) {
                foreach ($request->endsWith as $key => $value) {
                    $query->where($key, 'like', '%'.$value);
                }
            }

            // higher (e.g. ?higher[age]=18)
            if (isset($request->higher)) {
                foreach ($request->higher as $key => $value) {
                    $query->where($key, '>', $value);
                }
            }

            // lower (e.g. ?lower[price]=250)
            if (isset($request->lower)) {
                foreach ($request->lower as $key => $value) {
                    $query->where($key, '<', $value);
                }
            }

            // only (e.g. ?only=firstname,lastname)
            if (isset($request->only)) {
                $cols = explode(',', $request->only);
                foreach ($cols as $key => $col) {
                    if (in_array($col, $hiddenFields)) {
                        unset($cols[$key]);
                    }
                }
                if (count($cols) > 0) {
                    $query->select($cols);
                }
            }

            // with (e.g. ?with=relationOne,relationTwo)
            if (isset($request->with)) {
                $rels = explode(',', $request->with);
                foreach ($rels as $rel) {
                    $query->with($rel);
                }
            }

            // has (e.g. ?has[category][color_id]=3)
            if (isset($request->has)) {
                foreach ($request->has as $haskey => $hasvalue) {
                    foreach ($hasvalue as $key => $value) {
                        $query->whereHas($haskey, function (Builder $q) use ($key, $value) {
                            $q->where($key, $value);
                        })->get();
                    }
                }
            }

            // hasLike (e.g. ?hasLike[category][name]=red)
            if (isset($request->hasLike)) {
                foreach ($request->hasLike as $haskey => $hasvalue) {
                    foreach ($hasvalue as $key => $value) {
                        $query->whereHas($haskey, function (Builder $q) use ($key, $value) {
                            $q->where($key, 'like', "%$value%");
                        })->get();
                    }
                }
            }

            // orderBy (e.g. ?orderBy=name|asc,email|desc,deleted_at)
            if (isset($request->orderBy)) {
                $cols = explode(',', $request->orderBy);
                foreach ($cols as $col) {
                    $vars = explode('|', $col);
                    $query->orderBy($vars[0], isset($vars[1]) ? $vars[1] : 'asc');
                }
            }

            // rand (e.g. ?rand=true)
            if (isset($request->rand) && $request->rand) {
                $query->inRandomOrder();
            }

            // paginate (e.g. ?paginate=10)
            if (isset($request->paginate)) {
                $data = $query->paginate($request->paginate);
            } else {
                // limit (e.g. ?limit=10)
                if (isset($request->limit)) {
                    $query->take($request->limit);
                }
                $data = $query->get();
            }

            return $data;
        } catch (\Throwable $th) {
            return null;
        }
    }


    public function filterShow($model, $request, $customFilters = [])
    {
        try {
            $hiddenFields = (new $model())->getHidden();

            $query = $model::query();

            $query->where('id', $request->id);

            // $customFilters (e.g. ['is_online' => true])
            foreach ($customFilters as $key => $value) {
                $query->where($key, $value);
            }

            // only (e.g. ?only=firstname,lastname)
            if (isset($request->only)) {
                $cols = explode(',', $request->only);
                foreach ($cols as $key => $col) {
                    if (in_array($col, $hiddenFields)) {
                        unset($cols[$key]);
                    }
                }
                if (count($cols) > 0) {
                    $query->select($cols);
                }
            }

            // with (e.g. ?with=relationOne,relationTwo)
            if (isset($request->with)) {
                $rels = explode(',', $request->with);
                foreach ($rels as $rel) {
                    $query->with($rel);
                }
            }

            return $query->first();
        } catch (\Throwable $th) {
            return null;
        }
    }
}
