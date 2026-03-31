<?php

namespace App\Http\Controllers;

use App\Ai\Agents\AssistantAgent;
use App\Models\Keluarga;
use App\Models\AnggotaKeluarga;
use App\Models\Dasawisma;
use App\Models\Verifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message'         => ['required', 'string', 'max:1000'],
            'conversation_id' => ['nullable', 'string', 'max:36'],
        ]);

        // Coba pakai AI SDK jika provider tersedia
        $hasOpenAI    = !empty(config('ai.providers.openai.key'));
        $hasGemini    = !empty(config('ai.providers.gemini.key'));
        $hasAnthropic = !empty(config('ai.providers.anthropic.key'));

        if ($hasOpenAI || $hasGemini || $hasAnthropic) {
            try {
                $agent = new AssistantAgent(
                    conversationId: $validated['conversation_id'] ?? null
                );

                $response = $agent->prompt($validated['message']);

                return response()->json([
                    'success' => true,
                    'message' => (string) $response,
                    'role'    => 'assistant',
                ]);
            } catch (\Throwable $e) {
                \Log::error('AI Chat error: ' . $e->getMessage());
                // Fallback ke mode demo jika error
            }
        }

        // Mode Demo — jawab berdasarkan kata kunci tanpa AI API
        $reply = $this->demoResponse($validated['message']);

        return response()->json([
            'success' => true,
            'message' => $reply,
            'role'    => 'assistant',
            'demo'    => true,
        ]);
    }

    /**
     * Jawab pertanyaan sederhana berdasarkan data real dari database
     * tanpa membutuhkan API key AI eksternal.
     */
    private function demoResponse(string $message): string
    {
        $msg = mb_strtolower($message);

        // Statistik
        if (str_contains($msg, 'statistik') || str_contains($msg, 'total') || str_contains($msg, 'jumlah') || str_contains($msg, 'berapa')) {
            $totalKK     = Keluarga::count();
            $totalWarga  = AnggotaKeluarga::count();
            $totalDasa   = Dasawisma::count();
            $menunggu    = Verifikasi::where('status_verifikasi', 'menunggu')->count();
            $disetujui   = Verifikasi::where('status_verifikasi', 'disetujui')->count();
            $ditolak     = Verifikasi::where('status_verifikasi', 'ditolak')->count();

            return "📊 **Statistik SIDA-Dompas terkini:**\n\n" .
                "• Total KK: **{$totalKK}** kartu keluarga\n" .
                "• Total Warga: **{$totalWarga}** jiwa\n" .
                "• Total Dasawisma: **{$totalDasa}**\n\n" .
                "📋 Status Verifikasi:\n" .
                "• ⏳ Menunggu: **{$menunggu}**\n" .
                "• ✅ Disetujui: **{$disetujui}**\n" .
                "• ❌ Ditolak: **{$ditolak}**";
        }

        // Cari KK
        if (str_contains($msg, 'cari') || str_contains($msg, 'data kk') || str_contains($msg, 'keluarga')) {
            $total = Keluarga::count();
            $terbaru = Keluarga::with('dasawisma')->latest()->first();
            return "🔍 Sistem memiliki **{$total}** data KK terdaftar.\n\n" .
                ($terbaru ? "KK terbaru: **{$terbaru->nama_kepala_keluarga}** (Dasawisma: {$terbaru->dasawisma?->nama_dasawisma})" : '') .
                "\n\nUntuk pencarian spesifik, gunakan fitur Filter di halaman Data KK & Anggota.";
        }

        // Verifikasi
        if (str_contains($msg, 'verifikasi') || str_contains($msg, 'menunggu') || str_contains($msg, 'disetujui')) {
            $menunggu  = Verifikasi::where('status_verifikasi', 'menunggu')->count();
            $disetujui = Verifikasi::where('status_verifikasi', 'disetujui')->count();
            $ditolak   = Verifikasi::where('status_verifikasi', 'ditolak')->count();
            return "📋 **Status Verifikasi:**\n\n" .
                "• ⏳ Menunggu approval: **{$menunggu}** KK\n" .
                "• ✅ Sudah disetujui: **{$disetujui}** KK\n" .
                "• ❌ Ditolak: **{$ditolak}** KK\n\n" .
                ($menunggu > 0 ? "Ada {$menunggu} KK yang perlu segera ditinjau!" : "Semua KK sudah ditinjau. 🎉");
        }

        // Panduan
        if (str_contains($msg, 'cara') || str_contains($msg, 'bagaimana') || str_contains($msg, 'panduan') || str_contains($msg, 'bantu')) {
            return "💡 **Panduan SIDA-Dompas:**\n\n" .
                "**Role Kader:**\n" .
                "• 📁 Kelola Data Dasawisma di menu 'Data Dasawisma'\n" .
                "• 👨‍👩‍👧 Input KK baru di 'Data KK & Anggota' → Tambah KK\n" .
                "• ✏️ Klik 'Detail' KK untuk edit/tambah anggota keluarga\n" .
                "• 📊 Pantau status verifikasi di 'Status Verifikasi'\n\n" .
                "**Role Admin:**\n" .
                "• ✅ Review & setujui/tolak KK di 'Verifikasi Data'\n" .
                "• 👥 Kelola akun kader di 'Manajemen User'\n" .
                "• 📑 Cetak laporan di 'Laporan'";
        }

        // Dasawisma
        if (str_contains($msg, 'dasawisma') || str_contains($msg, 'rt') || str_contains($msg, 'rw')) {
            $total = Dasawisma::count();
            $list = Dasawisma::with('kader.user')->orderBy('nama_dasawisma')->limit(5)->get();
            $listText = $list->map(fn($d) => "• {$d->nama_dasawisma} (RT {$d->rt}/RW {$d->rw})")->join("\n");
            return "🏘️ **Data Dasawisma** — Total: **{$total}** dasawisma\n\n{$listText}" .
                ($total > 5 ? "\n\n... dan " . ($total - 5) . " dasawisma lainnya." : '');
        }

        // Default (Role-aware greeting)
        $user = auth()->user();
        $roleInfo = match ($user?->role) {
            'kades' => "Sebagai **Kepala Desa**, saya bisa menyajikan ringkasan dan laporan statistik kependudukan untuk Anda.",
            'admin' => "Sebagai **Admin**, saya bisa membantu memantau status verifikasi KK yang perlu Anda setujui.",
            'kader' => "Sebagai **Kader**, saya bisa membantu Anda mengecek status input KK Anda dan panduan pendataan.",
            default => "Saya bisa membantu Anda mencari data spesifik."
        };

        return "Halo {$user?->name}! Saya Asisten SIDA-Dompas 🌿\n\n" .
            $roleInfo . "\n\n" .
            "Coba tanyakan sesuatu! Contoh:\n" .
            "• *\"Berapa total KK saat ini?\"*\n" .
            "• *\"Ada berapa data yang menunggu verifikasi?\"*\n" .
            "• *\"Tampilkan status verifikasi.\"*\n\n" .
            "_(Mode Demo Sederhana — tambahkan GEMINI_API_KEY di .env agar saya bisa menggunakan AI penuh)_";
    }
}
