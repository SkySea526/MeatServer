<?php

namespace MeatServer;

class PlayerStatus{

    public $playerData;
    public $status;

    public function __construct(PlayerData $playerData){
        $this->playerData = $playerData;
        $this->status = [
            "coin" => 0,
            "cont" => 0,
            "singles_play" => 0,
            "singles_win" => 0,
            "singles_lose" => 0,
            "doubles_play" => 0,
            "doubles_win" => 0,
            "doubles_lose" => 0,
        ];
    }

    public function removeData($key, $amount){
        $this->status[$key] -= $amount;
    }

    public function setData($key, $amount){
        $this->status[$key] = $amount;
    }

    public function addData($key, $amount){
        $this->status[$key] += $amount;
    }

    public function getData($key){
        return $this->status[$key];
    }
}