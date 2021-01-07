<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertTaskToTodo;
use App\Http\Requests\CreateTask;
use App\Http\Requests\UpdateTask;
use App\Http\Resources\Task as TaskResource;
use App\Http\Resources\Todo as TodoResource;
use App\Models\Task;
use App\Models\Todo;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where('user_id', auth()->user()->id)->get();
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'message' => 'Resource successfully created.',
                    'data' => TaskResource::collection($tasks),
                ], 200,
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\CreateTask $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTask $request)
    {
        try {
            $payload = $request->validated();
            $user = auth()->user();
            $todo = Todo::firstWhere('hash', $payload['todo_id']);
            if (!$todo) {
                return response()
                    ->json(
                        [
                            'statusCode' => 404,
                            'message' => 'Todo resource not available.',
                        ], 404,
                    );
            }
            $task = Task::create(
                array_merge($payload, [ 'user_id' => $user->id, 'todo_id' => $todo->id, ]),
            );
            return response()
                ->json(
                    [
                        'statusCode' => 200,
                        'message' => 'Task resource successfully created.',
                        'data' => new TaskResource($task),
                    ], 200,
                );
        } catch (\Throwable $th) {
            return response()
                ->json(
                    [
                        'statusCode' => 500,
                        'message' => 'Task resource failed to create.',
                        'error' => "$th",
                    ], 500,
                );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'message' => 'Task resource retrieved successfully.',
                    'data' => new TaskResource($task),
                ], 200,
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTask $request, Task $task)
    {
        try {
            $payload = $request->validated();
            $task->update($payload);
            return response()
                ->json(
                    [
                        'statusCode' => 200,
                        'message' => 'Task resource updated successfully.',
                        'data' => new TaskResource($task),
                    ], 200,
                );
        } catch (\Throwable $th) {
            return response()
                ->json(
                    [
                        'statusCode' => 500,
                        'message' => 'Task resource failed to update.',
                        'error' => "$th",
                    ], 500,
                );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Task resource removed successfully.',
                ], 200,
            );
    }

    /**
     * Returns tasks grouped by their Todos
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function showByTodo(Todo $todo)
    {
        return response()
            ->json(
                [
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Task resource retrieved successfully.',
                    'data' => TaskResource::collection($todo->tasks)
                ], 200,
            );
    }

    /**
     * Returns tasks grouped by their Todos
     *
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function convertToTodo(ConvertTaskToTodo $request, Task $task)
    {
        try {
            $payload = $request->validated();
            $payload['user_id'] = auth()->user()->id;
            $payload['description'] = $task->description;
            $todo = Todo::create($payload);
            foreach ($payload['tasks'] ?? [] as $key => $t) {
                $t['todo_id'] = $todo->id;
                $t['user_id'] = $payload['user_id'];
                Task::create($t);
            }

            $task->delete();
            return response()
                ->json(
                    [
                        'statusCode' => 200,
                        'status' => true,
                        'message' => 'Task resource converted successfully.',
                        'data' => new TodoResource($todo)
                    ], 200,
                );
        } catch (\Throwable $th) {
            return response()
                ->json(
                    [
                        'statusCode' => 500,
                        'message' => 'Task resource failed to convert.',
                        'error' => "$th",
                    ], 500,
                );
        }
    }


    /**
     * Change the status of the resource
     *
     * @param \App\Models\Todo $todo
     *
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Task $task, $status = 'completed')
    {
        try {
            $date = new DateTime("now", new DateTimeZone('Africa/Lagos'));
            $task->update(
                [ 'completed_at' => $status === 'completed' ? $date : null ],
            );

            $updateTodoQuery = $request->todo ?? '';
            if ($updateTodoQuery === 'update') {
                Todo::query()
                    ->where('id', $task->todo_id)
                    ->update(
                        [ 'completed_at' => $status === 'completed' ? $date : null ]
                    );
            }

            return response()
                ->json(
                    [
                        'statusCode' => 202,
                        'status' => true,
                        'message' => 'Task resource updated successfully.',
                        'data' => new TaskResource($task),
                    ], 202,
                );
        } catch (\Throwable $th) {
            return response()
                ->json(
                    [
                        'statusCode' => 500,
                        'message' => 'Task resource failed to update.',
                        'error' => "$th",
                    ], 500,
                );
        }
    }
}
