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
                    @if ($user->followers->contains(auth()->id())) <!-- Check if already following -->
                    <form action="{{ route('friend.destroy', $user) }}" method="POST" id="follow-form-{{ $user->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="unfollow-button text-red-500 hover:text-red-700" data-id="{{ $user->id }}">unFollow</button>
                    </form>
                    @else
                    <form action="{{ route('friend.store', $user) }}" method="POST" id="follow-form-{{ $user->id }}">
                        @csrf
                        <button type="button" class="follow-button text-blue-500 hover:text-blue-700" data-id="{{ $user->id }}">follow</button>
                    </form>
                    @endif
                    @endif
                </div>
            </div>

            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">友達リクエスト</h3> <!-- Title for Followers -->
            @foreach($user->followers as $follower) <!-- Iterate over the followers -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4" id="follower-{{ $follower->id }}">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-gray-800 dark:text-gray-300 text-lg">
                        {{ $follower->name }} <!-- Display the follower's name -->
                    </p>
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        <p>アカウント作成日時: {{ $follower->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @if ($follower->id !== auth()->id()) <!-- Check if not the authenticated user -->
                    @if ($follower->followers->contains(auth()->id())) <!-- Check if already following -->
                    <form action="{{ route('friend.destroy', $follower) }}" method="POST" id="follow-form-{{ $follower->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="unfollow-button text-red-500 hover:text-red-700" data-id="{{ $follower->id }}">unFollow</button>
                    </form>
                    @else
                    <form action="{{ route('friend.store', $follower) }}" method="POST" id="follow-form-{{ $follower->id }}">
                        @csrf
                        <button type="button" class="follow-button text-blue-500 hover:text-blue-700" data-id="{{ $follower->id }}">follow</button>
                    </form>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- jQuery Script for AJAX Follow/Unfollow -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // フォローボタンのクリックイベント
            $('.follow-button').on('click', function() {
                var userId = $(this).data('id');
                var formId = '#follow-form-' + userId;
                var formAction = $(formId).attr('action');
                var token = '{{ csrf_token() }}';

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: {
                        _token: token
                    },
                    success: function(response) {
                        // 成功時に要素を消す
                        $('#follower-' + userId).fadeOut();
                    },
                    error: function(xhr) {
                        alert('フォローに失敗しました。');
                    }
                });
            });
        });
    </script>
</x-app-layout>