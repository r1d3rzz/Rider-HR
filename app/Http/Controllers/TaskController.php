<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function tasksRender(Request $request)
    {
        $project = Project::with('tasks')->where('id', $request->project_id)->firstOrFail();

        return view('components.task.tasksRender', [
            'project' => $project,
        ])->render();
    }

    public function tasksDraggable(Request $request)
    {
        $project = Project::with('tasks')->where('id', $request->project_id)->firstOrFail();

        $pendingTasks = explode(',', $request->pendingTasks);
        foreach ($pendingTasks as $key => $task_id) {
            $task = collect($project->tasks)->where('id', $task_id)->first();
            if ($task) {
                $task->serial_number = $key;
                $task->status = "pending";
                $task->update();
            }
        }

        $inProgressTasks = explode(',', $request->inProgressTasks);
        foreach ($inProgressTasks as $key => $task_id) {
            $task = collect($project->tasks)->where('id', $task_id)->first();
            if ($task) {
                $task->serial_number = $key;
                $task->status = "in_progress";
                $task->update();
            }
        }

        $completeTasks = explode(',', $request->completeTasks);
        foreach ($completeTasks as $key => $task_id) {
            $task = collect($project->tasks)->where('id', $task_id)->first();
            if ($task) {
                $task->serial_number = $key;
                $task->status = "complete";
                $task->update();
            }
        }

        return "success";
    }

    public function store(Request $request)
    {
        $task = new Task();
        $task->project_id = $request->project_id;
        $task->title = $request->title;
        $task->description = $request->description ? $request->description : "";
        $task->start_date = $request->start_date;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->status = $request->status;
        $task->save();

        if ($task->members) {
            $task->members()->sync($request->members);
        }

        return "success";
    }

    public function update($id, Request $request)
    {
        $task = Task::findOrFail($id);

        $task->title = $request->title;
        $task->description = $request->description ? $request->description : "";
        $task->start_date = $request->start_date;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->update();

        if ($task->members) {
            $task->members()->sync($request->members);
        }

        return "success";
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        if ($task->members) {
            $task->members()->detach();
        };
        $task->delete();

        return "success";
    }
}
