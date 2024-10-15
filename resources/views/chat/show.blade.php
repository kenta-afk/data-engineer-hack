<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat with User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach($chats as $chat)
                        <div class="mb-4 flex {{ $chat->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs">
                                <!-- 名前をメッセージの上に固定 -->
                                <p class="text-sm font-bold mb-1 {{ $chat->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                    {{ $chat->sender_id === auth()->id() ? auth()->user()->name : $chat->sender->name }}
                                </p>
                                <!-- メッセージ内容 -->
                                <div class="{{ $chat->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-green-500 text-white' }} p-3 rounded-lg">
                                    <p class="text-sm">
                                        {{ $chat->message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- メッセージ送信フォーム -->
            <form action="{{ route('chat.send') }}" method="POST">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $receiverId }}">
                <textarea name="message" rows="4" class="w-full dark:bg-gray-800 dark:text-gray-200 rounded-md p-2"></textarea>
                <button type="submit" class="bg-blue-500 text-white p-2 mt-4 rounded-md w-full">Send</button>
            </form>
        </div>
    </div>
</x-app-layout>
