<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User一覧') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @foreach($users as $user)
        @if ($user->id !== auth()->id()) <!-- 自分以外のユーザー -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-gray-800 dark:text-gray-300 text-lg">
                        {{ $user->name }} <!-- ユーザーの名前 -->
                    </p>
                    
                    <!-- フォローの状態を確認 -->
                    @php
                        $isFollowing = $user->followers->contains(auth()->id()); 
                        $pendingRequest = $user->followers2->where('id', auth()->id())->first();
                    @endphp

                    <!-- フォロワーかどうかのチェック -->
                    @if ($isFollowing)
                        <form action="{{ route('friend.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">unFollow</button>
                        </form>
                    @elseif ($pendingRequest && $pendingRequest->pivot->status === 'pending')
                        <button type="button" class="text-blue-500 hover:text-blue-700">承認待ち</button>
                    @else
                        <form action="{{ route('friend.store', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="text-blue-500 hover:text-blue-700">follow</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</x-app-layout>
