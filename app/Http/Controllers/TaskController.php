<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use App\Models\Task;
use App\Models\TaskMember;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $fields = $request->all();

            // Fix: Changed 'projectsId' to 'projectId'
            $errors = Validator::make($fields, [
                'name' => 'required|string',
                'projectId' => 'required|numeric',
                'memberIds' => 'required|array',
                'memberIds.*' => 'numeric|exists:members,id', // Ensuring each memberId exists in the members table
            ]);

            if ($errors->fails()) {
                return response($errors->errors()->all(), 422);
            }

            // Task creation
            $task = Task::create([
                'projectId' => $fields['projectId'],
                'name' => $fields['name'],
                'status' => Task::NOT_STARTED,
            ]);

            // Assign members to the task
            $members = $fields['memberIds'];
            foreach ($members as $memberId) {
                TaskMember::create([
                    'projectId' => $fields['projectId'],
                    'taskId' => $task->id,
                    'memberId' => $memberId, // Fix: Ensure correct memberId is used
                ]);
            }

            return response(['message' => 'Task created successfully!']);
        });
    }

    public function TaskToNotStartedToPending(Request $request)
    {
        Task::changeTaskStatus($request->taskId, Task::PENDING);

        return response(['message' => 'Task Moved To Pending'], 200);
    }
    public function TaskToNotStartedToCompleted(Request $request)
    {
        Task::changeTaskStatus($request->taskId, Task::COMPLETED);


        return response(['message' => 'Task Status Moved To Completed'], 200);
    }
    public function TaskToPendingToCompleted(Request $request)
    {
        Task::changeTaskStatus($request->taskId, Task::COMPLETED);


        return response(['message' => 'Task Status Moved To Completed'], 200);
    }
    public function TaskToPendingToNotStarted(Request $request)
    {
        Task::changeTaskStatus($request->taskId, Task::NOT_STARTED);


        return response(['message' => 'Task Status Moved To Not_Started'], 200);
    }
    public function TaskToCompletedToPending(Request $request)
    {
        Task::changeTaskStatus($request->taskId, Task::PENDING);


        return response(['message' => 'Task Status Moved To Pending'], 200);
    }
    public function TaskToCompletedToNotStarted(Request $request)
    {
        Task::changeTaskStatus($request->taskId, Task::NOT_STARTED);


        return response(['message' => 'Task Status Moved To Not Started'], 200);
    }
}
