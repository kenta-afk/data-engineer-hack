<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User一覧') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @foreach($users as $user)
        @if ($user->id !== auth()->id()) <!-- ログインユーザーをスキップ -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('chat.show', ['user' => $user->id]) }}" class="text-gray-800 dark:text-gray-300 text-lg hover:underline">
                        {{ $user->name }}
                    </a>
                    
                    <!--ブロック-->
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
        @endforeach
    </div>
</x-app-layout>