<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Task;
use App\Models\User;

use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{

    public function top()
    {
        $allTasks = Task::all();
        $login_user = Auth::user();
        if ($allTasks['user_id'] = $login_user->id) {
            $tasks = Task::where('user_id', $login_user->id)->where('status', 1 || 2)->get();
        };

        return view('dashboard', isset($tasks) ? compact('tasks') : '');
    }
    public function index(Request $request)
    {
        $tasks = $this->getFilteredTasks($request);
        $users = User::all();

        return view('admin.tasks.index', compact('tasks', 'users'));
    }

    public function show($id)
    {
        $task = Task::findorFail($id);
        $user = $task->user;
        return view('admin.tasks.show', compact('task', 'user'));
    }

    public function create()
    {
        $user = User::with('tasks')->get();

        return view('admin.tasks.create', compact('user'));
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
        $user = User::all();
        return view('admin.tasks.create', compact('user', 'task'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validatePost($request);

        if ($validator->fails()) {
            return redirect(route('admin.tasks.edit', $id))
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
            'user_id' => 'required',
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
            'user_id.required' => ':attributeは必須項目です。',
        ];

        $attributes = [
            'title' => 'タイトル',
            'content' => '内容',
            'deadline_at' => '対応期限',
            'support_at' => '対応日時',
            'priority' => '優先度',
            'status' => 'ステータス',
            'user_id' => '担当者',
        ];

        return Validator::make($request->all(), $rules, $messages, $attributes);
    }
    public function downloadCsv(Request $request)
    {
        $tasks = $this->getFilteredTasks($request);

        // CSVヘッダー
        $csvData = [
            ['ID', 'タイトル', '担当者', '対応期限', '優先度', 'ステータス', '最終更新日時']
        ];

        // タスクを1行ずつ追加
        foreach ($tasks as $task) {
            $csvData[] = [
                $task->id,
                $task->title,
                $task->user->name ?? '',
                optional($task->deadline_at)->format('Y-m-d') ?? '',
                config('const.task.priority')[$task->priority] ?? '',
                config('const.task.status')[$task->status] ?? '',
                optional($task->updated_at)->format('Y-m-d H:i:s') ?? '',
            ];
        }

        // CSV文字列生成
        $csv = '';
        foreach ($csvData as $row) {
            $escaped = [];
            foreach ($row as $value) {
                $escaped[] = '\'' . str_replace('\'', '\'\'', $value) . '\'';
            }
            $csv .= implode(',', $escaped) . "\r\n";
        }

        $filename = 'tasks_export_' . date('YmdHis') . '.csv';
        $encodedCsv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');

        // CSV出力
        return response($encodedCsv, 200, [
            'Content-Type' => 'text/csv; charset=SJIS',
            'Content-Disposition' => "attachment; filename={$filename}"
        ]);
    }

    private function getFilteredTasks(Request $request)
    {
        $query = Task::query();

        if (!empty($request->keyword)) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        if (!empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if (!empty($request->status)) {
            $query->whereIn('status', $request->status);
        }

        if (!empty($request->priority)) {
            $query->whereIn('priority', $request->priority);
        }

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $query->whereBetween('deadline_at', [$request->start_date, $request->end_date]);
        } elseif (!empty($request->start_date)) {
            $query->where('deadline_at', '>=', $request->start_date);
        } elseif (!empty($request->end_date)) {
            $query->where('deadline_at', '<=', $request->end_date);
        }

        return $query->get();
    }
}
