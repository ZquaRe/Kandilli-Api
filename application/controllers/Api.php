<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    /***
     * Api constructor.
     */
    public function __construct()
    {
        parent::__construct();

        //When the page is refreshed, if there is a new earthquake, it automatically adds it to the database.
        //If the page opens late, you can delete it.
        self::cronjob();
    }

    /**
     * @return string|true
     */
    public function index()
    {
        foreach ($this->eqmodel->GetAll() as $Key) {
            $Result[] = array(
                'date' => $Key->eq_date,
                'time' => $Key->eq_time,
                'latitude' => $Key->eq_latitude,
                'longitude' => $Key->eq_longitude,
                'depth' => $Key->eq_depth,
                'md' => $Key->eq_md,
                'ml' => $Key->eq_ml,
                'mw' => $Key->eq_mw,
                'location' => $Key->eq_location,
                'revize' => $Key->eq_revize,
            );
        }
        return print_r(json_encode($Result));
    }

    /**
     * @return string|true
     */
    public function limit()
    {
        $this->OnePara = abs($this->uri->segment(3));
        $this->TwoPara = abs($this->uri->segment(4));

        if (!empty($this->OnePara) && is_numeric($this->OnePara) || !empty($this->TwoPara) && is_numeric($this->TwoPara)) {
            if ($this->OnePara != 0) {

                foreach ($this->eqmodel->GetLimit($this->OnePara, $this->TwoPara) as $Key) {
                    $Result[] = array(
                        'date' => $Key->eq_date,
                        'time' => $Key->eq_time,
                        'latitude' => $Key->eq_latitude,
                        'longitude' => $Key->eq_longitude,
                        'depth' => $Key->eq_depth,
                        'md' => $Key->eq_md,
                        'ml' => $Key->eq_ml,
                        'mw' => $Key->eq_mw,
                        'location' => $Key->eq_location,
                        'revize' => $Key->eq_revize,
                    );
                }
                return print_r(json_encode($Result));
            } else {
                print_r(json_encode(array('status' => false, 'description' => 'Invalid number')));
            }

        } else {
            print_r(json_encode(array('status' => false, 'description' => 'Invalid parameter, api/limit/1')));
        }

    }

    /***
     * @return string|true
     */
    public function date()
    {
        if (empty($this->uri->segment(3))) print_r(json_encode(array('status' => false, 'description' => 'Invalid date parameter')));
        //Parameters
        $this->OnePara = $this->uri->segment(3);
        $this->TwoPara = $this->uri->segment(4);
        $this->ThreePara = abs($this->uri->segment(5));
        $this->Limit = 0;
        //DateTime Validated
        if (IsDateTime($this->OnePara)) {

            //Date Convert
            $this->OnePara = date("Y-m-d", strtotime($this->uri->segment(3)));

            //Limit Control
            if (!empty($this->TwoPara) && $this->TwoPara == 'limit' && !empty($this->ThreePara) && is_numeric($this->ThreePara)) {
                $this->Limit = $this->ThreePara;
            } else $this->Limit = 500;


            //Data Control
            if ($this->eqmodel->GetDate($this->OnePara, $this->Limit)) {

                foreach ($this->eqmodel->GetDate($this->OnePara, $this->Limit) as $Key) {
                    $Result[] = array(
                        'date' => $Key->eq_date,
                        'time' => $Key->eq_time,
                        'latitude' => $Key->eq_latitude,
                        'longitude' => $Key->eq_longitude,
                        'depth' => $Key->eq_depth,
                        'md' => $Key->eq_md,
                        'ml' => $Key->eq_ml,
                        'mw' => $Key->eq_mw,
                        'location' => $Key->eq_location,
                        'revize' => $Key->eq_revize,
                    );
                }
                if (!empty($Result)) {
                    return print_r(json_encode($Result));
                }

            }

        } else {
            print_r(json_encode(array('status' => false, 'description' => 'Invalid date format')));
        }

    }

    /***
     * @return string|true
     */
    public function location()
    {
        $this->OnePara = mb_strtoupper(TurkishUpper(urldecode($this->uri->segment(3))));
        $this->TwoPara = $this->uri->segment(4);
        $this->Three = abs($this->uri->segment(5));
        $this->Limit = 0;

        if (!empty($this->OnePara)) {
            if (strlen($this->OnePara) >= 3) {

                //Limit Control
                if (!empty($this->TwoPara) && $this->TwoPara == 'limit' && !empty($this->Three) && is_numeric($this->Three)) {
                    $this->Limit = $this->Three;
                } else $this->Limit = 500;

                if ($this->eqmodel->GetLocation($this->OnePara, $this->Limit)) {

                    foreach ($this->eqmodel->GetLocation($this->OnePara, $this->Limit) as $Key) {
                        $Result[] = array(
                            'date' => $Key->eq_date,
                            'time' => $Key->eq_time,
                            'latitude' => $Key->eq_latitude,
                            'longitude' => $Key->eq_longitude,
                            'depth' => $Key->eq_depth,
                            'md' => $Key->eq_md,
                            'ml' => $Key->eq_ml,
                            'mw' => $Key->eq_mw,
                            'location' => $Key->eq_location,
                            'revize' => $Key->eq_revize,
                        );
                    }
                    if (!empty($Result)) {
                        return print_r(json_encode($Result));
                    }

                }
            } else {
                print_r(json_encode(array('status' => false, 'description' => 'Location information cannot be less than 3 characters')));
            }
        } else {
            print_r(json_encode(array('status' => false, 'description' => 'Invalid location parameter')));
        }
    }

    /***
     * @return string|true
     */
    public function between()
    {

        if (empty($this->uri->segment(3)) || empty($this->uri->segment(4))) print_r(json_encode(array('status' => false, 'description' => 'Location information cannot be less than 2 characters.')));

        //Parameters
        $this->OnePara = $this->uri->segment(3);
        $this->TwoPara = $this->uri->segment(4);
        $this->ThreePara = $this->uri->segment(5);
        $this->FourPara = abs($this->uri->segment(6));
        $this->Limit = 0;
        //DateTime Validated
        if (IsDateTime($this->OnePara) && IsDateTime($this->TwoPara)) {

            //Date Convert
            $this->OnePara = date("Y-m-d", strtotime($this->uri->segment(3)));
            $this->TwoPara = date("Y-m-d", strtotime($this->uri->segment(4)));

            //Limit Control
            if (!empty($this->ThreePara) && $this->ThreePara == 'limit' && !empty($this->FourPara) && is_numeric($this->FourPara)) {
                $this->Limit = $this->FourPara;
            } else $this->Limit = 500;


            //Data Control
            if ($this->eqmodel->GetBetweenDate($this->OnePara, $this->TwoPara, $this->Limit)) {

                foreach ($this->eqmodel->GetBetweenDate($this->OnePara, $this->TwoPara, $this->Limit) as $Key) {
                    $Result[] = array(
                        'date' => $Key->eq_date,
                        'time' => $Key->eq_time,
                        'latitude' => $Key->eq_latitude,
                        'longitude' => $Key->eq_longitude,
                        'depth' => $Key->eq_depth,
                        'md' => $Key->eq_md,
                        'ml' => $Key->eq_ml,
                        'mw' => $Key->eq_mw,
                        'location' => $Key->eq_location,
                        'revize' => $Key->eq_revize,
                    );
                }
                if (!empty($Result)) {
                    return print_r(json_encode($Result));
                }

            }

        } else {
            print_r(json_encode(array('status' => false, 'description' => 'Invalid date format')));
        }

    }

    /**
     *
     */
    public function cronjob()
    {
        foreach (json_decode($this->eqlib->Earth()) as $Eq) {
            $this->eqmodel->Add(array(
                'eq_date' => $Eq->Date,
                'eq_time' => $Eq->Time,
                'eq_latitude' => $Eq->Latitude,
                'eq_longitude' => $Eq->Longitude,
                'eq_depth' => $Eq->Depth,
                'eq_md' => $Eq->Md,
                'eq_ml' => $Eq->Ml,
                'eq_mw' => $Eq->Mw,
                'eq_location' => $Eq->Location,
                'eq_revize' => $Eq->Revize
            ));
        }
    }

}
