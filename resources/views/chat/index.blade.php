<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @foreach($users as $user)
            @if ($user->id !== auth()->id()) <!-- ログインユーザーをスキップ -->
                @php
                    // ログインユーザーが相手をフォローしているかチェック
                    $isFollowerApproved = $user->followers2->where('id', auth()->id())->first();
                    // 相手がログインユーザーをフォローしているかチェック
                    $isFollowingApproved = auth()->user()->followers2->where('id', $user->id)->first();
                @endphp
                
                @if ($isFollowerApproved && $isFollowingApproved 
                    && $isFollowerApproved->pivot->status === 'approved' 
                    && $isFollowingApproved->pivot->status === 'approved') <!-- 両方が承認された相互フォロー -->
                    
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <a href="{{ route('chat.show', ['user' => $user->id]) }}" class="text-gray-800 dark:text-gray-300 text-lg hover:underline">
                                    {{ $user->name }}
                                </a>

                                <!-- 各ユーザーの温度を表示 (初期値: 50℃) -->
                                <p>関係性温度: {{ $userTemperatures[$user->id] ?? 50 }}℃</p>

                                <!-- ブロック -->
                                <div class="flex">
                                    @if ($user->blocked->contains(auth()->id()))
                                        <form action="{{ route('block.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">ブロックを解除する</button>
                                        </form>
                                    @else
                                        <form action="{{ route('block.store', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-blue-500 hover:text-blue-700">ブロックする</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</x-app-layout>