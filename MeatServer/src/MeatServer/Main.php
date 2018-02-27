<?php
 
namespace MeatServer;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\math\Vector3;

use pocketmine\item\Item;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerChangeSkinEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\player\cheat\PlayerIllegalMoveEvent;

use PocketMineHelper\event\entity\EntityInteractEvent;
use PocketMineHelper\event\ui\UICloseEvent;
use PocketMineHelper\event\ui\UIDataReceiveEvent;
use PocketMineHelper\event\ui\ServerSettingsRequestEvent;

use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\ExplodePacket;
use pocketmine\network\mcpe\protocol\ShowProfilePacket;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

class Main extends PluginBase implements Listener {

    public static $path;
    public static $DB;

    public function onEnable(){
        date_default_timezone_set('Asia/Tokyo');
        $this->getServer()->getPluginManager()->registerEvents($this,$this);

        self::$path = $this->getDataFolder();
    }

    public function onJoinEvent(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $playerData = PlayerData::getPlayerDataByPlayer($player);
        PlayerData::setOnlinePlayerData($playerData, true);
        $player->getInventory()->clearall();
        Server::getInstance()->getScheduler()->scheduleDelayedTask(new CallbackTask([$playerData,"joined"],[]),20);
    }

    public function onQuitEvent(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        if($player->loggedIn) {
            $playerData = PlayerData::getPlayerDataByPlayer($player);
            PlayerData::setOnlinePlayerData($playerData, false);
        }
    }

    public function onLoginEvent(PlayerLoginEvent $event){
        $player = $event->getPlayer();
        $playerData = PlayerData::getPlayerDataByPlayer($player);
        $playerData->xuid = $player->getXuid();
        $playerData->saveToData();
    }

    public function onPreLoginEvent(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        PlayerData::createPlayerData($player);
    }

    public function onExhaust(PlayerExhaustEvent $event){
        $player = $event->getPlayer();
        $playerData = PlayerData::getPlayerDataByPlayer($player);
        if(!$playerData->enableHunger) $event->setCancelled();
    }

    public function onInteract(PlayerInteractEvent $event){
    }

    public function onDeathEvent(PlayerDeathEvent $event) {
    }

    public function onEntityDamageEvent(EntityDamageEvent $event) {
    }

    public function onDropEvent(PlayerDropItemEvent $event) {
    }

    public function onChangeSkin(PlayerChangeSkinEvent $event){
        $event->setCancelled();
    }

    public function onIllegalMove(PlayerIllegalMoveEvent $event){
        $event->setCancelled();
    }

    public function onPacket(DataPacketReceiveEvent $event) {
    }
}