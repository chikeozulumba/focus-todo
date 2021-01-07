<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignTodos;
use App\Http\Requests\CreateLabel;
use App\Http\Requests\UpdateLabel;
use App\Http\Resources\Label as LabelResource;
use App\Http\Resources\LabelWithoutTodos as LabelWithoutTodosResource;
use App\Models\Label;
use App\Models\Todo;
use Illuminate\Support\Str;

class LabelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label = Label::where('user_id', auth()->user()->id)->get();
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'message' => 'Label resource successfully retrieved.',
                    'data' => LabelResource::collection($label),
                ], 200,
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateLabel  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLabel $request)
    {
        try {
            $user = auth()->user();
            $payload = $request->validated();
            $payload['user_id'] = $user->id;
            $payload['display_title'] = ucfirst($payload['title']);
            $payload['title'] = '@' . Str::slug($payload['title'], '_');
            $label = Label::updateOrCreate([ 'title' => $payload['title'], 'user_id' => $user->id ], $payload);

            if ($payload['todos'] ?? null) {
                foreach ($payload['todos'] as $hash) {
                    $todo = Todo::firstWhere('hash', $hash);
                    $todo->labels()->sync($label->id);
                }
            }

            return response()
                ->json(
                    [
                        'statusCode' => 201,
                        'message' => 'Label resource created successfully.',
                        'data' => new LabelWithoutTodosResource($label),
                    ], 201,
                );
        } catch (\Throwable $th) {
            return response()
                    ->json(
                        [
                            'statusCode' => 500,
                            'message' => 'Label resource failed to update.',
                            'error' => "$th",
                        ], 500,
                    );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return response()
            ->json(
                [
                    'statusCode' => 201,
                    'message' => 'Label resource created successfully.',
                    'data' => new LabelResource($label),
                ], 201,
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLabel $request, Label $label)
    {
        try {
            $user = auth()->user();
            $payload = $request->validated();
            $payload['user_id'] = $user->id;

            if ($payload['title'] ?? null) {
                $payload['display_title'] = ucfirst($payload['title']);
                $payload['title'] = '@' . Str::slug($payload['title'], '_');
            }

            $label->update($payload);

            if ($payload['todos'] ?? null) {
                foreach ($payload['todos'] as $hash) {
                    $todo = Todo::firstWhere('hash', $hash);
                    $todo->labels()->sync($label->id);
                }
            }

            return response()
                ->json(
                    [
                        'statusCode' => 202,
                        'message' => 'Label resource updated successfully.',
                        'data' => new LabelWithoutTodosResource($label),
                    ], 202,
                );
        } catch (\Throwable $th) {
            return response()
                ->json(
                    [
                        'statusCode' => 500,
                        'message' => 'Label resource failed to update.',
                        'error' => "$th",
                    ], 500,
                );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        $label->todos()->detach();
        $label->delete();
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Label resource removed successfully.',
                ], 200,
            );
    }

    /**
     * Unassign Todo Resource from Label Resource
     *
     * @param \App\Models\Label $label
     *
     * @return \Illuminate\Http\Response
     */
    public function unAssignTodo(Label $label, Todo $todo)
    {
        $label->todos()->detach($todo->id);
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Label resource unassigned successfully.',
                ], 200,
            );
    }

    /**
     * Assign Label resource to a Todo resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function assignTodo(Label $label, Todo $todo)
    {
        $label->todos()->sync($todo->id);
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Label resource assigned successfully.',
                ], 200,
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function assignTodos(AssignTodos $request, Label $label)
    {
        $hash = $request->validated()['todos'] ?? [];
        $todosIds = Todo::query()
            ->where('user_id', auth()->user()->id)
            ->whereIn('hash', $hash)
            ->pluck('id');
        $label->todos()->sync($todosIds);
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Label resources assigned successfully.',
                ], 200,
            );
    }
}
