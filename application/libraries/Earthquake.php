<?php

class Earthquake
{
    // Deprem verilerinin alınacağı URL.
    private $url = 'http://www.koeri.boun.edu.tr/scripts/lst0.asp';

    public function Earth()
    {
        // URL'den ham verileri çeker.
        $rawData = $this->fetchDataFromUrl();
        // Ham verileri işler.
        $processedData = $this->processData($rawData);

        // İşlenmiş verileri JSON formatında döndürür.
        return json_encode($processedData);
    }

    private function fetchDataFromUrl()
    {
        // cURL oturumu başlatılıyor.
        $ch = curl_init();

        // Çeşitli cURL seçenekleri ayarlanıyor.
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        // HTTP başlıkları ayarlanıyor.
        $headers = [
            'Content-type: text/html; charset=UTF-8',
            'Connection: keep-alive',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language: tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // cURL oturumu başlatılıyor ve sonuç alınıyor.
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            // Eğer bir hata varsa, hatayı ekrana yazdırıyor.
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        // Verinin karakter setini ISO-8859-9'dan UTF-8'e dönüştürüyor.
        return iconv("ISO-8859-9", "UTF-8", $result);
    }

    private function processData($rawData)
    {
        // Ham verilerdeki HTML etiketlerini kaldırıyor ve satırlara ayırıyor.
        $preTagExploded = explode('<pre>', strip_tags($rawData));
        $rows = array_values(array_filter(array_map('trim', explode("\n", $preTagExploded[0]))));
        // İlk 31 satır dikkate alınmıyor.
        $earthquakeDataRows = array_slice($rows, 31);

        $result = [];
        // Her bir satır için işlem yapılıyor.
        foreach ($earthquakeDataRows as $row) {
            // Satır boşluklara göre bölünüyor.
            $rowData = preg_split('/[\s]+/', $row);

            // Konum bilgisi alınıyor.
            $location = implode(' ', array_slice($rowData, 8, -1));
            // Konum bilgisinde 'REVIZE' ifadesi varsa, bu ifade ve sonrası çıkarılıyor.
            if (strstr($location, 'REVIZE')) {
                $location = substr($location, 0, -21);
            }

            // Satırdaki tüm veri tekrar birleştiriliyor.
            $rowWithoutGaps = implode(' ', $rowData);
            // 'REVIZE' ifadesi varsa bu ifade ve sonrası alınıyor.
            $revision = strstr($rowWithoutGaps, "REVIZE") ? trim(substr($rowWithoutGaps, strpos($rowWithoutGaps, 'REVIZE'))) : null;

            // Eğer satırda 'yayımlanan' ifadesi geçiyorsa, döngü kırılıyor.
            if (strpos($rowWithoutGaps, "yayımlanan")) {
                break;
            }

            // Her bir satır için bir dizi oluşturuluyor ve sonuç dizisine ekleniyor.
            $result[] = [
                'Date' => $rowData[0],
                'Time' => $rowData[1],
                'Latitude' => $rowData[2],
                'Longitude' => $rowData[3],
                'Depth' => $rowData[4],
                'Md' => $rowData[5],
                'Ml' => $rowData[6],
                'Mw' => $rowData[7],
                'Location' => $location,
                'Revize' => $revision
            ];
        }

        // İşlenmiş deprem verilerini döndürüyor.
        return $result;
    }
}
?>
