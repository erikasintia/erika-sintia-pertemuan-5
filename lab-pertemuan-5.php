<?php
// LAB 5 — Inheritance & overriding
// Spesifikasi:
//  - Pegawai(string $nama, float $gajiPokok): hitungGaji(): float, FINAL info(): string
//  - Guru(nama, gajiPokok, int $jamMengajar)      -> gajiPokok + jamMengajar x 50.000
//  - Staf(nama, gajiPokok, float $tunjangan)       -> gajiPokok + tunjangan
//  - Honorer(nama, float $gajiPerJam, int $jamKerja) -> gajiPerJam x jamKerja
//  - Sekolah: tambahPegawai(Pegawai $p), totalGaji(): float  (komposisi)

class Pegawai
{
    protected string $nama;
    protected float $gajiPokok;

    public function __construct(string $nama, float $gajiPokok)
    {
        $this->nama = $nama;
        $this->gajiPokok = $gajiPokok;
    }

    public function hitungGaji(): float
    {
        return $this->gajiPokok;
    }

    final public function info(): string
    {
        $gajiFormatted = number_format($this->hitungGaji(), 0, ',', '.');
        return "{$this->nama} (" . static::class . ") - Total Gaji: Rp {$gajiFormatted}";
    }
}

class Guru extends Pegawai
{
    private int $jamMengajar;

    public function __construct(string $nama, float $gajiPokok, int $jamMengajar)
    {
        parent::__construct($nama, $gajiPokok);
        $this->jamMengajar = $jamMengajar;
    }

    public function hitungGaji(): float
    {
        return $this->gajiPokok + ($this->jamMengajar * 50000);
    }
}

class Staf extends Pegawai
{
    private float $tunjangan;

    public function __construct(string $nama, float $gajiPokok, float $tunjangan)
    {
        parent::__construct($nama, $gajiPokok);
        $this->tunjangan = $tunjangan;
    }

    public function hitungGaji(): float
    {
        return $this->gajiPokok + $this->tunjangan;
    }
}

class Honorer extends Pegawai
{
    private float $gajiPerJam;
    private int $jamKerja;

    public function __construct(string $nama, float $gajiPerJam, int $jamKerja)
    {
        // Honorer tidak memakai gaji pokok tetap, sehingga diisi 0 di parent
        parent::__construct($nama, 0);
        $this->gajiPerJam = $gajiPerJam;
        $this->jamKerja = $jamKerja;
    }

    public function hitungGaji(): float
    {
        return $this->gajiPerJam * $this->jamKerja;
    }
}

class Sekolah
{
    /** @var Pegawai[] */
    private array $daftarPegawai = [];

    public function tambahPegawai(Pegawai $p): void
    {
        $this->daftarPegawai[] = $p;
    }

    public function totalGaji(): float
    {
        $total = 0;
        foreach ($this->daftarPegawai as $pegawai) {
            $total += $pegawai->hitungGaji();
        }
        return $total;
    }

    public function cetakSemuaInfo(): void
    {
        foreach ($this->daftarPegawai as $pegawai) {
            echo $pegawai->info() . PHP_EOL;
        }
    }
}

// Pengujian:
$sekolah = new Sekolah();

$guru = new Guru('Budi', 3000000, 20);       // 3.000.000 + (20 * 50.000) = 4.000.000
$staf = new Staf('Siti', 2500000, 750000);   // 2.500.000 + 750.000 = 3.250.000
$honorer = new Honorer('Andi', 40000, 50);   // 40.000 * 50 = 2.000.000

$sekolah->tambahPegawai($guru);
$sekolah->tambahPegawai($staf);
$sekolah->tambahPegawai($honorer);

$sekolah->cetakSemuaInfo();
echo "Total Pengeluaran Gaji Sekolah: Rp " . number_format($sekolah->totalGaji(), 0, ',', '.') . PHP_EOL;