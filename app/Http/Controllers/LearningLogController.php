<?php

namespace App\Http\Controllers;

use App\Models\LearningLog;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLearningLogRequest;
use App\Http\Requests\UpdateLearningLogRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LearningLogController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $userId = $request->user()->id;

        $logs = LearningLog::query()
            ->ownedBy($userId)
            ->search($q)
            ->latestStudied()
            ->paginate(10)
            ->withQueryString();

        $todayTotalMinutes = LearningLog::query()->todayTotalMinutes($userId);

        // 直近7日（今日含む）
        $start = Carbon::today()->subDays(6); // 6日前〜今日 = 7日分
        $end = Carbon::today();

        // DBから日別合計を取得（studied_on は date cast済み）
        $rows = LearningLog::query()
            ->ownedBy($userId)
            ->whereBetween('studied_on', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('DATE(studied_on) as d, SUM(minutes) as total')
            ->groupBy('d')
            ->pluck('total', 'd'); // ['2026-02-08' => 120, ...]

        // 0埋めして配列化（グラフ用）
        $weeklyLabels = [];
        $weeklyMinutes = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i)->toDateString(); // YYYY-MM-DD
            $weeklyLabels[] = Carbon::parse($date)->format('m/d');
            $weeklyMinutes[] = (int) ($rows[$date] ?? 0);
        }

        return view('logs.index', compact(
            'logs', 'q', 'todayTotalMinutes',
            'weeklyLabels', 'weeklyMinutes'
        ));
    }

    public function create()
    {
        return view('logs.create');
    }

    public function store(StoreLearningLogRequest $request)
    {
        $log = LearningLog::create([
            'user_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        return redirect()->route('logs.show', $log)
    ->with('success', '学習ログを保存しました');
    }

    public function show(LearningLog $log)
    {
        $this->authorize('view', $log);
        return view('logs.show', compact('log'));
    }

    public function edit(LearningLog $log)
    {
        $this->authorize('update', $log);
        return view('logs.edit', compact('log'));
    }

    public function update(UpdateLearningLogRequest $request, LearningLog $log)
    {
        $this->authorize('update', $log);

        $log->update($request->validated());
        return redirect()->route('logs.show', $log)
    ->with('success', '学習ログを更新しました');
    }

    public function destroy(LearningLog $log)
    {
        $this->authorize('delete', $log);

        $log->delete();
        return redirect()->route('logs.index', $log)
    ->with('success', '学習ログを削除しました');
    }

    public function trashed(Request $request)
    {
    $userId = $request->user()->id;

    $logs = LearningLog::onlyTrashed()
        ->ownedBy($userId)
        ->latestStudied()
        ->paginate(10)
        ->withQueryString();

    return view('logs.trashed', compact('logs'));
    }

    public function restore(Request $request, int $id)
    {
        $userId = $request->user()->id;

        $log = LearningLog::onlyTrashed()
            ->ownedBy($userId)
            ->findOrFail($id);

        $log->restore();

        return redirect()->route('logs.index')
            ->with('success', '学習ログを復元しました');
    }
}
