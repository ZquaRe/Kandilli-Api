<?php
/**
 * class earthquake
 *
 * @author Furkan Sezgin (ZquaRe)
 * @mail furkan-sezgin@hotmail.com
 */
class earthquake
{

    /**
     * @return mixed
     */
    public function Earth()
    {
        $this->Result = $this->cURL();
        //Pre tagından sonraki verileri alıyoruz
        $this->ExplodeTag = explode('<pre>', strip_tags($this->Result));

        //Satır sonlarını anlamlı hale getiriyoruz
        $this->ExplodeTag = explode("\n", $this->ExplodeTag[0]);


        //Boş olan bütün arrayleri siliyoruz
        $this->Data = array_filter(array_map('trim', $this->ExplodeTag));

        //Verilerin Array numaraları biçimsiz olarak geliyor 7, 8, 22,46 gibi. Verileri foreach'a alıp yeniden sıralandırıyoruz.
        foreach ($this->Data as $Key) {
            $this->NewData[] = $Key;
        }

        //31. Arrayden başlatıyoruz, --- ve ondan önceki yazıları siliyor bize sadece deprem bilgileri kalıyor
        $this->SpliceData = array_splice($this->NewData, 31);

        //İçerisindeki boşlukları parçalıyoruz bu sayede daha anlamlı veriler elde ediyoruz.
        foreach ($this->SpliceData as $Key) {
            $this->ResultData[] = preg_split('/[\s]+/', $Key);
        }


        foreach ($this->ResultData as $Key) {

            //Caner Kıyıcı teşekkür ediyorum, bölge alanlarını birleştiriyoruz.
            $this->NewName = array_slice($Key, 8, -1);
            //Alanları birleştiriyoruz
            $this->NewLocation = implode(' ', $this->NewName);
            //Eğer bölge adının içerisinde REVIZE kelimesi geçiyorsa bunu sildiriyoruz, 21 karakterlik alan alıyor sadece.
            if (strstr($this->NewLocation, 'REVIZE')) $this->NewLocation = substr($this->NewLocation, 0, -21);

            //Alanları birleştiriyoruz
            $this->ResultNoGap = implode(' ', $Key);
            //Revize var mı diye bakıyoruz, eğer revize varsa revizelenme durumunu ve tarihini alıyoruz, yoksa null döndürüyoruz.
            if (strstr($this->ResultNoGap, "REVIZE")) {
                $Revize[] = trim(substr($this->ResultNoGap, strpos($this->ResultNoGap, 'REVIZE')));
            } else {
                $Revize = null;
            }
            //En sonda site bilgilendirme yazısı mevcut, o yere geldiğinde döngü dursun.
            if (strpos($this->ResultNoGap, "yayımlanan")) break;

            $this->NewResult[] = array(

                'Date' => $Key[0],
                'Time' => $Key[1],
                'Latitude' => $Key[2],
                'Longitude' => $Key[3],
                'Depth' => $Key[4],
                'Md' => $Key[5],
                'Ml' => $Key[6],
                'Mw' => $Key[7],
                'Location' => $this->NewLocation,
                'Revize' => $Revize[0]
            );
        }
        return json_encode($this->NewResult);

    }

    /**
     * @return bool|false|string
     */
    private function cURL()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://www.koeri.boun.edu.tr/scripts/lst0.asp');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Accept-Language: tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        //Türkçe Dil Biçimi
        $result = iconv("ISO-8859-9", "UTF-8", $result);
        return $result;
    }

}