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
                    <form action="{{ route('admin.tasks.index') }}" method="GET">
                        @csrf
                        <div class="flex items-left mb-4">
                            <div>
                                <input type="text" name="keyword" placeholder="タイトルを検索"
                                    value="{{ request('keyword') }}"
                                    class="border border-gray-300 rounded-md px-2 py-1 text-left w-40">
                            </div>
                            <div>
                                <select name="user_id" class="border border-gray-300 rounded-md px-1 py-1 w-40">
                                    @if (empty(request('user_id')))
                                        <option value="">担当者</option>
                                    @else
                                        <option value="">全て</option>
                                    @endif
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="status" class="border border-gray-300 rounded-md px-1 py-1 w-40">
                                    @if (empty(request('status')))
                                        <option value="">ステータス</option>
                                    @else
                                        <option value="">全て</option>
                                    @endif
                                    @foreach (config('const.task.status') as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <select name="priority" class="border border-gray-300 rounded-md px-1 py-1 w-40">
                                    @if (empty(request('priority')))
                                        <option value="">優先度</option>
                                    @else
                                        <option value="">全て</option>
                                    @endif
                                    @foreach (config('const.task.priority') as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ request('priority') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="border border-gray-300 rounded-md px-2 py-1 w-40">
                                <span>〜</span>

                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="border border-gray-300 rounded-md px-2 py-1 w-40">
                            </div>
                            <div>
                                <button type="submit"
                                    class="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">検索</button>
                                <a href="{{ route('admin.tasks.index') }}"
                                    class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">リセット</a>
                            </div>
                        </div>
                    </form>

                    <div class="flex justify-end mb-4">
                        <a class=" bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            href="{{ route('admin.tasks.create') }}">
                            新規作成
                        </a>
                    </div>

                    <table class="table-auto w-full border">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">タイトル</th>
                                <th class="border px-4 py-2">対応期限</th>
                                <th class="border px-4 py-2">優先度</th>
                                <th class="border px-4 py-2">ステータス</th>
                                <th class="border px-4 py-2">最終更新日時</th>
                                <th class="border px-4 py-2">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $val)
                                <tr>
                                    <td class="border px-4 py-2">{{ $val->id }}</td>
                                    <td class="border px-4 py-2">{{ $val->title }}</td>
                                    <td class="border px-4 py-2">
                                        {{ optional($val->deadline_at)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ config('const.task.priority')[$val->priority] }}</td>
                                    <td class="border px-4 py-2">
                                        {{ config('const.task.status')[$val->status] }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ optional($val->updated_at)->format('Y-m-d H:i:s') ?? '未更新' }}</td>
                                    <td class="border px-4 py-2 flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.tasks.show', $val->id) }}"
                                            class="text-blue-600 hover:underline">詳細</a>
                                        <a href="{{ route('admin.tasks.edit', $val->id) }}"
                                            class="ml-2 text-green-600 hover:underline">編集</a>
                                        <form action="{{ route('admin.tasks.delete', $val->id) }}" method="POST"
                                            onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:underline bg-transparent border-none cursor-pointer p-0 m-0">削除</button>
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
