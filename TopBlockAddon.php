<?php

declare(strict_types=1);

/**
 * @name TopBlockAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\TopBlockAddon
 * @depend TopBlock
 */
namespace JackMD\ScoreHud\Addons
{
    
    use JackMD\ScoreHud\addon\AddonBase;
    use Kygekraqmak\TopBlock\TopBlock;
    use pocketmine\Player;
    
    class TopBlockAddon extends AddonBase {
        
        private $topblock;
        
        public function onEnable() : void {
            $this->topblock = $this->getServer()->getPluginManager()->getPlugin("TopBlock");
        }
        
        public function getProcessedTags(Player $player) : array {
            return [
                "{topblock}" => $this->topblock->getTopBlock($player)
            ];
        }
        
    }
    
}