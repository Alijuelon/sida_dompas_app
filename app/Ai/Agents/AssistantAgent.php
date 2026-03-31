<?php

namespace App\Ai\Agents;

use App\Ai\Tools\GetDataKKTool;
use App\Ai\Tools\GetStatistikTool;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class AssistantAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(
        public ?string $conversationId = null
    ) {}

    /**
     * Instruksi sistem untuk AI assistant SIDA-Dompas (Role-Aware).
     */
    public function instructions(): Stringable|string
    {
        $user     = auth()->user();
        $roleName = match ($user?->role) {
            'admin' => 'Administrator SIDA-Dompas',
            'kader' => 'Kader Dasawisma',
            'kades' => 'Kepala Desa',
            default => 'Pengguna',
        };

        $roleContext = match ($user?->role) {
            'kades' => "Fokus utamamu adalah memberikan ringkasan, laporan analitik, dan persentase statistik warga untuk membantu pengambilan keputusan strategis Kepala Desa.",
            'kader' => "Fokus utamamu adalah membimbing kader dalam proses operasional pendataan KK baru, manajemen dasawisma, dan pemantauan status persetujuan KK.",
            'admin' => "Fokus utamamu adalah membantu admin memonitor aliran verifikasi data, memantau data yang menunggu persetujuan (approval pending), dan manajemen pengguna/kader.",
            default => "Fokus utamamu melayani panduan umum."
        };

        return <<<PROMPT
            Kamu adalah Asisten Virtual SIDA-Dompas (Sistem Informasi Dasawisma Desa Dompas).
            Kamu saat ini sedang membantu {$roleName} bernama {$user?->name}.
            
            {$roleContext}
            
            Spesialisasimu:
            - Data Kartu Keluarga (KK) dan anggota keluarga
            - Statistik kependudukan dan progres verifikasi
            - Panduan penggunaan fitur sistem berdasarkan role
            
            Selalu jawab dengan format Markdown yang rapi, mudah dibaca, profesional, dan dalam Bahasa Indonesia.
            Gunakan tools yang tersedia (seperti GetStatistikTool atau GetDataKKTool) untuk menjawab pertanyaan spesifik angka/data.
            PROMPT;
    }

    /**
     * Riwayat percakapan dari database.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        if (! $this->conversationId) {
            return [];
        }

        // Ambil riwayat dari tabel agent_conversation_messages
        try {
            $rows = \DB::table('agent_conversation_messages')
                ->where('conversation_id', $this->conversationId)
                ->whereIn('role', ['user', 'assistant'])
                ->orderBy('created_at')
                ->limit(30)
                ->get();

            return $rows->map(fn ($row) => new Message($row->role, $row->content))->all();
        } catch (\Exception) {
            return [];
        }
    }

    /**
     * Tools yang tersedia untuk agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new GetStatistikTool(),
            new GetDataKKTool(),
        ];
    }
}
