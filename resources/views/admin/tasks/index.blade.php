<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            記事一覧
        </h2>
        <form action="{{ route('admin.tasks.index') }}" method="GET">
            @csrf
            <div class="flex flex-wrap items-center mb-4 gap-4">
                <!-- キーワード -->
                <input type="text" name="keyword" placeholder="タイトルを検索" value="{{ request('keyword') }}"
                    class="border border-gray-300 rounded-md px-2 py-1 text-left w-40">

                <!-- 担当者 -->
                <select name="user_id" class="border border-gray-300 rounded-md px-2 py-1 w-40">
                    <option value="">{{ empty(request('user_id')) ? '担当者' : '全て' }}</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <!-- ステータス（複数選択） -->
                <div class="mb-2">
                    <span class="font-semibold mr-2">ステータス:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach (config('const.task.status') as $key => $value)
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="status[]" value="{{ $key }}"
                                    {{ in_array($key, (array) request('status')) ? 'checked' : '' }}>
                                <span class="text-sm">{{ $value }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-2">
                    <span class="font-semibold mr-2">優先度:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach (config('const.task.priority') as $key => $value)
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="priority[]" value="{{ $key }}"
                                    {{ in_array($key, (array) request('priority')) ? 'checked' : '' }}>
                                <span class="text-sm">{{ $value }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>


                <!-- 期間 -->
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="border border-gray-300 rounded-md px-2 py-1 w-40">
                    <span>〜</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="border border-gray-300 rounded-md px-2 py-1 w-40">
                </div>

                <!-- ボタン -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-32">
                        検索
                    </button>
                    <a href="{{ route('admin.tasks.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-32 text-center">
                        リセット
                    </a>
                </div>
            </div>
        </form>

    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-4">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- 新規作成 -->
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.tasks.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-32 text-center">
                            新規作成
                        </a>
                    </div>

                    <!-- テーブル -->
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
                                        {{ optional($task->deadline_at)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ config('const.task.priority')[$task->priority] }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ config('const.task.status')[$task->status] }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ optional($task->updated_at)->format('Y-m-d H:i:s') ?? '未更新' }}
                                    </td>
                                    <td class="border px-4 py-2 flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.tasks.show', $task->id) }}"
                                            class="text-blue-600 hover:underline">詳細</a>
                                        <a href="{{ route('admin.tasks.edit', $task->id) }}"
                                            class="text-green-600 hover:underline">編集</a>
                                        <form action="{{ route('admin.tasks.delete', $task->id) }}" method="POST"
                                            onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:underline bg-transparent border-none cursor-pointer p-0 m-0">
                                                削除
                                            </button>
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
