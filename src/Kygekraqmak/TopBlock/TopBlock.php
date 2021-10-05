<?php

declare(strict_types=1);

namespace Kygekraqmak\TopBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TopBlock extends PluginBase implements Listener {

    public const PREFIX = C::LIGHT_PURPLE . "[" . C::WHITE . "TopBlock" . C::LIGHT_PURPLE . "] " . C::RESET;
    
    public $data;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->data = new Config($this->getDataFolder()."data.yml", Config::YAML);
    }

    public function onBlockBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $playername = strtolower($event->getPlayer()->getName());
        $data = $this->data;
        $data->set($playername, ($data->get($playername) + 1 ?? 1));
        $data->save();
        $data->reload();
    }

    /**
     * API: Get top block from player
     *
     * @param   Player  $player
     * @return  int
     */
    public function getTopBlock(Player $player) : int {
        $playername = strtolower($player->getName());
        $data = $this->data->get($playername);
        return (empty($data)) ? 0 : $data;
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
        switch ($cmd->getName()) {
            case "topblock":
                if (count($args) < 1) {
                    $dataall = $this->data->getAll();
                    arsort($dataall);
                    $i = 1;
                    $sender->sendMessage(C::BOLD . ">" . C::LIGHT_PURPLE . "> " . C::WHITE . "TopBlock " . C::LIGHT_PURPLE . "Heist Network <" . C::WHITE . "<");
                    foreach ($dataall as $playername => $count) {
                        if ($i > 10) continue;
                        $sender->sendMessage(C::LIGHT_PURPLE . "(" . C::WHITE . $i . C::LIGHT_PURPLE . ") " . C::WHITE . $playername . C::LIGHT_PURPLE . ": " . C::YELLOW . $count . C::GREEN . " Blocks");
                        $i++;
                    }
                } elseif (isset($args[0])) {
                    $blocks = $this->data->get(strtolower($args[0]));
                    $blocks = ($blocks == null) ? 0 : $blocks;
                    $sender->sendMessage(self::PREFIX . C::AQUA . strtolower($args[0]) . C::LIGHT_PURPLE . " telah menghancurkan " . C::YELLOW . $blocks . C::GREEN . " blocks.");
                }
            break;
        }
        return true;
    }

}
