<?php

namespace MeatServer\game\singles;

use MeatServer\game\Game;

class Singles extends Game {

    public function __construct($id){
        $this->id = $id;
        $info[Game::INFO_PLAYERS_LIST] = [];
        $info[Game::INFO_PLAYERS] = 0;
        $info[Game::INFO_TIME] = 5;
        $info[Game::INFO_PHASE] = Game::PLAYER_WITING;
        $this->setInformation($info);
        $this->colortag[0] = "§c";
        $this->colortag[1] = "§b";
        $this->ramdomMap();
    }

    public function onJoin(PlayerData $playerData) {
        parent::onJoin($playerData);
        $info = $this->getInformation();
        $info[Game::INFO_PLAYERS_LIST][] = $playerData;
        $info[Game::INFO_PLAYERS]++;
        $this->setInformation($info);
        if($info[Game::INFO_PLAYERS] >= Game::DATA_SINGLES) {
            $this->GameStart();
        }
    }

    public function onQuit(PlayerData $playerData){
        $info = $this->getInformation();
        foreach ($info[Game::INFO_PLAYERS_LIST] as $key => $data) {
            if($data == $playerData) unset($info[Game::INFO_PLAYERS_LIST][$key]);
        }
        parent::onQuit($playerData);
    }

    public function startGame() {
        $this->hidePlayers();
        $info = $this->getInformation();
        $count = 0;
        foreach ($info[Game::INFO_PLAYERS_LIST] as $key => $playerData) {
            $player = $playerData->player;
            $playerData->setInventory(new GameSinglesInventory($playerData));
            $player->teleport($this->stage->getSpawnPoint()[$count]);
            $player->setImmobile(true);
            $player->setNameTag($this->colortag[$count].$player->getName());
            $count++;
        }
    }

}