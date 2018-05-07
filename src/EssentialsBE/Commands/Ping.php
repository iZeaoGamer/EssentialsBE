<?php

declare(strict_types = 1);

namespace EssentialsBE\Commands;

use pocketmine\Player;
use EssentialsBE\BaseFiles\BaseAPI;
use EssentialsBE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class Ping extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "ping", "Pong!");
        $this->setPermission("essentials.ping");
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
        if(!$sender instanceof Player){
            return false;
        }
        $sender->sendMessage("ยง6" . $sender->getPing() . "ms");
        return true;
    }
}
