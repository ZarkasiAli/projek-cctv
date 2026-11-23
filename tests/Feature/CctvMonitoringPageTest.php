<?php

namespace Tests\Feature;

use App\Models\Ruangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CctvMonitoringPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_cctv_page_keeps_unavailable_rooms_and_shows_unavailable_message(): void
    {
        Ruangan::create([
            'nama' => 'Lab RPL 1',
            'kode_ruangan' => 'RPL-1',
            'lokasi' => 'Gedung A',
            'link_stream_cctv' => 'https://example.com/live/stream.m3u8',
        ]);

        Ruangan::create([
            'nama' => 'Lab Lama',
            'kode_ruangan' => 'OLD-1',
            'lokasi' => 'Gedung A',
            'link_stream_cctv' => 'http://localhost/cctv/lab-lama',
        ]);

        $response = $this->get('/cctv?ruangan_id=2');

        $response->assertStatus(200);
        $response->assertSee('https://cdn.jsdelivr.net/npm/hls.js', false);
        $response->assertSee('Tidak tersedia', false);
        $response->assertDontSee('images/cctv_feed.jpg', false);
        $response->assertSee('OLD-1', false);
        $response->assertSee('Lab Lama', false);
    }

    public function test_cctv_room_selector_has_pagination_controls(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            Ruangan::create([
                'nama' => "Ruangan {$i}",
                'kode_ruangan' => "R{$i}",
                'lokasi' => 'Gedung A',
                'link_stream_cctv' => 'https://example.com/live/stream-' . $i . '.m3u8',
            ]);
        }

        $response = $this->get('/cctv?ruangan_id=1');

        $response->assertStatus(200);
        $response->assertSee('room-page-prev', false);
        $response->assertSee('room-page-next', false);
    }

    public function test_cctv_grid_shows_all_playable_streams_and_marks_unavailable_ones(): void
    {
        Ruangan::create([
            'nama' => 'Lab Livestream',
            'kode_ruangan' => 'LIV-1',
            'lokasi' => 'Gedung A',
            'link_stream_cctv' => 'https://example.com/live/lab.m3u8',
        ]);

        Ruangan::create([
            'nama' => 'Lab Offline',
            'kode_ruangan' => 'OFF-1',
            'lokasi' => 'Gedung B',
            'link_stream_cctv' => null,
        ]);

        $response = $this->get('/cctv?mode=grid');

        $response->assertStatus(200);
        $response->assertSee('cctv-grid-video', false);
        $response->assertSee('Tidak tersedia', false);
    }
}
