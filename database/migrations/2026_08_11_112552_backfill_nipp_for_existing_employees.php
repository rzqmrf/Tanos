<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Isi NIPP untuk semua karyawan yang masih kosong.
     * Format: NIP-<id dengan 4 digit> (misal NIP-0001, NIP-0101, NIP-2668).
     * Id karyawan unik, jadi NIPP hasil backfill otomatis unik & tidak bentrok.
     */
    public function up(): void
    {
        $employees = DB::table('employees')
            ->whereNull('nipp')
            ->orWhere('nipp', '')
            ->orderBy('id')
            ->get();

        foreach ($employees as $emp) {
            $nipp = 'NIP-' . str_pad($emp->id, 4, '0', STR_PAD_LEFT);

            // Cegah bentrok unik kalau format NIP-xxxx sudah terpakai
            if (DB::table('employees')->where('nipp', $nipp)->where('id', '!=', $emp->id)->exists()) {
                $nipp = 'NIP-' . $emp->id;
            }

            DB::table('employees')->where('id', $emp->id)->update(['nipp' => $nipp]);
        }
    }

    /**
     * Reverse — kosongkan kembali NIPP hasil backfill.
     * Hanya yang berformat NIP-<id> hasil backfill yang dikosongkan.
     */
    public function down(): void
    {
        $employees = DB::table('employees')
            ->where('nipp', 'like', 'NIP-%')
            ->get();

        foreach ($employees as $emp) {
            $autoNipp = 'NIP-' . str_pad($emp->id, 4, '0', STR_PAD_LEFT);
            if ($emp->nipp === $autoNipp || $emp->nipp === 'NIP-' . $emp->id) {
                DB::table('employees')->where('id', $emp->id)->update(['nipp' => null]);
            }
        }
    }
};
