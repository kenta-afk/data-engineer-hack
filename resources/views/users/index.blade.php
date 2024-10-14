<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User一覧') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @foreach($users as $user)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-gray-800 dark:text-gray-300 text-lg">
                        {{ $user->name }}
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        <p>アカウント作成日時: {{ $user->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    </p>
                    @if ($user->id !== auth()->id())
                    @if ($user->followers->contains(auth()->id()))
                    <form action="{{ route('friend.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">unFollow</button>
                    </form>
                    @else
                    <form action="{{ route('friend.store', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-blue-500 hover:text-blue-700">follow</button>
                    </form>
                    @endif

                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>