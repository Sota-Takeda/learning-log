<?php

namespace App\Http\Controllers;

use App\Models\LearningLog;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLearningLogRequest;
use App\Http\Requests\UpdateLearningLogRequest;

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

        return view('logs.index', compact('logs', 'q', 'todayTotalMinutes'));
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
