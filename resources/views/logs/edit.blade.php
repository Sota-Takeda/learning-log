<x-app-layout>
    <h1>学習ログ編集</h1>

    <form method="POST" action="{{ route('logs.update', $log) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        @endif

        <div>
            <label>学習日</label>
            <input type="date" name="studied_on" value="{{ old('studied_on', $log->studied_on->toDateString()) }}">
        </div>

        <div>
            <label>学習時間（分）</label>
            <input type="number" name="minutes" value="{{ old('minutes', $log->minutes) }}" min="1" max="1440">
        </div>

        <div>
            <label>タイトル</label>
            <input type="text" name="title" value="{{ old('title', $log->title) }}">
        </div>

        <div>
            <label>メモ</label>
            <textarea name="memo">{{ old('memo', $log->memo) }}</textarea>
        </div>

        <button type="submit">更新</button>
    </form>

    <p><a href="{{ route('logs.show', $log) }}">詳細へ戻る</a></p>
</x-app-layout>
