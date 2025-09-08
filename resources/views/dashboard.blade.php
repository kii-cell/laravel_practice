<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    <div class="mt-4">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-2">担当タスク</h3>

                        <ul>
                            @if ($tasks)
                                @foreach ($tasks as $val)
                                    <li>
                                        {{ $val->deadline_at->format('Y/m/d') }} : {{ $val->title }}
                                    </li>
                                @endforeach
                            @else
                                <li>現在、該当するタスクはありません。</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
