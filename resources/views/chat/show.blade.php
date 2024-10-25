@php
    // 温度が50℃でR: 217, G: 51, B: 63、-50℃でR: 128, G: 200, B: 239になるように補間する
    $r_high = 217; // 高温時の赤成分
    $g_high = 51;  // 高温時の緑成分
    $b_high = 63;  // 高温時の青成分

    $r_low = 128;  // 低温時の赤成分
    $g_low = 200;  // 低温時の緑成分
    $b_low = 239;  // 低温時の青成分

    // 温度に応じた補間（50℃で$r_high, -50℃で$r_low）
    $r = (int)(($r_high - $r_low) * ($answer + 50) / 100 + $r_low);
    $g = (int)(($g_high - $g_low) * ($answer + 50) / 100 + $g_low);
    $b = (int)(($b_high - $b_low) * ($answer + 50) / 100 + $b_low);

    $alpha = 0.8; // 透明度
    $backgroundColor = "rgba($r, $g, $b, $alpha)";
@endphp

<x-app-layout>
    <x-slot name="header">


        <form action="{{ route('chat.index') }}" method="GET">
            <button type="submit" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                戻る
            </button>
        </form>
    </x-slot>
    


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- 背景にぼかしを適用 -->
                <div class="absolute inset-0" style="background-color: {{ $backgroundColor }}; filter: blur(15px); z-index: 1;"></div>
                
                <!-- コンテンツはぼかさない -->
                <div class="relative p-6 text-gray-900 dark:text-gray-100" style="z-index: 2;">
                    <!-- チャット数の表示 -->
                    <div class="mb-4 text-center text-sm font-semibold">
                        <p>チャット数: {{ $chats->count() }}</p>
                        @if($daysSinceFirstChat !== null)
                        <p>初めてのチャットから経過した日数: {{ floor($daysSinceFirstChat) }} 日</p>
                        @else
                        <p>まだチャットがありません。</p>
                        @endif
                        <!-- 計算結果の表示 -->
                        <p>関係性温度: {{ $answer }}℃</p>
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                        @foreach($chats as $chat)
                        <div class="flex {{ $chat->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs {{ $chat->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                <!-- メッセージ送信者の名前 -->
                                <p class="text-sm font-bold mb-1">
                                    {{ $chat->sender_id === auth()->id() ? auth()->user()->name : $chat->sender->name }}
                                </p>
                                <!-- メッセージ内容 -->
                                <div class="{{ $chat->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-green-500 text-white' }} p-3 rounded-lg">
                                    <p class="text-sm">
                                        {{ $chat->message }}
                                    </p>
                                </div>
                                <div class="flex">
                                    @if ($chat->liked->contains(auth()->id()))
                                    <form action="{{ route('chats.dislike', $chat) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                                        <button type="submit" class="text-black-500 hover:text-red-700">
                                            dislike {{ $chat->liked->count() }}
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('chats.like', $chat) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                                        <button type="submit" class="text-black-500 hover:text-blue-700">
                                            like {{ $chat->liked->count() }}
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- メッセージ送信フォーム -->
                <form action="{{ route('chat.send') }}" method="POST" class="relative mt-4" style="z-index: 2;">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $receiverId }}">
                    <textarea name="message" rows="4" class="w-full dark:bg-gray-800 dark:text-gray-200 rounded-md p-2"></textarea>
                    <button type="submit" class="bg-blue-500 text-white p-2 mt-4 rounded-md w-full">Send</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
