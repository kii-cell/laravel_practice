<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            記事一覧
        </h2>

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
                    <form method="POST" action="{{ route('admin.tasks.download-csv') }}">
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
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">CSVダウンロード</button>
                    </form>


                    <form action="{{ route('admin.tasks.index') }}" method="GET" class="mb-6"
                        x-data="{ open: false }">
                        <!-- 上部バー -->
                        <div class="flex justify-between items-center mb-2">
                            <!-- 折りたたみトグル -->
                            <button type="button" @click="open = !open"
                                class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-200">
                                <span x-show="!open">検索条件を表示</span>
                                <span x-show="open">検索条件を閉じる</span>
                            </button>
                        </div>

                        <!-- 折りたたみ対象部分 -->
                        <div x-show="open" x-transition
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 border rounded-lg bg-gray-50">

                            <!-- キーワード -->
                            <div>
                                <label for="keyword" class="block text-sm font-medium text-gray-700">タイトル</label>
                                <input type="text" id="keyword" name="keyword"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="タイトルを検索" value="{{ request('keyword') }}">
                            </div>

                            <!-- 担当者 -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">担当者</label>
                                <select name="user_id" id="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">{{ empty(request('user_id')) ? '担当者' : '全て' }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- ステータス -->
                            <div>
                                <span class="block text-sm font-medium text-gray-700">ステータス</span>
                                <div class="mt-1 space-y-1">
                                    @foreach (config('const.task.status') as $key => $value)
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="status[]" value="{{ $key }}"
                                                class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                {{ in_array($key, (array) request('status')) ? 'checked' : '' }}>
                                            {{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 優先度 -->
                            <div>
                                <span class="block text-sm font-medium text-gray-700">優先度</span>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach (config('const.task.priority') as $key => $value)
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="priority[]" value="{{ $key }}"
                                                class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                {{ in_array($key, (array) request('priority')) ? 'checked' : '' }}>
                                            {{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 期間 From -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">対応期限
                                    (From)</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>

                            <!-- 期間 To -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">対応期限 (To)</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>

                            <!-- ボタン（右下配置） -->
                            <div class="col-span-full flex justify-end space-x-3">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    検索
                                </button>
                                <a href="{{ route('admin.tasks.index') }}"
                                    class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    リセット
                                </a>
                            </div>
                        </div>
                    </form>


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
