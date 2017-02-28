<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class GuildsTable extends Table
{
	function addGuild($name) //ajout d'une guilde dans la bdd
	{
		$query = $this->query();

        $query->insert(['name'])
            ->values([
                'name' => $name
            ])
            ->execute();
		
	}
	
	function allGuild()//recense toutes les guildes
    {
        $list_Guilds = $this->find()
            ->select(['id', 'name'])
            ->toArray();

        return $list_Guilds;
    }
	
	function existGuild($guilds, $name)//retourne si une guilde existe ou non parmis toute les guildes
	{
		
		foreach($guilds as $guild){
            if($guild["name"] == $name){
				return true;
            }
         }
		return false;
		
	}
	
	function findId($name)//retourne l'id d'une guilde en fonction de son nom
    {
        return $this->find()
            ->select(['id'])
            ->where(['name' => $name])
            ->first();
    }

    function nameguild($id_guild){//retourne le nom d'une guilde en fonction de son id
        return $this->find()
            ->select(['name'])
            ->where(['id' => $id_guild])
            ->toArray();
    }
	
	
	
}