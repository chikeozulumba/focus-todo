<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTodo;
use App\Http\Requests\UpdateTodo;
use App\Models\Todo;
use App\Http\Resources\Todo as TodoResource;
use App\Models\Label;
use App\Models\Task;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TodosController extends Controller
{
    /**
     * Retrieve all todo resources
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $todos = Todo::where('user_id', auth()->user()->id)->get();
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'message' => 'Todo resource successfully retrieved.',
                    'data' => TodoResource::collection($todos),
                ], 200,
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTodo $request)
    {
        $payload = $request->validated();
        $payload['user_id'] = auth()->user()->id;
        $todo = Todo::create($payload);
        foreach ($payload['tasks'] ?? [] as $key => $task) {
            $task['todo_id'] = $todo->id;
            $task['user_id'] = $payload['user_id'];
            Task::create($task);
        }
        foreach ($payload['labels'] ?? [] as $key => $label) {
            $label = Label::query()->firstOrCreate(
                [
                    'display_title' => ucfirst($label),
                    'user_id' => $payload['user_id'],
                ],
                [
                    'display_title' => ucfirst($label),
                    'title' => '@' . $label,
                    'user_id' => $payload['user_id'],
                ]
            );

            $label->todos()->sync($todo->id);
        }
        return response()
            ->json(
                [
                    'statusCode' => 201,
                    'message' => 'Todo resource successfully created.',
                    'data' => new TodoResource($todo, [ "relations" => [ 'tasks', 'labels'] ]),
                ], 201,
            );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Todo $todo
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return $todo->load('labels');
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'message' => 'Todo resource retrieved successfully.',
                    'data' => new TodoResource($todo),
                ], 200,
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\UpdateTodo $request
     * @param \App\Models\Todo $todo
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTodo $request, Todo $todo)
    {
        $payload = $request->validated();
        $todo->update($payload);
        $user = auth()->user();
        foreach ($payload['tasks'] ?? [] as $task) {
            $task['todo_id'] = $todo->id;
            $task['user_id'] = $user->id;
            Task::query()->updateOrCreate([ 'hash' => $task['id'] ?? null, 'user_id' => $task['user_id'] ], $task);
        }
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'message' => 'Todo resource updated successfully.',
                    'data' => new TodoResource($todo),
                ], 200,
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Todo $todo
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->tasks()->delete();
        $todo->delete();
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Todo resource removed successfully.',
                ], 200,
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Todo $todo
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function task(Todo $todo, Task $task)
    {
        $todo->tasks()->delete();
        $todo->delete();
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Todo resource removed successfully.',
                ], 200,
            );
    }

    /**
     * Change the status of the resource
     *
     * @param \App\Models\Todo $todo
     *
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Todo $todo, $status = 'completed')
    {
        $user = auth()->user();
        $date = new DateTime("now", new DateTimeZone('Africa/Lagos'));
        $todo->update(
            [ 'completed_at' => $status === 'completed' ? $date : null ],
        );

        $taskQuery = $request->tasks ?? '';
        $tasksHashIds = explode(',', $taskQuery);
        if ($taskQuery === 'all') {
            Task::query()
                ->where('todo_id', $todo->id)
                ->update(
                    [ 'completed_at' => $status === 'completed' ? $date : null ]
                );
        }

        if (count($tasksHashIds) > 0) {
            foreach ($tasksHashIds as $ids) {
                Task::query()
                    ->where('todo_id', $todo->id)
                    ->where('user_id', $user->id)
                    ->update(
                        [ 'completed_at' => $status === 'completed' ? $date : null ]
                    );
            }
        }

        return response()
            ->json(
                [
                    'statusCode' => 202,
                    'status' => true,
                    'message' => 'Todo resource updated successfully.',
                    'data' => new TodoResource($todo),
                ], 202,
            );
    }

    /**
     * Change the priority of the resource
     *
     * @param \App\Models\Todo $todo
     *
     * @return \Illuminate\Http\Response
     */
    public function priority(Request $request, Todo $todo, $priority = 1)
    {
        $todo->update(
            [ 'priority' => (int) $priority ],
        );

        return response()
            ->json(
                [
                    'statusCode' => 202,
                    'status' => true,
                    'message' => 'Todo resource updated successfully.',
                    'data' => new TodoResource($todo),
                ], 202,
            );
    }
}
