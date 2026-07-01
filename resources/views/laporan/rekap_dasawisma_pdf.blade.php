<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Data Warga Dasawisma</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px; /* Ukuran font diperkecil agar semua 31 kolom muat di kertas A4 */
        }
        .header {
            margin-bottom: 15px;
            font-size: 11px;
            font-weight: bold;
        }
        .header table {
            border-collapse: collapse;
            border: none;
        }
        .header td {
            padding: 2px 5px;
            border: none;
            text-align: left;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid black;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .text-left { text-align: left !important; padding-left: 5px !important; }
        .bg-gray { background-color: #f2f2f2; }
        
        /* Pengaturan Lebar Kolom agar rapi saat dicetak PDF */
        .col-no { width: 2%; }
        .col-name { width: 12%; }
        .col-kk { width: 2.5%; }
        .col-narrow { width: 2.5%; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td width="150">DASA WISMA</td>
                <td width="10">:</td>
                <td>{{ $dasawisma->nama_dasawisma ?? 'CEMPAKA' }}</td>
            </tr>
            <tr>
                <td>RT</td>
                <td>:</td>
                <td>{{ $dasawisma->rt ?? '07' }}</td>
            </tr>
            <tr>
                <td>RW</td>
                <td>:</td>
                <td>{{ $dasawisma->rw ?? '04' }}</td>
            </tr>
            <tr>
                <td>DESA/KELURAHAN</td>
                <td>:</td>
                <td>{{ $desa ?? 'DOMPAS' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr class="bg-gray">
                <th rowspan="3" class="col-no">NO</th>
                <th rowspan="3" class="col-name">NAMA KEPALA RUMAH TANGGA</th>
                <th rowspan="3" class="col-kk">JML<br>KK</th>
                <th colspan="12">JUMLAH ANGGOTA KELUARGA</th>
                <th colspan="6">KRITERIA RUMAH</th>
                <th colspan="3">SUMBER AIR KELUARGA</th>
                <th colspan="2">MAKANAN POKOK</th>
                <th colspan="4">WARGA MENGIKUTI KEGIATAN</th>
                <th rowspan="3" class="col-narrow">KET</th>
            </tr>
            <tr class="bg-gray">
                <th colspan="3">TOTAL</th>
                <th colspan="2">BALITA</th>
                <th rowspan="2" class="col-narrow">PUS</th>
                <th rowspan="2" class="col-narrow">WUS</th>
                <th rowspan="2" class="col-narrow">IBU<br>HAMIL</th>
                <th rowspan="2" class="col-narrow">IBU<br>MENYUSUI</th>
                <th rowspan="2" class="col-narrow">LANSIA</th>
                <th rowspan="2" class="col-narrow">3<br>BUTA</th>
                <th rowspan="2" class="col-narrow">BERKEBUTUHAN<br>KHUSUS</th>
                
                <th rowspan="2" class="col-narrow">SEHAT<br>LAYAK<br>HUNI</th>
                <th rowspan="2" class="col-narrow">TIDAK<br>SEHAT<br>LAYAK<br>HUNI</th>
                <th rowspan="2" class="col-narrow">MEMILIKI<br>TMP.<br>PEMB.<br>SAMPAH</th>
                <th rowspan="2" class="col-narrow">MEMILIKI<br>SPAL</th>
                <th rowspan="2" class="col-narrow">MEMILIKI<br>JAMBAN<br>KELUARGA</th>
                <th rowspan="2" class="col-narrow">MENEMPEL<br>STIKER<br>P4K</th>
                
                <th rowspan="2" class="col-narrow">PDAM</th>
                <th rowspan="2" class="col-narrow">SUMUR</th>
                <th rowspan="2" class="col-narrow">DLL</th>
                
                <th rowspan="2" class="col-narrow">BERAS</th>
                <th rowspan="2" class="col-narrow">NON<br>BERAS</th>
                
                <th rowspan="2" class="col-narrow">UP2K</th>
                <th rowspan="2" class="col-narrow">PEMANFAATAN<br>TANAH<br>PEKARANGAN</th>
                <th rowspan="2" class="col-narrow">INDUSTRI<br>RUMAH<br>TANGGA</th>
                <th rowspan="2" class="col-narrow">KESEHATAN</th>
            </tr>
            <tr class="bg-gray">
                <th class="col-narrow">L</th>
                <th class="col-narrow">P</th>
                <th class="col-narrow">JML</th>
                <th class="col-narrow">L</th>
                <th class="col-narrow">P</th>
            </tr>
            <tr class="bg-gray">
                @for ($i = 1; $i <= 31; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @if(isset($data) && count($data) > 0)
                @foreach($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $row->nama_kepala_keluarga }}</td>
                    <td>{{ $row->jml_kk }}</td>
                    
                    <td>{{ $row->total_l ?: '-' }}</td>
                    <td>{{ $row->total_p ?: '-' }}</td>
                    <td>{{ ($row->total_l + $row->total_p) ?: '-' }}</td>
                    
                    <td>{{ $row->balita_l ?: '-' }}</td>
                    <td>{{ $row->balita_p ?: '-' }}</td>
                    
                    <td>{{ $row->pus ?: '-' }}</td>
                    <td>{{ $row->wus ?: '-' }}</td>
                    <td>{{ $row->ibu_hamil ?: '-' }}</td>
                    <td>{{ $row->ibu_menyusui ?: '-' }}</td>
                    <td>{{ $row->lansia ?: '-' }}</td>
                    <td>{{ $row->buta ?: '-' }}</td>
                    <td>{{ $row->berkebutuhan_khusus ?: '-' }}</td>
                    
                    <td>{{ $row->sehat_layak_huni ? 'v' : '-' }}</td>
                    <td>{{ $row->tidak_sehat_layak_huni ? 'v' : '-' }}</td>
                    <td>{{ $row->punya_tempat_sampah ? 'v' : '-' }}</td>
                    <td>{{ $row->punya_spal ? 'v' : '-' }}</td>
                    <td>{{ $row->punya_jamban ? 'v' : '-' }}</td>
                    <td>{{ $row->tempel_stiker_p4k ? 'v' : '-' }}</td>
                    
                    <td>{{ $row->sumber_air == 'PDAM' ? 'v' : '-' }}</td>
                    <td>{{ $row->sumber_air == 'SUMUR' ? 'v' : '-' }}</td>
                    <td>{{ $row->sumber_air == 'DLL' ? 'v' : '-' }}</td>
                    
                    <td>{{ $row->makanan_pokok == 'BERAS' ? 'v' : '-' }}</td>
                    <td>{{ $row->makanan_pokok == 'NON BERAS' ? 'v' : '-' }}</td>
                    
                    <td>{{ $row->ikut_up2k ? 'v' : '-' }}</td>
                    <td>{{ $row->pemanfaatan_pekarangan ? 'v' : '-' }}</td>
                    <td>{{ $row->industri_rumah_tangga ? 'v' : '-' }}</td>
                    <td>{{ $row->kesehatan ? 'v' : '-' }}</td>
                    
                    <td>{{ $row->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <!-- Tampilan data dummy jika variabel $data kosong (sebagai preview) -->
                <tr>
                    <td>1</td>
                    <td class="text-left">FOMY HARIYANTO</td>
                    <td>2</td>
                    <td>2</td><td>4</td><td>6</td>
                    <td>1</td><td>1</td>
                    <td>1</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
                    
                    <td>v</td><td>-</td><td>-</td><td>-</td><td>v</td><td>-</td>
                    <td>-</td><td>v</td><td>-</td>
                    <td>v</td><td>-</td>
                    <td>-</td><td>v</td><td>-</td><td>v</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="text-left">SAMIN</td>
                    <td>2</td>
                    <td>3</td><td>4</td><td>7</td>
                    <td>-</td><td>1</td>
                    <td>1</td><td>-</td><td>-</td><td>-</td><td>2</td><td>-</td><td>-</td>
                    
                    <td>v</td><td>-</td><td>-</td><td>-</td><td>v</td><td>-</td>
                    <td>-</td><td>v</td><td>-</td>
                    <td>v</td><td>-</td>
                    <td>-</td><td>v</td><td>-</td><td>v</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="text-left">TARMIZI</td>
                    <td>2</td>
                    <td>2</td><td>2</td><td>4</td>
                    <td>1</td><td>-</td>
                    <td>1</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
                    
                    <td>v</td><td>-</td><td>v</td><td>v</td><td>v</td><td>v</td>
                    <td>-</td><td>v</td><td>-</td>
                    <td>v</td><td>-</td>
                    <td>-</td><td>v</td><td>-</td><td>v</td>
                    <td>-</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="bg-gray" style="font-weight: bold;">
                <td colspan="2">JUMLAH</td>
                <td>{{ $totals['jml_kk'] ?? '13' }}</td>
                
                <td>{{ $totals['total_l'] ?? '25' }}</td>
                <td>{{ $totals['total_p'] ?? '25' }}</td>
                <td>{{ $totals['total_jml'] ?? '50' }}</td>
                
                <td>{{ $totals['balita_l'] ?? '8' }}</td>
                <td>{{ $totals['balita_p'] ?? '3' }}</td>
                
                <td>{{ $totals['pus'] ?? '8' }}</td>
                <td>{{ $totals['wus'] ?? '0' }}</td>
                <td>{{ $totals['ibu_hamil'] ?? '4' }}</td>
                <td>{{ $totals['ibu_menyusui'] ?? '0' }}</td>
                <td>{{ $totals['lansia'] ?? '11' }}</td>
                <td>{{ $totals['buta'] ?? '0' }}</td>
                <td>{{ $totals['berkebutuhan_khusus'] ?? '0' }}</td>
                
                <td>{{ $totals['sehat_layak_huni'] ?? '9' }}</td>
                <td>{{ $totals['tidak_sehat_layak_huni'] ?? '0' }}</td>
                <td>{{ $totals['punya_tempat_sampah'] ?? '9' }}</td>
                <td>{{ $totals['punya_spal'] ?? '9' }}</td>
                <td>{{ $totals['punya_jamban'] ?? '9' }}</td>
                <td>{{ $totals['tempel_stiker_p4k'] ?? '9' }}</td>
                
                <td>{{ $totals['sumber_air_pdam'] ?? '0' }}</td>
                <td>{{ $totals['sumber_air_sumur'] ?? '9' }}</td>
                <td>{{ $totals['sumber_air_dll'] ?? '0' }}</td>
                
                <td>{{ $totals['makanan_pokok_beras'] ?? '9' }}</td>
                <td>{{ $totals['makanan_pokok_non_beras'] ?? '0' }}</td>
                
                <td>{{ $totals['ikut_up2k'] ?? '0' }}</td>
                <td>{{ $totals['pemanfaatan_pekarangan'] ?? '9' }}</td>
                <td>{{ $totals['industri_rumah_tangga'] ?? '0' }}</td>
                <td>{{ $totals['kesehatan'] ?? '9' }}</td>
                
                <td>-</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
