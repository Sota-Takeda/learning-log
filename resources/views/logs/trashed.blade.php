<x-app-layout>
    <h1 class="text-3xl font-bold text-gray-800 mb-4">削除済み一覧 🗑️</h1>

    <p class="mb-4">
        <a href="{{ route('logs.index') }}" class="underline">← 一覧へ戻る</a>
    </p>

    @if ($logs->count() === 0)
        <p>削除済みの記録はありません。</p>
    @else
        <ul class="space-y-2">
            @foreach ($logs as $log)
                <li class="bg-white p-3 rounded border">
                    <div class="text-sm text-gray-600">
                        {{ $log->studied_on->format('Y-m-d') }} / {{ $log->minutes }}分
                    </div>
                    <div class="font-semibold">{{ $log->title }}</div>

                    <form method="POST" action="{{ route('logs.restore', $log->id) }}" class="mt-2">
                        @csrf
                        @method('PATCH')
                        <button class="underline">復元</button>
                    </form>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    @endif
</x-app-layout>
