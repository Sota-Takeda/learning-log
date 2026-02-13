<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LearningLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningLogFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** Feature: 未ログインは /logs に入れない */
    public function test_guest_cannot_access_logs_index(): void
    {
        $this->get('/logs')
            ->assertRedirect(route('login'));
    }

    /** Feature: 他人のログは edit/update/delete できない（403） */
    public function test_user_cannot_edit_update_delete_others_log(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $log = LearningLog::create([
            'user_id' => $owner->id,
            'studied_on' => now()->toDateString(),
            'minutes' => 60,
            'title' => 'Owner log',
            'memo' => 'memo',
        ]);

        // edit は 403
        $this->actingAs($other)
            ->get(route('logs.edit', $log))
            ->assertForbidden();

        // update は 403（更新されない）
        $this->actingAs($other)
            ->patch(route('logs.update', $log), [
                'studied_on' => now()->toDateString(),
                'minutes' => 120,
                'title' => 'Hacked',
                'memo' => 'changed',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('learning_logs', [
            'id' => $log->id,
            'title' => 'Owner log',
            'minutes' => 60,
        ]);

        // delete は 403（削除されない）
        $this->actingAs($other)
            ->delete(route('logs.destroy', $log))
            ->assertForbidden();

        $this->assertDatabaseHas('learning_logs', [
            'id' => $log->id,
        ]);
    }

    /** Feature: minutes/title/studied_on のバリデーション */
    public function test_store_validation_errors_for_minutes_title_studied_on(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('logs.create'))
            ->post(route('logs.store'), [
                'studied_on' => '',     // required
                'minutes'    => 0,      // min:1 想定
                'title'      => '',     // required
                'memo'       => 'memo', // optional想定
            ])
            ->assertRedirect(route('logs.create'))
            ->assertSessionHasErrors(['studied_on', 'minutes', 'title']);

        // 保存されていないこと
        $this->assertDatabaseCount('learning_logs', 0);
    }
}
