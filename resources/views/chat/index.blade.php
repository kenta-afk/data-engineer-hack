<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User一覧') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @foreach($users as $user)
            @if ($user->id !== auth()->id())  <!-- ログインユーザーをスキップ -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <a href="{{ route('chat.show', ['user' => $user->id]) }}" class="text-gray-800 dark:text-gray-300 text-lg hover:underline">
                            {{ $user->name }}
                        </a>
                        <div class="text-gray-600 dark:text-gray-400 text-sm">
                            <p>アカウント作成日時: {{ $user->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</x-app-layout>
