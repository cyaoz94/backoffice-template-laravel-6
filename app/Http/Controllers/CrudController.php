<?php

namespace App\Http\Controllers;

use App\Filters\Filterable;
use Illuminate\Http\Request;

class CrudController extends Controller
{
    protected $modelClass;
    protected $resourceClass;
    protected $filterClass;
    protected $filter;

    public function __construct(Request $request)
    {
        // if the inherited class has a filter class
        // create a new instance of that filter class
        $this->filter = $this->filterClass
            ? new $this->filterClass($request)
            : null;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = $this->isClassFilterable() && !!$this->filter
            ? $this->modelClass::filter($this->filter)
            : $this->modelClass::query();

        $items = $query->paginate();

        return response()->json([
            'code' => 0,
            'data' => $this->resourceClass ? new $this->resourceClass($items) : $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'total' => $items->count(),
                'per_page' => $items->perPage(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $validationRules
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, array $validationRules = [])
    {
        $request->validate($validationRules);

        $this->modelClass::create($request);

        return response()->json([
            'code' => 0,
            'message' => 'Created Successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = $this->modelClass::findOrFail($id);

        return response()->json([
            'code' => 0,
            'data' => $this->resourceClass ? new $this->resourceClass($item) : $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @param array $validationRules
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, array $validationRules = [])
    {
        $request->validate($validationRules);

        $item = $this->modelClass::findOrFail($id);
        $item->fill($request->all());
        $item->save();

        return response()->json([
            'code' => 0,
            'message' => 'Updated Successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $item = $this->modelClass::findOrFail($id);
        $item->delete();

        return response()->json([
            'code' => 0,
            'message' => 'Deleted Successfully.',
        ]);
    }

    /**
     * checks if a class uses the Filterable trait
     * @return bool
     */
    private function isClassFilterable()
    {
        return in_array(Filterable::class, class_uses($this->modelClass));
    }
}
