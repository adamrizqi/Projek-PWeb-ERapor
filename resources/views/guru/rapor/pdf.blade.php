<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor - {{ $siswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1, .header h2, .header h3 {
            margin: 0;
            text-transform: uppercase;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .content-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 13px;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .signatures {
            width: 100%;
            margin-top: 50px;
        }
        .signatures td {
            text-align: center;
            vertical-align: top;
            width: 33%;
        }
        .signature-space {
            height: 70px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pemerintah Kabupaten Blitar</h2>
        <h3>Dinas Pendidikan</h3>
        <h1>SD Negeri Slumbung 1</h1>
        <p>Jl. Pendidikan No. 1, Slumbung, Kec. Gandusari, Kab. Blitar</p>
    </div>

    <div class="title">Laporan Hasil Belajar Siswa</div>

    <table class="info-table">
        <tr>
            <td width="20%">Nama Peserta Didik</td>
            <td width="40%">: <strong>{{ strtoupper($siswa->nama_lengkap) }}</strong></td>
            <td width="15%">Kelas</td>
            <td width="25%">: {{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>: {{ $siswa->nis }} / {{ $siswa->nisn }}</td>
            <td>Semester</td>
            <td>: {{ $semester }} (Satu)</td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>: SDN Slumbung 1</td>
            <td>Tahun Ajaran</td>
            <td>: {{ $tahun_ajaran }}</td>
        </tr>
    </table>

    <div class="section-title">A. KOMPETENSI SIKAP</div>
    <table class="content-table">
        <tr>
            <th width="25%">Aspek</th>
            <th>Deskripsi</th>
        </tr>
        <tr>
            <td class="text-center text-bold">Sikap Spiritual</td>
            <td>{{ $sikap->sikap_spiritual }}</td>
        </tr>
        <tr>
            <td class="text-center text-bold">Sikap Sosial</td>
            <td>{{ $sikap->sikap_sosial }}</td>
        </tr>
    </table>

    <div class="section-title">B. KOMPETENSI PENGETAHUAN DAN KETERAMPILAN</div>
    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mata Pelajaran</th>
                <th width="10%">KKM</th>
                <th width="10%">Nilai</th>
                <th width="10%">Predikat</th>
                <th width="35%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $index => $n)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $n->mataPelajaran->nama_mapel }}</td>
                <td class="text-center">{{ $n->mataPelajaran->kkm }}</td>
                <td class="text-center text-bold">{{ $n->nilai_akhir }}</td>
                <td class="text-center">{{ $n->predikat }}</td>
                <td style="font-size: 11px;">{{ $n->deskripsi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">C. KETIDAKHADIRAN</div>
    <table class="content-table" style="width: 50%;">
        <tr>
            <td>Sakit</td>
            <td class="text-center">{{ $kehadiran->sakit }} hari</td>
        </tr>
        <tr>
            <td>Izin</td>
            <td class="text-center">{{ $kehadiran->izin }} hari</td>
        </tr>
        <tr>
            <td>Tanpa Keterangan</td>
            <td class="text-center">{{ $kehadiran->alpa }} hari</td>
        </tr>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>Orang Tua/Wali,
                <div class="signature-space"></div>
                _______________________
            </td>
            <td></td>
            <td>
                Blitar, {{ $tanggal_cetak }}<br>Wali Kelas,
                <div class="signature-space"></div>
                <strong><u>{{ $wali_kelas->name }}</u></strong><br>
                NIP. {{ $wali_kelas->nip }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 20px;">
                Mengetahui,<br>Kepala Sekolah
                <div class="signature-space"></div>
                <strong><u>{{ $kepala_sekolah['nama'] }}</u></strong><br>
                NIP. {{ $kepala_sekolah['nip'] }}
            </td>
        </tr>
    </table>
</body>
</html>
