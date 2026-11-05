<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_citizen_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Rani Warga',
            'email' => 'rani@example.test',
            'no_whatsapp' => '081234567890',
            'alamat' => 'Jalan Melati 12',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('warga.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'rani@example.test', 'role' => 'warga']);
        $this->assertAuthenticated();
    }

    public function test_citizens_can_only_see_their_own_reports(): void
    {
        $owner = User::factory()->create();
        $otherCitizen = User::factory()->create();
        $report = $owner->laporans()->create([
            'judul' => 'Lampu jalan mati',
            'detail' => 'Lampu jalan di dekat taman sudah mati selama dua malam.',
            'tanggal' => now()->toDateString(),
        ]);

        $this->actingAs($otherCitizen)
            ->get(route('laporan.show', $report))
            ->assertNotFound();
    }

    public function test_dashboard_pages_render_for_their_own_roles(): void
    {
        $citizen = User::factory()->create();
        $this->actingAs($citizen)
            ->get(route('warga.dashboard'))
            ->assertOk()
            ->assertSee($citizen->name);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard admin');
    }

    public function test_citizens_cannot_open_admin_pages(): void
    {
        $citizen = User::factory()->create();

        $this->actingAs($citizen)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_must_move_report_status_in_order(): void
    {
        $citizen = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $report = $citizen->laporans()->create([
            'judul' => 'Jalan berlubang di gang',
            'detail' => 'Ada lubang besar di jalan gang yang membahayakan pengendara.',
            'tanggal' => now()->toDateString(),
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $report), ['status' => 'selesai'])
            ->assertSessionHasErrors('status');
        $this->assertSame('pending', $report->fresh()->status);

        $this->patch(route('admin.laporan.status', $report), ['status' => 'proses'])
            ->assertRedirect(route('admin.laporan.show', $report));
        $this->assertSame('proses', $report->fresh()->status);

        $this->patch(route('admin.laporan.status', $report), ['status' => 'selesai'])
            ->assertRedirect(route('admin.laporan.show', $report));
        $this->assertSame('selesai', $report->fresh()->status);
    }
}
