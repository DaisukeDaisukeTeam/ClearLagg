<?php
namespace clearlagg;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\entity\object\ItemEntity;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase{
    protected $exemptedEntities = [];

    public function onEnable(): void{
        $this->getServer()->getCommandMap()->register("clearlagg", new ClearLaggCommand($this));
        //$this->getLogger()->info(TextFormat::YELLOW . "Enabling...");
    }

    /**
     * @return int
     */
    public function removeEntities(){
        $i = 0;
        foreach($this->getServer()->getWorldManager()->getWorlds() as $level){
            foreach($level->getEntities() as $entity){
                if(!$this->isEntityExempted($entity) && !($entity instanceof Living)){
                    $entity->close();
                    $i++;
                }
            }
        }
        return $i;
    }

    /**
     * @return int
     */
    public function removeMobs(){
        $i = 0;
        foreach($this->getServer()->getWorldManager()->getWorlds() as $level){
            foreach($level->getEntities() as $entity){
                if(!$this->isEntityExempted($entity) && $entity instanceof Living && !($entity instanceof Human)){
                    $entity->close();
                    $i++;
                }
            }
        }
        return $i;
    }

    /**
     * @return int
     */
    public function removeDroppedItems(){
        $i = 0;
        foreach($this->getServer()->getWorldManager()->getWorlds() as $level){
            foreach($level->getEntities() as $entity){
                if(!$this->isEntityExempted($entity) && ($entity instanceof ItemEntity)){
                    $entity->close();
                    $i++;
                }
            }
        }
        return $i;
    }

    /**
     * @return array
     */
    public function getEntityCount(){
        $ret = [0, 0, 0];
        foreach($this->getServer()->getWorldManager()->getWorlds() as $level){
            foreach($level->getEntities() as $entity){
                if($entity instanceof Human){
                    $ret[0]++;
                }
                elseif($entity instanceof Living){
                    $ret[1]++;
                }
                else{
                    $ret[2]++;
                }
            }
        }
        return $ret;
    }

    /**
     * @param Entity $entity
     */
    public function exemptEntity(Entity $entity){
        $this->exemptedEntities[$entity->getID()] = $entity;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function isEntityExempted(Entity $entity){
        return isset($this->exemptedEntities[$entity->getID()]);
    }
} 
