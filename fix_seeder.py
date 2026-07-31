import re

with open('database/seeders/DatabaseSeeder.php', 'r') as f:
    content = f.read()

# Fix noKk
new_no_kk_logic = """
                    $kkDay = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
                    $kkMonth = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
                    $kkYear = str_pad(rand(10, 24), 2, '0', STR_PAD_LEFT);
                    $kkSuffix = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    $noKk = "140801{$kkDay}{$kkMonth}{$kkYear}{$kkSuffix}";
"""
content = re.sub(r"\$noKk = \$faker->numerify\('140801##########'\);.*?\n", new_no_kk_logic.strip() + "\n", content)

# Fix sumber_air and stiker
sumber_air_old = """'sumber_air'                 => $faker->randomElement(['PDAM', 'Sumur', 'Lainnya']),"""
sumber_air_new = """'sumber_air'                 => [$faker->randomElement(['PDAM', 'Sumur', 'Sungai', 'Mata Air', 'Air Hujan'])],
                        'sumber_air_lainnya'         => null,"""
content = content.replace(sumber_air_old, sumber_air_new)

# Fix stiker
stiker_old = """'menempel_stiker_p4k'        => $faker->boolean(60),"""
stiker_new = """'menempel_stiker_p4k'        => $faker->boolean(60),
                        'jenis_stiker'               => 'Stiker P4K',"""
content = content.replace(stiker_old, stiker_new)


# Fix NIK logic
nik_old_logic = """                        $ikutBelajar = $faker->boolean(20);
                        $ikutKoperasi = ($isKK || $isIstri) ? $faker->boolean(40) : false;

                        AnggotaKeluarga::create(["""

nik_new_logic = """                        $ikutBelajar = $faker->boolean(20);
                        $ikutKoperasi = ($isKK || $isIstri) ? $faker->boolean(40) : false;

                        $tglLahirObj = Carbon::parse($tglLahir);
                        $day = $tglLahirObj->format('d');
                        $month = $tglLahirObj->format('m');
                        $year = $tglLahirObj->format('y');
                        if ($gender === 'P') {
                            $day = str_pad((int)$day + 40, 2, '0', STR_PAD_LEFT);
                        }
                        $suffix = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                        $nik = "140801{$day}{$month}{$year}{$suffix}";

                        AnggotaKeluarga::create(["""
content = content.replace(nik_old_logic, nik_new_logic)

content = content.replace("""'nik'                      => $faker->numerify('140801##########'),""", """'nik'                      => $nik,""")

with open('database/seeders/DatabaseSeeder.php', 'w') as f:
    f.write(content)
