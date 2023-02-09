<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eq_model extends CI_Model
{

    private $tableName = 'earthquakes';

    /***
     * @return mixed
     */
    public function GetAll()
    {
        return $this->db->
        order_by('eq_date', 'DESC')->order_by('eq_time', 'DESC')->get($this->tableName)->
        result();
    }

    /**
     * @param $Date
     * @param null $Limit
     * @return |null
     */
    public function GetDate($Date, $Limit = null)
    {
        if (!empty($Limit)) {
            $Results = $this->db->
            order_by('eq_date', 'DESC')->
            order_by('eq_time', 'DESC')->
            where('eq_date', $Date)->
            limit($Limit)->
            get($this->tableName)->
            result();
            if ($Results) {
                return $Results;
            } else {
                return null;
            }
        } else {
            $Results = $this->db->
            order_by('eq_date', 'DESC')->
            order_by('eq_time', 'DESC')->
            where('eq_date', $Date)->
            get($this->tableName)->
            result();
            if ($Results) {
                return $Results;
            } else {
                return null;
            }
        }


    }

    /**
     * @param $OneParam
     * @param int $TwoParam
     * @return |null
     */
    public function GetLimit($OneParam, $TwoParam = 0)
    {
        $Result = $this->db->
        order_by('eq_date', 'DESC')->
        order_by('eq_time', 'DESC')->
        limit($OneParam, $TwoParam)->
        get($this->tableName)->
        result();
        if ($Result) {
            return $Result;
        } else {
            return null;
        }
    }

    /**
     * @param $OneParam
     * @param int $TwoParam
     * @return |null
     */
    public function GetLocation($OneParam, $TwoParam = 0)
    {
        $Result = $this->db->
        order_by('eq_date', 'DESC')->
        order_by('eq_time', 'DESC')->
        like('eq_location', $OneParam)->
        limit($TwoParam)->
        get($this->tableName)->
        result();
        if ($Result) {
            return $Result;
        } else {
            return null;
        }
    }

    /***
     * @param $OneParam
     * @param $TwoParam
     * @param int $Limit
     * @return |null
     */
    public function GetBetweenDate($OneParam, $TwoParam, $Limit = 0)
    {
        // if($this->uri->segment(4) >= $this->uri->segment(3)) echo '1. parametre, 2. parametreden küçük';

        if ($OneParam >= $TwoParam) {

            $Result = $this->db->
            order_by('eq_date', 'DESC')->
            order_by('eq_time', 'DESC')->
            where('eq_date >=', $TwoParam)->
            where('eq_date <=', $OneParam)->
            limit($Limit)->
            get($this->tableName)->
            result();
            if ($Result) {
                return $Result;
            } else {
                return null;
            }
        }
        if ($OneParam <= $TwoParam) {
            $Result = $this->db->
            order_by('eq_date', 'ASC')->
            order_by('eq_time', 'ASC')->
            where('eq_date >=', $OneParam)->
            where('eq_date <=', $TwoParam)->
            limit($Limit)->
            get($this->tableName)->
            result();
            if ($Result) {
                return $Result;
            } else {
                return null;
            }
        }

    }

    /***
     * @param array $Params
     * @return mixed
     */
    public function Add($Params = array())
    {

        $Control = $this->db->
        select('eq_time, eq_latitude,eq_longitude,eq_depth')->
        where('eq_time', $Params['eq_time'])->
        where('eq_latitude', $Params['eq_latitude'])->
        where('eq_longitude', $Params['eq_longitude'])->
        where('eq_depth', $Params['eq_depth'])->
        get($this->tableName)->
        result();

        if (!$Control) {
            return $this->db->insert($this->tableName, $Params);
        }


    }
}