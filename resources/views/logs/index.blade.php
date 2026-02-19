<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            学習記録一覧表 📝
        </h1>
        <form method="GET" action="{{ route('logs.index') }}" class="flex items-center gap-2">
            <input
                type="text"
                name="q"
                value="{{ $q ?? '' }}"
                placeholder="検索（タイトル/メモ）"
                class="border border-gray-300 px-3 py-1.5 rounded"
            >
            <button
                type="submit"
                class="border border-gray-700 text-gray-700 px-3 py-1.5 rounded hover:bg-gray-700 hover:text-white transition"
            >
                検索
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-gray-500">直近7日間の学習時間</p>
            <p class="text-sm text-gray-500">合計：{{ array_sum($weeklyMinutes) }} 分</p>
        </div>

        <div class="h-56">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const weeklyLabels = @json($weeklyLabels);
        const weeklyMinutes = @json($weeklyMinutes);

        const ctx = document.getElementById('weeklyChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: '分',
                    data: weeklyMinutes,
                    borderWidth: 2,
                    backgroundColor: '#DBEAFE'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 30 }
                    }
                }
            }
        });
    </script>

    <div class="mb-4">
        <a href="{{ route('logs.create') }}"
            class="inline-block bg-white border border-gray-800 text-gray-800 px-2 py-1 rounded hover:bg-gray-800 hover:text-white transition">
            記録を登録
        </a>
    </div>

    @if ($logs->count() === 0)
        <p>まだ記録はありません</p>
    @else
        <ul class="mb-4">
            @foreach ($logs as $log)
                <li class="mb-2">
                    {{ $log->studied_on->format('Y-m-d') }} /
                    {{ $log->minutes }}分 /
                    <a href="{{ route('logs.show', $log) }}">{{ $log->title }}</a>
                </li>
            @endforeach
        </ul>

        {{ $logs->links() }}
    @endif
</x-app-layout>
