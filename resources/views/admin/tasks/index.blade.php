<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            記事一覧
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- 検索フォーム --}}
                    <form method="GET" action="{{ route('admin.tasks.index') }}" class="mb-6">
                        <div class="flex flex-wrap gap-4 border-b pb-4 mb-4">
                            {{-- タイトル --}}
                            <div>
                                <label for="title" class="block text-sm">タイトル</label>
                                <input type="text" id="title" name="title" value="{{ request('title') }}"
                                    class="border rounded px-2 py-1">
                            </div>

                            {{-- 担当者 --}}
                            <div>
                                <label for="user_id" class="block text-sm">担当者</label>
                                <select id="user_id" name="user_id" class="border rounded px-2 py-1">
                                    <option value="">すべて</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ステータス --}}
                            <div class="border p-2">
                                <span class="block text-sm">ステータス</span>
                                <div class="flex flex-col gap-1">
                                    @foreach (config('const.task.status') as $key => $value)
                                        <label>
                                            <input type="checkbox" name="status[]" value="{{ $key }}"
                                                {{ in_array($key, (array) request('status')) ? 'checked' : '' }}>
                                            {{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- 優先度 --}}
                            <div class="border p-2">
                                <span class="block text-sm">優先度</span>
                                <div class="flex flex-col gap-1">
                                    @foreach (config('const.task.priority') as $key => $value)
                                        <label>
                                            <input type="checkbox" name="priority[]" value="{{ $key }}"
                                                {{ in_array($key, (array) request('priority')) ? 'checked' : '' }}>
                                            {{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- 期限 --}}
                            <div>
                                <label for="deadline_from" class="block text-sm">対応期限 (From)</label>
                                <input type="date" id="deadline_from" name="deadline_from"
                                    value="{{ request('deadline_from') }}" class="border rounded px-2 py-1">
                            </div>
                            <div>
                                <label for="deadline_to" class="block text-sm">対応期限 (To)</label>
                                <input type="date" id="deadline_to" name="deadline_to"
                                    value="{{ request('deadline_to') }}" class="border rounded px-2 py-1">
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded ">検索</button>
                            <a href="{{ route('admin.tasks.index') }}"
                                class="px-4 py-2 border rounded text-gray-700">リセット</a>
                        </div>
                    </form>

                    <div class="flex flex-col items-end gap-2 mb-4">
                        {{-- CSVダウンロード --}}
                        <form method="POST" action="{{ route('admin.tasks.download-csv') }}"
                            class="w-full flex justify-end">
                            @csrf
                            <input type="hidden" name="title" value="{{ request('title') }}">
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                            @foreach ((array) request('status') as $s)
                                <input type="hidden" name="status[]" value="{{ $s }}">
                            @endforeach
                            @foreach ((array) request('priority') as $p)
                                <input type="hidden" name="priority[]" value="{{ $p }}">
                            @endforeach
                            <input type="hidden" name="deadline_from" value="{{ request('deadline_from') }}">
                            <input type="hidden" name="deadline_to" value="{{ request('deadline_to') }}">
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                CSVダウンロード
                            </button>
                        </form>

                        {{-- 新規作成 --}}
                        <a href="{{ route('admin.tasks.create') }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            新規作成
                        </a>
                    </div>


                    {{-- テーブル --}}
                    <table class="table-auto w-full border">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">タイトル</th>
                                <th class="border px-4 py-2">担当者</th>
                                <th class="border px-4 py-2">対応期限</th>
                                <th class="border px-4 py-2">優先度</th>
                                <th class="border px-4 py-2">ステータス</th>
                                <th class="border px-4 py-2">最終更新日時</th>
                                <th class="border px-4 py-2">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="border px-4 py-2">{{ $task->id }}</td>
                                    <td class="border px-4 py-2">{{ $task->title }}</td>
                                    <td class="border px-4 py-2">{{ $task->user->name }}</td>
                                    <td class="border px-4 py-2">
                                        {{ optional($task->deadline_at)->format('Y-m-d H:i:s') }}</td>
                                    <td class="border px-4 py-2">{{ config('const.task.priority')[$task->priority] }}
                                    </td>
                                    <td class="border px-4 py-2">{{ config('const.task.status')[$task->status] }}</td>
                                    <td class="border px-4 py-2">
                                        {{ optional($task->updated_at)->format('Y-m-d H:i:s') ?? '未更新' }}</td>
                                    <td class="border px-4 py-2 flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.tasks.show', $task->id) }}"
                                            class="text-blue-600 hover:underline">詳細</a>
                                        <a href="{{ route('admin.tasks.edit', $task->id) }}"
                                            class="text-green-600 hover:underline">編集</a>
                                        <form action="{{ route('admin.tasks.delete', $task->id) }}" method="POST"
                                            onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">削除</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
