<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eq_model extends CI_Model
{
    // Veritabanında kullanılacak tablo adı
    private $tableName = 'earthquakes';

    // Tüm deprem verilerini getiren fonksiyon
    public function getAll()
    {
        return $this->db
            ->order_by('eq_date', 'DESC')  // Tarihe göre sıralama
            ->order_by('eq_time', 'DESC')  // Zamana göre sıralama
            ->get($this->tableName)  // Veriyi çekme
            ->result();  // Sonucu döndürme
    }

    // Belirli bir tarihte olan depremleri getiren fonksiyon
    public function getDate($date, $limit = null)
    {
        $query = $this->db
            ->order_by('eq_date', 'DESC')  // Tarihe göre sıralama
            ->order_by('eq_time', 'DESC')  // Zamana göre sıralama
            ->where('eq_date', $date);  // Belirtilen tarihle eşleşen veriler
            
        if (!empty($limit)) {  // Eğer limit belirtildiyse
            $query = $query->limit($limit);  // Sorguya limiti ekle
        }
            
        $results = $query->get($this->tableName)->result();  // Sonucu çek
        
        return $results ?: null;  // Eğer sonuç boşsa null döndür, değilse sonucu döndür
    }

    // Belirli sayıda deprem verisi getiren fonksiyon
    public function getLimit($oneParam, $twoParam = 0)
    {
        $result = $this->db
            ->order_by('eq_date', 'DESC')  // Tarihe göre sıralama
            ->order_by('eq_time', 'DESC')  // Zamana göre sıralama
            ->limit($oneParam, $twoParam)  // Belirtilen limit
            ->get($this->tableName)  // Veriyi çekme
            ->result();  // Sonucu döndürme
        
        return $result ?: null;  // Eğer sonuç boşsa null döndür, değilse sonucu döndür
    }

    // Belirli bir lokasyonda olan depremleri getiren fonksiyon
    public function getLocation($oneParam, $twoParam = 0)
    {
        $result = $this->db
            ->order_by('eq_date', 'DESC')  // Tarihe göre sıralama
            ->order_by('eq_time', 'DESC')  // Zamana göre sıralama
            ->like('eq_location', $oneParam)  // Lokasyonu belirtilen parametre ile eşleşen veriler
            ->limit($twoParam)  // Belirtilen limit
            ->get($this->tableName)  // Veriyi çekme
            ->result();  // Sonucu döndürme
        
        return $result ?: null;  // Eğer sonuç boşsa null döndür, değilse sonucu döndür
    }

    // Belirli bir tarih aralığında olan depremleri getiren fonksiyon
    public function getBetweenDate($oneParam, $twoParam, $limit = 0)
    {
        // Eğer ilk parametre ikinci parametreden büyükse, sonuçları ters sırala
        if ($oneParam >= $twoParam) {
            $query = $this->db
                ->order_by('eq_date', 'DESC')  // Tarihe göre sıralama
                ->order_by('eq_time', 'DESC')  // Zamana göre sıralama
                ->where('eq_date >=', $twoParam)  // Belirtilen tarih aralığı ile eşleşen veriler
                ->where('eq_date <=', $oneParam);  // Belirtilen tarih aralığı ile eşleşen veriler
        } else {
            $query = $this->db
                ->order_by('eq_date', 'ASC')  // Tarihe göre sıralama
                ->order_by('eq_time', 'ASC')  // Zamana göre sıralama
                ->where('eq_date >=', $oneParam)  // Belirtilen tarih aralığı ile eşleşen veriler
                ->where('eq_date <=', $twoParam);  // Belirtilen tarih aralığı ile eşleşen veriler
        }
        
        if ($limit > 0) {  // Eğer limit belirtildiyse
            $query = $query->limit($limit);  // Sorguya limiti ekle
        }
        
        $result = $query->get($this->tableName)->result();  // Sonucu çek
        
        return $result ?: null;  // Eğer sonuç boşsa null döndür, değilse sonucu döndür
    }

    // Yeni deprem verisi ekleyen fonksiyon
    public function add($params = array())
    {
        $control = $this->db
            ->select('eq_time, eq_latitude,eq_longitude,eq_depth')  // Kontrol edilecek alanlar
            ->where('eq_time', $params['eq_time'])  // Zaman değeri eşleşmesi
            ->where('eq_latitude', $params['eq_latitude'])  // Enlem değeri eşleşmesi
            ->where('eq_longitude', $params['eq_longitude'])  // Boylam değeri eşleşmesi
            ->where('eq_depth', $params['eq_depth'])  // Derinlik değeri eşleşmesi
            ->get($this->tableName)  // Veriyi çekme
            ->result();  // Sonucu döndürme

        if (!$control) {  // Eğer kontrol edilen veri yoksa
            return $this->db->insert($this->tableName, $params);  // Yeni veriyi ekle
        }
    }
}
