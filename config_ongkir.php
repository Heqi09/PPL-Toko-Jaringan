<?php
// Ganti dengan API Key RajaOngkir Sandbox Anda (opsional, jika tidak diisi akan menggunakan engine simulasi)
define('RAJAONGKIR_API_KEY', 'YOUR_RAJAONGKIR_API_KEY_HERE');
define('KOTA_ASAL_ID', '22'); // ID 22 = Kabupaten Bangkalan (Asal Pengiriman Toko)

// Fungsi Hitung Ongkir (Dukungan API RajaOngkir Sandbox & Engine Simulasi Lokal)
function hitung_ongkos_kirim($id_kota_tujuan, $berat_gram, $kurir) {
    // 1. Jika API Key diisi, panggil API RajaOngkir Sandbox
    if (RAJAONGKIR_API_KEY !== 'YOUR_RAJAONGKIR_API_KEY_HERE' && !empty(RAJAONGKIR_API_KEY)) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'origin'      => KOTA_ASAL_ID,
                'destination' => $id_kota_tujuan,
                'weight'      => $berat_gram,
                'courier'     => strtolower($kurir)
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded",
                "key: " . RAJAONGKIR_API_KEY
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        
        if (isset($result['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'])) {
            return $result['rajaongkir']['results'][0]['cost'][0]['cost'][0]['value'];
        }
    }

    // 2. Fallback Engine Simulasi Ongkir Lokal (Bangkalan ke Kota-kota di Indonesia)
    $tarif_per_kg = [
        'jne'  => 12000,
        'pos'  => 10000,
        'tiki' => 11000
    ];
    
    $berat_kg = ceil($berat_gram / 1000);
    $tarif_base = $tarif_per_kg[strtolower($kurir)] ?? 12000;
    
    return $tarif_base * $berat_kg;
}
?>