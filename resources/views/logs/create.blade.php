<x-app-layout>
    <h1>学習ログ作成</h1>

    <form method="POST" action="{{ route('logs.store') }}">
        @csrf

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        @endif

        <div>
            <label>学習日</label>
            <input type="date" name="studied_on" value="{{ old('studied_on', now()->toDateString()) }}">
        </div>

        <div>
            <label>学習時間（分）</label>
            <input type="number" name="minutes" value="{{ old('minutes', 60) }}" min="1" max="1440">
        </div>

        <div>
            <label>タイトル</label>
            <input type="text" name="title" value="{{ old('title') }}">
        </div>

        <div>
            <label>メモ</label>
            <textarea name="memo">{{ old('memo') }}</textarea>
        </div>

        <button type="submit">保存</button>
    </form>

    <p><a href="{{ route('logs.index') }}">一覧へ</a></p>
</x-app-layout>
