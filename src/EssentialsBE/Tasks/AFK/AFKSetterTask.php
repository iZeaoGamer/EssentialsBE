<?php
namespace EssentialsBE\Tasks\AFK;

use EssentialsBE\BaseFiles\BaseTask;
use EssentialsBE\BaseFiles\BaseAPI;
use pocketmine\utils\TextFormat;

class AFKSetterTask extends BaseTask{

    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api);
    }

    /*
     * This task is executed every 30 seconds,
     * with the purpose of checking all players' last movement
     * time, stored in their 'Session',
     * and check if it is pretty near,
     * or it's over, the default Idling limit.
     *
     * If so, they will be set in AFK mode
     */

    /**
     * @param int $currentTick
     */
    public function onRun($currentTick){
        $this->getAPI()->getServer()->getLogger()->debug(TextFormat::YELLOW . "Running EssentialsBE's AFKSetterTask");
        foreach($this->getAPI()->getServer()->getOnlinePlayers() as $p){
            if(!$this->getAPI()->isAFK($p) && ($last = $this->getAPI()->getLastPlayerMovement($p)) !== null && !$p->hasPermission("essentials.afk.preventauto")){
                if(time() - $last >= $this->getAPI()->getEssentialsBEPlugin()->getConfig()->getNested("afk.auto-set")){
                    $this->getAPI()->setAFKMode($p, true, $this->getAPI()->getEssentialsBEPlugin()->getConfig()->getNested("afk.auto-broadcast"));
                }
            }
        }
        // Re-Schedule the task xD
        $this->getAPI()->scheduleAutoAFKSetter();
    }
}