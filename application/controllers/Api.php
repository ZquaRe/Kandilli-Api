<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    /**
     * Api constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Sayfa yenilendiğinde yeni bir deprem varsa otomatik olarak veritabanına eklenir.
        // Sayfa geç açılırsa bu işlemi silebilirsiniz.
        $this->cronjob();
    }

    /**
     * @return string|true
     */
    public function index()
    {
        $result = [];
        foreach ($this->eqmodel->getAll() as $key) {
            $result[] = [
                'date' => $key->eq_date,
                'time' => $key->eq_time,
                'latitude' => $key->eq_latitude,
                'longitude' => $key->eq_longitude,
                'depth' => $key->eq_depth,
                'md' => $key->eq_md,
                'ml' => $key->eq_ml,
                'mw' => $key->eq_mw,
                'location' => $key->eq_location,
                'revize' => $key->eq_revize,
            ];
        }
        return print_r(json_encode($result));
    }

    /**
     * @return string|true
     */
    public function limit()
    {
        $onePara = abs($this->uri->segment(3));
        $twoPara = abs($this->uri->segment(4));

        if (!empty($onePara) && is_numeric($onePara) || !empty($twoPara) && is_numeric($twoPara)) {
            if ($onePara != 0) {
                $result = [];
                foreach ($this->eqmodel->getLimit($onePara, $twoPara) as $key) {
                    $result[] = [
                        'date' => $key->eq_date,
                        'time' => $key->eq_time,
                        'latitude' => $key->eq_latitude,
                        'longitude' => $key->eq_longitude,
                        'depth' => $key->eq_depth,
                        'md' => $key->eq_md,
                        'ml' => $key->eq_ml,
                        'mw' => $key->eq_mw,
                        'location' => $key->eq_location,
                        'revize' => $key->eq_revize,
                    ];
                }
                return print_r(json_encode($result));
            } else {
                return print_r(json_encode(['status' => false, 'description' => 'Geçersiz sayı']));
            }
        } else {
            return print_r(json_encode(['status' => false, 'description' => 'Geçersiz parametre, api/limit/1']));
        }
    }

    /**
     * @return string|true
     */
    public function date()
    {
        if (empty($this->uri->segment(3))) {
            return print_r(json_encode(['status' => false, 'description' => 'Geçersiz tarih parametresi']));
        }
        // Parametreler
        $onePara = $this->uri->segment(3);
        $twoPara = $this->uri->segment(4);
        $threePara = abs($this->uri->segment(5));
        $limit = 0;
        // Tarih Doğrulama
        if (isDateTime($onePara)) {
            // Tarihi Dönüştür
            $onePara = date("Y-m-d", strtotime($this->uri->segment(3)));

            // Limit Kontrolü
            if (!empty($twoPara) && $twoPara == 'limit' && !empty($threePara) && is_numeric($threePara)) {
                $limit = $threePara;
            } else {
                $limit = 500;
            }

            // Veri Kontrolü
            $result = [];
            if ($this->eqmodel->getDate($onePara, $limit)) {
                foreach ($this->eqmodel->getDate($onePara, $limit) as $key) {
                    $result[] = [
                        'date' => $key->eq_date,
                        'time' => $key->eq_time,
                        'latitude' => $key->eq_latitude,
                        'longitude' => $key->eq_longitude,
                        'depth' => $key->eq_depth,
                        'md' => $key->eq_md,
                        'ml' => $key->eq_ml,
                        'mw' => $key->eq_mw,
                        'location' => $key->eq_location,
                        'revize' => $key->eq_revize,
                    ];
                }
                if (!empty($result)) {
                    return print_r(json_encode($result));
                }
            }
        } else {
            return print_r(json_encode(['status' => false, 'description' => 'Geçersiz tarih formatı']));
        }
    }

    /**
     * @return string|true
     */
    public function location()
    {
        $onePara = mb_strtoupper(TurkishUpper(urldecode($this->uri->segment(3))));
        $twoPara = $this->uri->segment(4);
        $threePara = abs($this->uri->segment(5));
        $limit = 0;

        if (!empty($onePara)) {
            if (strlen($onePara) >= 3) {
                // Limit Kontrolü
                if (!empty($twoPara) && $twoPara == 'limit' && !empty($threePara) && is_numeric($threePara)) {
                    $limit = $threePara;
                } else {
                    $limit = 500;
                }

                $result = [];
                if ($this->eqmodel->getLocation($onePara, $limit)) {
                    foreach ($this->eqmodel->getLocation($onePara, $limit) as $key) {
                        $result[] = [
                            'date' => $key->eq_date,
                            'time' => $key->eq_time,
                            'latitude' => $key->eq_latitude,
                            'longitude' => $key->eq_longitude,
                            'depth' => $key->eq_depth,
                            'md' => $key->eq_md,
                            'ml' => $key->eq_ml,
                            'mw' => $key->eq_mw,
                            'location' => $key->eq_location,
                            'revize' => $key->eq_revize,
                        ];
                    }
                    if (!empty($result)) {
                        return print_r(json_encode($result));
                    }
                }
            } else {
                return print_r(json_encode(['status' => false, 'description' => 'Konum bilgisi 3 karakterden az olamaz']));
            }
        } else {
            return print_r(json_encode(['status' => false, 'description' => 'Geçersiz konum parametresi']));
        }
    }

    /**
     * @return string|true
     */
    public function between()
    {
        if (empty($this->uri->segment(3)) || empty($this->uri->segment(4))) {
            return print_r(json_encode(['status' => false, 'description' => 'Konum bilgisi 2 karakterden az olamaz.']));
        }

        // Parametreler
        $onePara = $this->uri->segment(3);
        $twoPara = $this->uri->segment(4);
        $threePara = $this->uri->segment(5);
        $fourPara = abs($this->uri->segment(6));
        $limit = 0;

        // Tarih Doğrulama
        if (isDateTime($onePara) && isDateTime($twoPara)) {
            // Tarihi Dönüştür
            $onePara = date("Y-m-d", strtotime($this->uri->segment(3)));
            $twoPara = date("Y-m-d", strtotime($this->uri->segment(4)));

            // Limit Kontrolü
            if (!empty($threePara) && $threePara == 'limit' && !empty($fourPara) && is_numeric($fourPara)) {
                $limit = $fourPara;
            } else {
                $limit = 500;
            }

            // Veri Kontrolü
            $result = [];
            if ($this->eqmodel->getBetweenDate($onePara, $twoPara, $limit)) {
                foreach ($this->eqmodel->getBetweenDate($onePara, $twoPara, $limit) as $key) {
                    $result[] = [
                        'date' => $key->eq_date,
                        'time' => $key->eq_time,
                        'latitude' => $key->eq_latitude,
                        'longitude' => $key->eq_longitude,
                        'depth' => $key->eq_depth,
                        'md' => $key->eq_md,
                        'ml' => $key->eq_ml,
                        'mw' => $key->eq_mw,
                        'location' => $key->eq_location,
                        'revize' => $key->eq_revize,
                    ];
                }
                if (!empty($result)) {
                    return print_r(json_encode($result));
                }
            }
        } else {
            return print_r(json_encode(['status' => false, 'description' => 'Geçersiz tarih formatı']));
        }
    }

    /**
     *
     */
    public function cronjob()
    {
        foreach (json_decode($this->eqlib->Earth()) as $eq) {
            $this->eqmodel->add([
                'eq_date' => $eq->Date,
                'eq_time' => $eq->Time,
                'eq_latitude' => $eq->Latitude,
                'eq_longitude' => $eq->Longitude,
                'eq_depth' => $eq->Depth,
                'eq_md' => $eq->Md,
                'eq_ml' => $eq->Ml,
                'eq_mw' => $eq->Mw,
                'eq_location' => $eq->Location,
                'eq_revize' => $eq->Revize
            ]);
        }
    }
}
