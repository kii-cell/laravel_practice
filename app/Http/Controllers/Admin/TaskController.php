<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('admin.tasks.index', compact('tasks'));
    }

    public function show($id)
    {
        $task = Task::findorFail($id);
        return view('admin.tasks.show', compact('task'));
    }

    public function create()
    {
        return view('admin.tasks.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validatePost($request);

        if ($validator->fails()) {
            return redirect(route('admin.tasks.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $task = new Task();
        $task->saveTask($request);

        return redirect(route('admin.tasks.index'))->with('success', 'タスクが正常に投稿されました。');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('admin.tasks.create', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validatePost($request);

        if ($validator->fails()) {
            return redirect(route('admin.tasks.create', $id))
                ->withErrors($validator)
                ->withInput();
        }

        $task = Task::findOrFail($id);
        $task->saveTask($request, now());

        return redirect(route('admin.tasks.index'))->with('success', 'タスクが正常に更新されました。');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect(route('admin.tasks.index'))->with('success', 'タスクが正常に削除されました。');
    }

    protected function validatePost(Request $request)
    {
        $rules = [
            'title' => 'required|max:100',
            'content' => 'required|max:1000',
            'deadline_at' => 'required|date_format:Y-m-d\TH:i',
            'support_at' => 'nullable|date_format:Y-m-d\TH:i',
            'priority' => 'required',
            'status' => 'required',
        ];

        $messages = [
            'title.required' => ':attributeは必須項目です。',
            'title.max' => ':attributeは:max文字以内で入力してください。',
            'content.required' => ':attributeは必須項目です。',
            'content.max' => ':attributeは:max文字以内で入力してください。',
            'deadline_at.required' => ':attributeは必須項目です。',
            'deadline_at.date_format' => ':attributeは正しい日時形式で入力してください。',
            'support_at.date_format' => ':attributeは正しい日時形式で入力してください。',
            'priority.required' => ':attributeは必須項目です。',
            'status.required' => ':attributeは必須項目です。',
        ];

        $attributes = [
            'title' => 'タイトル',
            'content' => '内容',
            'deadline_at' => '対応期限',
            'support_at' => '対応日時',
            'priority' => '優先度',
            'status' => 'ステータス',
        ];

        return Validator::make($request->all(), $rules, $messages, $attributes);
    }
}
