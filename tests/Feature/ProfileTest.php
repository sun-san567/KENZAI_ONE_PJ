<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectFileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_ファイルをアップロードできる()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('test.pdf', 1024);

        $response = $this->postJson(
            route('projects.files.upload', $project->id),
            ['file' => $file]
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas('project_files', [
            'project_id' => $project->id,
            'file_name' => 'test.pdf',
            'uploaded_by' => $user->id,
        ]);
    }

    public function test_ファイル一覧を取得できる()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user);

        // テスト用のファイルを作成
        ProjectFile::factory()->count(3)->create([
            'project_id' => $project->id,
            'uploaded_by' => $user->id,
        ]);

        $response = $this->getJson(
            route('projects.files.index', $project->id)
        );

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }
}
