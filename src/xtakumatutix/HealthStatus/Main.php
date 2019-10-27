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
            'フォーマット' => '{name}\n§c[❤{nowhealth}/{maxhealth}]',
            'フォーマットの説明' => '{name} = 名前, {nowhealth} = 現在の体力, {maxhealth} = 最大体力',//本当はresources使ってコメント入れたいところですが難しいので
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
        $name = $player->getName();
        $health = $player->getHealth();
        $maxHealth = $player->getMaxHealth();
        $format = $this->config->get("フォーマット");
        
        //別のやり方もありますが可読性優先
        $format = str_replace("{name}", $name, $format);//{name}を$nameの内容に置き換え
        $format = str_replace("{nowhealth}", $health, $format);//{nowhealth}を$healthの内容に置き換え
        $format = str_replace("{maxhealth}", $maxHealth, $format);//{maxhealth}を$maxHealthの内容に置き換え
        
        $player->setNameTag($format);
    }
}