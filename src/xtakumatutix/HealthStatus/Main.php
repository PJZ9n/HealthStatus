<?php

namespace xtakumatutix\HealthStatus;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config; //ここまで必須

use pocketmine\event\player\PlayerJoinEvent;

class Main extends PluginBase implements Listener {

    /** @var Config */ //修正
    private $config;

	public function onEnable(){
        $this->getServer()->getLogger()->info("[HealthStatus]読み込み完了v1.1.0_by.xtakumatutix");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
            '現在の体力の前' => '§c[❤',
            '現在の体力と最大体力の間' => '/',
            '最大体力の後ろ' => ']',
        )); // ここのコードはPJZ9nさんに教えてもらいましたサンクス！！
        
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
            function (int $currentTick): void{
                //処理開始
                //全てのオンラインプレイヤーに対してsetTitleする
                foreach ($this->getServer()->getOnlinePlayers() as $player) {
                    $this->setTitle($player);
                }
            }
        ), 20);//20Tick(1秒)おきに。
    }

    public function Onjoin(PlayerJoinEvent $event){
    	$player = $event->getPlayer();
        $this->setTitle($player);
    }
    
    public function setTitle(Player $player){
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $name =$player->getName();
        $health =$player->getHealth();
        $maxHealth =$player->getMaxHealth();
        $config =$this->config->get("現在の体力の前");
        $config2 =$this->config->get("現在の体力と最大体力の間");
        $config3 =$this->config->get("最大体力の後ろ");
        $player->setNameTag($name."\n".$config."".$health."".$config2."".$maxHealth."".$config3."");
    }

}