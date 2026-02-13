<x-app-layout>
    <h1>学習ログ詳細</h1>

    <p>学習日：{{ $log->studied_on->format('Y-m-d') }}</p>
    <p>学習時間：{{ $log->minutes }} 分</p>
    <p>タイトル：{{ $log->title }}</p>
    <p>メモ：{!! nl2br(e($log->memo)) !!}</p>

    <p>
        <a href="{{ route('logs.edit', $log) }}">編集</a>
    </p>

    <form method="POST" action="{{ route('logs.destroy', $log) }}">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('削除しますか？')">削除</button>
    </form>

    <p><a href="{{ route('logs.index') }}">一覧へ</a></p>
</x-app-layout>
