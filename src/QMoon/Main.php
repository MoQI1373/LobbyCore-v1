<?php

namespace QMoon;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\utils\TextFormat as DC;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use QMoon\command\Hub;
use QMoon\item\Item;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getLogger()->info("LobbyCore is enabled by QMoon");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->register("/hub", new Hub($this));
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $ev->getPlayer();
		$level = $player->getLevel();
		$level->broadcastLevelSoundEvent(new Vector3($player->getX(), $player->getY(), $player->getZ()), LevelSoundEventPacket::SOUND_LEVELUP);
		$event->setJoinMessage("");
		$player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
		$player->setGamemode(0);
		$player->setHealth(20);
		$player->setFood(20);
		$player->setMaxHealth(20);
		$player->setScale(1);
		$player->setImmobile(false);
		$player->removeAllEffects();
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();
		$player->addTitle("§a§lWelcome to §r§6DragonUniverse");
		$this->getArticulos()->give($player);
		$this->getServer()->broadcastMessage(DC::BOLD."§7[§a+§7]§r§b ".$player->getName());
	}

	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		$level = $player->getLevel();
		$level->broadcastLevelSoundEvent(new Vector3($player->getX(), $player->getY(), $player->getZ()), LevelSoundEventPacket::SOUND_FIZZ);
		$event->setQuitMessage("");
		$this->getServer()->broadcastMessage(DC::BOLD."§7[§c-§7]§r§b ".$pl->getName());
	}

	public function onRespawn(PlayerRespawnEvent $event){
		$player = $event->getPlayer();
		$player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
		$player->setGamemode(0);
		$player->setHealth(20);
		$player->setFood(20);
		$player->setMaxHealth(20);
		$player->setScale(1);
		$player->setImmobile(false);
		$player->removeAllEffects();
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();
		$this->getArticulos()->give($pl);
	}

	public function onInteract(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		if($item->getName() == DC::BOLD."§bInformation§r"){
			$this->getArticulos()->Info($player);
		}else if($item->getName() == DC::BOLD."§aGames§r"){
			$this->getArticulos()->MiniGM($player);
		}else if($item->getName() == DC::BOLD."§5Cosmetics§r"){
			if($player->hasPermission("core.cosmeticos")){
				$this->getArticulos()->Cms($player);
			} else {
				$player->sendMessage("§eYou dont have permissions to use this.");
			}
		}else if($item->getName() === DC::BOLD."§cReport§r"){
			$this->getServer()->dispatchCommand($player, "report");
		}else if($item->getName() === DC::BOLD."§6Hub§r"){
			$this->getServer()->dispatchCommand($player, "hub");
		}
	}

	public function onExhaust(PlayerExhaustEvent $event){
		$player = $event->getPlayer();
		if($player->getLevel()->getFolderName() == $this->getServer()->getDefaultLevel()->getFolderName()){
			$event->setCancelled();
		}
	}

	public function onDrop(PlayerDropItemEvent $event){
		$player = $event->getPlayer();
		if($player->getLevel()->getFolderName() == $this->getServer()->getDefaultLevel()->getFolderName()){
			$event->setCancelled();
		}
	}

	public function onChange(EntityLevelChangeEvent $event){
		$player = $event->getEntity();
		if($player instanceof Player){
			$player->setHealth(20);
			$player->setFood(20);
			if(in_array($player->getName(), $this->red)){
				unset($this->red[array_search($player->getName(), $this->red)]);
				}elseif (in_array($player->getName(), $this->green)){
			    unset($this->green[array_search($player->getName(), $this->green)]);
			    }elseif (in_array($player->getName(), $this->blue)){
				unset($this->blue[array_search($player->getName(), $this->blue)]);
			}
		}
	}
}