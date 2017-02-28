<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class SurroundingsTable extends Table
{
	function addSurrounding($type, $coordinate_x, $coordinate_y)//ajout d'un decors dans la bdd
	{
		$query = $this->query();

        $query->insert(['type', 'coordinate_x', 'coordinate_y'])
            ->values([
                'type' => $type,
                'coordinate_x' => $coordinate_x,
                'coordinate_y' => $coordinate_y
            ])
            ->execute();
		
		
	}

    function allObstacle(){//recense les coordonnées de tout les décors
        return $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
            ->toArray();
    }

	function allColomn()//recense les coordonnées de toutes les colonnes
    {
        $list_Colomn = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
			->where(['type' => 'Colomn'])
            ->toArray();

        return $list_Colomn;
    }
	
	function allTrap()//recense les coordonnées de tout les pieges
    {
        $list_Trap = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
			->where(['type' => 'Trap'])
            ->toArray();

        return $list_Trap;
    }
	
	function allMonsterInv()//recense les coordonnées de tout les monstres invisibles
    {
        $list_MonsterInv = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
			->where(['type' => 'monsterInv'])
            ->toArray();

        return $list_MonsterInv;
    }
	
	function nbSurrounding()//recense le nombre de décors
    {
        return $this->find()
            ->select(['count' => $this->find()->func()->count('*')])
            ->toArray()[0]['count'];
    }
	
	function deleteMonster()//suprime un monstre invisible de la bdd
	{
		$this->query()
                    ->delete()
                    ->where(['type' => 'monsterInv'])
                    ->execute();
		
	}
	
	
}