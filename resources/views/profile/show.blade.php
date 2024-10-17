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
                    <p class="text-lg">{{ $user->name }}</p>
                    <p>フォロワー数: {{ $user->followers->count() }}</p>

                    @if ($user->id !== auth()->id())
                        @if ($user->followers->contains(auth()->id()))
                            <form action="{{ route('friend.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700">unFollow</button>
                            </form>
                        @else
                            <form action="{{ route('friend.request', $user) }}" method="POST">
                                @csrf
                                <button class="text-blue-500 hover:text-blue-700">Follow</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            @if($user->receivedRequests->count() > 0)
                <h3 class="font-semibold text-lg">友達リクエスト</h3>
                @foreach ($user->receivedRequests as $request)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <p>{{ $request->follow->name }} さんがあなたをフォローしたいです。</p>
                            <form action="{{ route('friend.approve', $request) }}" method="POST">
                                @csrf
                                <button class="text-blue-500 hover:text-blue-700">承認する</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
