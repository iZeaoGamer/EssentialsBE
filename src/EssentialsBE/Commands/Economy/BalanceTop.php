<?php

declare(strict_types = 1);

namespace EssentialsBE\Commands\Economy;

use EssentialsBE\BaseFiles\BaseAPI;
use EssentialsBE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BalanceTop extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "balancetop", "See the top money", "", true, ["topbalance", "topmoney", "moneytop"]);
        $this->setPermission("essentials.balancetop.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . " §b§l--- Money top list ---§r");
        $this->getAPI()->sendBalanceTop($sender);
        return true;
    }
}
