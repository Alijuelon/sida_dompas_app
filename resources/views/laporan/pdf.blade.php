<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekapitulasi Catatan Data dan Kegiatan Warga</title>
    <style>
        @page {
            size: legal landscape;
            margin: 10mm;
        }
        body { font-family: sans-serif; font-size: 8px; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 14px; font-weight: bold; text-transform: uppercase; }
        
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        .data-table th, .data-table td { 
            border: 1px solid #000; 
            padding: 3px 2px; 
            text-align: center; 
            vertical-align: middle;
            font-size: 7px;
        }
        .data-table th { 
            background-color: #f4f4f4; 
            font-size: 6px;
            font-weight: bold;
        }
        .text-left { text-align: left !important; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAPITULASI CATATAN DATA DAN KEGIATAN WARGA</h1>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2" class="text-left" style="width: 80px;">NAMA KEPALA RUMAH TANGGA</th>
                <th rowspan="2">JML<br>KK</th>
                <th colspan="2">TOTAL</th>
                <th colspan="2">BALITA</th>
                <th rowspan="2">PUS</th>
                <th rowspan="2">WUS</th>
                <th rowspan="2">IBU<br>HAMIL</th>
                <th rowspan="2">IBU<br>MENYUSUI</th>
                <th rowspan="2">LANSIA</th>
                <th rowspan="2">BUTA</th>
                <th rowspan="2">BERKEBUTUHAN<br>KHUSUS</th>
                <th colspan="6">KRITERIA RUMAH</th>
                <th colspan="3">SUMBER AIR KELUARGA</th>
                <th colspan="2">MAKANAN POKOK</th>
                <th colspan="4">KEGIATAN WARGA</th>
                <th rowspan="2">KET</th>
            </tr>
            <tr>
                <th>L</th>
                <th>P</th>
                <th>L</th>
                <th>P</th>
                <!-- Kriteria Rumah -->
                <th>SEHAT<br>LAYAK<br>HUNI</th>
                <th>TDK<br>SEHAT</th>
                <th>MEMILIKI<br>TMP<br>SAMPAH</th>
                <th>MEMILIKI<br>SPAL</th>
                <th>MEMILIKI<br>JAMBAN</th>
                <th>TEMPEL<br>STIKER<br>PMI</th>
                <!-- Air -->
                <th>PDAM</th>
                <th>SUMUR</th>
                <th>DLL</th>
                <!-- Makanan -->
                <th>BERAS</th>
                <th>NON<br>BERAS</th>
                <!-- Kegiatan -->
                <th>UP2K</th>
                <th>MANFAAT<br>TANAH</th>
                <th>INDUSTRI<br>RUMAH TANGGA</th>
                <th>KERJA<br>BAKTI</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_kk = 0; $total_l = 0; $total_p = 0;
                $balita_l = 0; $balita_p = 0;
                $pus = 0; $wus = 0; $bumil = 0; $busui = 0; $lansia = 0; $buta = 0; $khusus = 0;
                $r_sehat = 0; $r_tdksehat = 0; $r_sampah = 0; $r_spal = 0; $r_jamban = 0; $r_pmi = 0;
                $a_pdam = 0; $a_sumur = 0; $a_dr = 0;
                $m_beras = 0; $m_nonberas = 0;
                $k_up2k = 0; $k_tanah = 0; $k_industri = 0; $k_bakti = 0;
            @endphp
            
            @forelse($keluargas as $index => $kk)
                @php
                    $is_sehat = $kk->sehat_layak_huni;
                    $is_tdksehat = !$is_sehat;
                    $has_sampah = $kk->memiliki_tempat_sampah;
                    $has_spal = $kk->memiliki_spal;
                    $has_jamban = $kk->memiliki_jamban ?? false;
                    $has_pmi = $kk->menempel_stiker_p4k;
                    
                    $is_pdam = ($kk->sumber_air == 'PDAM');
                    $is_sumur = ($kk->sumber_air == 'Sumur');
                    $is_dr = ($kk->sumber_air == 'Lainnya' || $kk->sumber_air == 'Sungai');
                    
                    $is_beras = ($kk->makanan_pokok == 'Beras');
                    $is_nonberas = ($kk->makanan_pokok != 'Beras' && $kk->makanan_pokok != null);

                    $total_kk += $kk->jumlah_kk;
                    $total_l += $kk->jumlah_laki_laki;
                    $total_p += $kk->jumlah_perempuan;
                    $balita_l += $kk->jumlah_balita_laki;
                    $balita_p += $kk->jumlah_balita_perempuan;
                    $pus += $kk->jumlah_pus;
                    $wus += $kk->jumlah_wus;
                    $bumil += $kk->jumlah_ibu_hamil;
                    $busui += $kk->jumlah_ibu_menyusui;
                    $lansia += $kk->jumlah_lansia;
                    $buta += $kk->jumlah_buta;
                    $khusus += $kk->jumlah_berkebutuhan_khusus;
                    
                    $r_sehat += $is_sehat ? 1 : 0;
                    $r_tdksehat += $is_tdksehat ? 1 : 0;
                    $r_sampah += $has_sampah ? 1 : 0;
                    $r_spal += $has_spal ? 1 : 0;
                    $r_jamban += $has_jamban ? 1 : 0;
                    $r_pmi += $has_pmi ? 1 : 0;
                    
                    $a_pdam += $is_pdam ? 1 : 0;
                    $a_sumur += $is_sumur ? 1 : 0;
                    $a_dr += $is_dr ? 1 : 0;
                    
                    $m_beras += $is_beras ? 1 : 0;
                    $m_nonberas += $is_nonberas ? 1 : 0;
                    
                    $k_up2k += $kk->ikut_up2k ? 1 : 0;
                    $k_tanah += $kk->ikut_pekarangan ? 1 : 0;
                    $k_industri += $kk->ikut_industri ? 1 : 0;
                    $k_bakti += $kk->ikut_kerja_bakti ? 1 : 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left nowrap">{{ strtoupper($kk->nama_kepala_keluarga ?? '') }}</td>
                    <td>{{ $kk->jumlah_kk ?: '-' }}</td>
                    <td>{{ $kk->jumlah_laki_laki ?: '-' }}</td>
                    <td>{{ $kk->jumlah_perempuan ?: '-' }}</td>
                    <td>{{ $kk->jumlah_balita_laki ?: '-' }}</td>
                    <td>{{ $kk->jumlah_balita_perempuan ?: '-' }}</td>
                    <td>{{ $kk->jumlah_pus ?: '-' }}</td>
                    <td>{{ $kk->jumlah_wus ?: '-' }}</td>
                    <td>{{ $kk->jumlah_ibu_hamil ?: '-' }}</td>
                    <td>{{ $kk->jumlah_ibu_menyusui ?: '-' }}</td>
                    <td>{{ $kk->jumlah_lansia ?: '-' }}</td>
                    <td>{{ $kk->jumlah_buta ?: '-' }}</td>
                    <td>{{ $kk->jumlah_berkebutuhan_khusus ?: '-' }}</td>
                    
                    <td>{{ $is_sehat ? '✓' : '-' }}</td>
                    <td>{{ $is_tdksehat ? '✓' : '-' }}</td>
                    <td>{{ $has_sampah ? '✓' : '-' }}</td>
                    <td>{{ $has_spal ? '✓' : '-' }}</td>
                    <td>{{ $has_jamban ? '✓' : '-' }}</td>
                    <td>{{ $has_pmi ? '✓' : '-' }}</td>
                    
                    <td>{{ $is_pdam ? '✓' : '-' }}</td>
                    <td>{{ $is_sumur ? '✓' : '-' }}</td>
                    <td>{{ $is_dr ? '✓' : '-' }}</td>
                    
                    <td>{{ $is_beras ? '✓' : '-' }}</td>
                    <td>{{ $is_nonberas ? '✓' : '-' }}</td>
                    
                    <td>{{ $kk->ikut_up2k ? '✓' : '-' }}</td>
                    <td>{{ $kk->ikut_pekarangan ? '✓' : '-' }}</td>
                    <td>{{ $kk->ikut_industri ? '✓' : '-' }}</td>
                    <td>{{ $kk->ikut_kerja_bakti ? '✓' : '-' }}</td>
                    <td>-</td>
                </tr>
            @empty
                <tr>
                    <td colspan="30">Tidak ada data rekapitulasi keluarga.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="2" class="text-left">JUMLAH</td>
                <td>{{ $total_kk ?: '-' }}</td>
                <td>{{ $total_l ?: '-' }}</td>
                <td>{{ $total_p ?: '-' }}</td>
                <td>{{ $balita_l ?: '-' }}</td>
                <td>{{ $balita_p ?: '-' }}</td>
                <td>{{ $pus ?: '-' }}</td>
                <td>{{ $wus ?: '-' }}</td>
                <td>{{ $bumil ?: '-' }}</td>
                <td>{{ $busui ?: '-' }}</td>
                <td>{{ $lansia ?: '-' }}</td>
                <td>{{ $buta ?: '-' }}</td>
                <td>{{ $khusus ?: '-' }}</td>
                
                <td>{{ $r_sehat ?: '-' }}</td>
                <td>{{ $r_tdksehat ?: '-' }}</td>
                <td>{{ $r_sampah ?: '-' }}</td>
                <td>{{ $r_spal ?: '-' }}</td>
                <td>{{ $r_jamban ?: '-' }}</td>
                <td>{{ $r_pmi ?: '-' }}</td>
                
                <td>{{ $a_pdam ?: '-' }}</td>
                <td>{{ $a_sumur ?: '-' }}</td>
                <td>{{ $a_dr ?: '-' }}</td>
                
                <td>{{ $m_beras ?: '-' }}</td>
                <td>{{ $m_nonberas ?: '-' }}</td>
                
                <td>{{ $k_up2k ?: '-' }}</td>
                <td>{{ $k_tanah ?: '-' }}</td>
                <td>{{ $k_industri ?: '-' }}</td>
                <td>{{ $k_bakti ?: '-' }}</td>
                <td>-</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
