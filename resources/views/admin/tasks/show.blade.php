<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            タスク詳細
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold">
                            {{ $task->title }}
                        </h3>
                    </div>
                    @php
                        $today = now();
                        $deadline = $task->deadline_at;
                        $diff = $today->diffInDays($deadline);
                    @endphp
                    <div class="flex gap-4">
                        <p><strong>ID:</strong> {{ $task->id }}</p>
                        <p><strong>ステータス:</strong> {{ config('const.task.status')[$task->status] }}</p>
                        <p><strong>優先度:</strong> {{ config('const.task.priority')[$task->priority] }}</p>
                    </div>
                    <div class="flex gap-4 mt-4">
                        <p><strong>担当者:</strong> {{ $user->name }}</p>
                        <p>
                            <strong>対応期限:</strong>
                            @if ($diff < 0)
                                <span class="text-red-600">期限切れ（{{ $deadline->format('Y/m/d') }}）</span>
                            @elseif ($diff <= 2 && $diff >= 1)
                                <span
                                    class="text-yellow-600">あと{{ $diff }}日（{{ $deadline->format('Y/m/d') }}）</span>
                            @elseif ($diff <= 1)
                                @php
                                    $diffInSeconds = $today->diffInSeconds($deadline);
                                    $hours = floor($diffInSeconds / 3600);
                                    $minutes = floor(($diffInSeconds % 3600) / 60);
                                    $seconds = $diffInSeconds % 60;
                                @endphp
                                <span
                                    class="text-yellow-600">あと{{ $hours }}時間{{ $minutes }}分（{{ $deadline->format('Y/m/d') }}）</span>
                            @else
                                <span class="text-green-600">{{ $deadline->format('Y/m/d') }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="mt-4">
                        <strong>本文:</strong>
                        <p class="mt-2 whitespace-pre-line">{{ $task->content }}</p>
                    </div>
                </div>
                <div class="bg-gray-200">
                    <div class="px-6 text-gray-800">
                        <p>作成日時: {{ $task->created_at }}</p>
                        <p>更新日時: {{ $task->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
