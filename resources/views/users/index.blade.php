<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User一覧') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @foreach($users as $user)
        @if ($user->id !== auth()->id())<!-- ユーザーをループ -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-gray-800 dark:text-gray-300 text-lg">
                        {{ $user->name }} <!-- ユーザーの名前 -->
                    </p>
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        <p>アカウント作成日時: {{ $user->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    @if ($user->id !== auth()->id()) <!-- 自分以外のユーザーの場合 -->
                    @if ($user->followers->contains(auth()->id())) <!-- フォロワーリストに自分が含まれている場合 -->
                    <form action="{{ route('friend.destroy', $user) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">unFollow</button>
                    </form>
                    @else
                    <form action="{{ route('friend.store', $user) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="text-blue-500 hover:text-blue-700">follow</button>
                    </form>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</x-app-layout>