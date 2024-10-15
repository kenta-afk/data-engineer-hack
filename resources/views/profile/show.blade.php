<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-gray-800 dark:text-gray-300 text-lg">{{ $user->name }}</p>
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        <p>アカウント作成日時: {{ $user->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <p>friend: {{ $user->followers->count() }}</p> <!-- Updated to 'フォロワー数' -->

                    @if ($user->id !== auth()->id()) <!-- Check if not the authenticated user -->
                    @if ($user->followers->contains(auth()->id()) ) <!-- Check if already following -->
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

            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">友達リクエスト</h3> <!-- Title for Followers -->
            @foreach($user->followers as $follower) <!-- Iterate over the followers -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-gray-800 dark:text-gray-300 text-lg">
                        {{ $follower->name }} <!-- Display the follower's name -->
                    </p>
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        <p>アカウント作成日時: {{ $follower->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @if ($follower->id !== auth()->id()) <!-- Check if not the authenticated user -->
                    @if ($follower->followers->contains(auth()->id())) <!-- Check if already following -->
                    <form action="{{ route('friend.destroy', $follower) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">unFollow</button>
                    </form>
                    @else
                    <form action="{{ route('friend.store', $follower) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-blue-500 hover:text-blue-700">follow</button>
                    </form>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>