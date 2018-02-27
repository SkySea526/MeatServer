<?php

namespace MeatServer\game;

use pocketmine\Server;

class Game {

    public $id;
    public $information = [];

    public $stage;

    const INFO_PLAYERS = "players";
    const INFO_PLAYERS_LIST = "players_list";
    const INFO_TIME = "time";
    const INFO_PHASE = "phase";

    const DATA_SINGLES = 2;
    const DATA_DOUBLES = 4;

    public function getInformation(){
        return $this->information;
    }

    public function setInformation(array $info){
        $this->information = $info;
    }

    public function onJoin(PlayerData $playerData){
    }

    public function onQuit(PlayerData $playerData){
    }

    public function randamMap(){
        $stageKey = array_rand($this->stages);
        $stage = $this->stages[$stageKey];
        $this->stage = $stage;
    }

    public function hidePlayers(){
        $info = $this->getInformation();
        foreach ($info[Game::INFO_PLAYERS_LIST] as $data) {
            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                $data->player->hidePlayer($player);
            }
            foreach ($info[Game::INFO_PLAYERS_LIST] as $data2) {
                $data->player->showPlayer($data2->player);
            }
            $data->showMode = "game";
            $data->showPlayers = $info[Game::INFO_PLAYERS_LIST];
        }
    }

    public function showPlayers(){
        $info = $this->getInformation();
        foreach ($info[Game::INFO_PLAYERS_LIST] as $data) {
            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                $data->player->showPlayer($player);
            }
            $data->showMode = "lobby";
            $data->showPlayers = Server::getInstance()->getOnlinePlayers();
        }
    }
}