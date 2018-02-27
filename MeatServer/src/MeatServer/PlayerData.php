<?php

namespace MeatServer;

class PlayerData {

    public static $data = [];
    public static $onlineData = [];

    public $player;
    public $status;

    public $enableHunger = false;

    const RANK_GUEST = 0;
    const RANK_VIP = 1;
    const RANK_VIP_PLUS = 2;
    const RANK_MVP = 3;
    const RANK_YT = 4;
    const RANK_DEVELOPER = 5;
    const RANK_OWNER = 6;

    public static function createPlayerData(Player $player){
        $id = $player->getName();
        self::$data[$id] = new PlayerData($player);
        /*if(empty(self::$data[$id])) self::$data[$id] = new PlayerData($player);
        else{
            self::$data[$id]->player = $player;
            self::$data[$id]->BossBar = new BossBar($player);
        }*/
        self::$data[$id]->checkData();
    }

    public static function getPlayerDataByPlayer(Player $player){
        $id = $player->getName();
        if(empty(self::$data[$id])) return null;
        return self::$data[$id];
    }

    public static function getPlayerDataByName(String $name) {
        if(empty(self::$data[$name])) return null;
        return self::$data[$name];
    }

    public static function setOnlinePlayerData($playerData, $online){
        $id = $playerData->player->getName();
        if($online) self::$onlineData[$id] = $playerData;
        else unset(self::$onlineData[$id]);
    }

    public static function getOnlinePlayerData(){
        return self::$onlineData;
    }

    public function __construct(Player $player){
        $this->player = $player;
        $this->status = new PlayerStatus($this);
    }

    public function checkData(){
        if(Main::$DB->exists("Account",$this->player->getName())) $this->syncPlayerData();
        else{
            $this->saveToData();
        }
    }

    public function getXuid(){
        return $this->xuid;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setRank(int $rank){
        $this->rank = $rank;
    }

    public function getRank(){
        return $this->rank;
    }

    public function syncPlayerData(){
        $id = $this->player->getName();
        $result = Main::$DB->get_row("Account",$id);
        $this->status->setData("coin",$result[self::DATA_COIN]);
        $this->status->setData("cont",$result[self::DATA_CONT]);
        $this->setRank($result[self::DATA_RANK]);
    }

    public function saveToData(){
        Main::$DB->update("Account",$this->player->getName(),$this->convertSQL3());
    }

    public function convertSQL3(){
        $result = [];
        $result["id"] = $this->getXuid();
        $result["coin"] = $this->status->getData("coin");
        $result["cont"] = $this->status->getData("cont");
        $result["rank"] = $this->getRank();
        return $result;
    }

    public function joined(){
        if(!$this->logined) {
            $this->logined = true;
        }
    }
}