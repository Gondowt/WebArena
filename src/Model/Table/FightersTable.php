<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class FightersTable extends Table
{
    function nbFighter($id_player) //fonction retournant le nombre de fighter pour un joueur
    {
        return $this->find()
            ->select(['count' => $this->find()->func()->count('*')])
            ->where(['player_id' => $id_player])
            ->toArray()[0]['count'];
    }

    function caseOccupee($x, $y, $coords){ //fonction qui retourne true si la case est occupé en fonction des coords et false le cas contraire

        foreach($coords as $coord){
            $case_occupee[] = $coord->toArray();
        }

        $coords = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
            ->toArray();

        foreach($coords as $coord){
            $case_occupee[] = $coord->toArray();
        }

        foreach($case_occupee as $case){
            if($case['coordinate_x'] == $x and $case['coordinate_y'] == $y){
                return true;
            }
        }

        return false;
    }

    function addFighter($nomCombattant, $id_player, $coords) //fonction qui permet d'ajouter un combattant en fonction de son nom, de son id, et de ses coords
    {
        $query = $this->query();

        do{
            $x = rand(0, 14);
            $y = rand(0, 9);
        }while($this->caseOccupee($x, $y, $coords));

        $query->insert(['name', 'player_id', 'coordinate_x', 'coordinate_y', 'level', 'xp', 'skill_sight', 'skill_strength', 'skill_health', 'current_health'])
            ->values([
                'name' => $nomCombattant,
                'player_id' => $id_player,
                'coordinate_x' => $x,
                'coordinate_y' => $y,
                'level' => 1,
                'xp' => 0,
                'skill_sight' => 1,
                'skill_strength' => 1,
                'skill_health' => 3,
                'current_health' => 3
            ])
            ->execute();

    }

    function getInfoFighter($id_player)//fonction qui permet d'obtenir les infos utilies sur un combattant
    {
        return $this->find()
            ->select(['id', 'name', 'coordinate_x', 'coordinate_y', 'level', 'xp', 'skill_sight', 'skill_strength', 'skill_health', 'current_health', 'guild_id'])
            ->where(['player_id' => $id_player])
            ->toArray();
    }

    function levelUp($id_player, $skill_number)//fonction qui permet de d'augmenter les skill selon le choix  pour un joueur
    {

        if ($skill_number == 0) {
            $skill = 'skill_health';
            $caracBonus = 3;

            $value = $this->find()
                ->select([$skill, 'xp'])
                ->where(['player_id' => $id_player])
                ->toArray();

            $this->query()
                ->update()
                ->set([$skill => (intval($value[0][$skill]) + $caracBonus), 'xp' => intval($value[0]['xp']) - 4, 'current_health' => (intval($value[0][$skill]) + $caracBonus)])
                ->where(['player_id' => $id_player])
                ->execute();

        } else {
            if ($skill_number == 1) {
                $skill = 'skill_sight';
            } else {
                $skill = 'skill_strength';
            }
            $caracBonus = 1;

            $value = $this->find()
                ->select([$skill, 'xp'])
                ->where(['player_id' => $id_player])
                ->toArray();

            $this->query()
                ->update()
                ->set([$skill => (intval($value[0][$skill]) + $caracBonus), 'xp' => intval($value[0]['xp']) - 4])
                ->where(['player_id' => $id_player])
                ->execute();
        }

    }

    function allFighter() //fonction qui repertorie tout les fighters dans une tableau
    {
        $list_fighters = $this->find()
            ->select(['name', 'player_id', 'coordinate_x', 'coordinate_y'])
            ->toArray();

        return $list_fighters;
    }

    function deplacement($sens, $id_player,$colomnsList, $trapsList, $monstersInvList) //fonction du déplacement du combattant en fonction du sens et de la gestion des décors
    {
        $value = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
            ->where(['player_id' => $id_player])
            ->toArray();
			
		$health = $this->find()
            ->select(['skill_health'])
            ->where(['player_id' => $id_player])
            ->toArray();
			

        if ($sens == 'gauche') {
            $value[0]['coordinate_x'] = $value[0]['coordinate_x'] - 1;
            if ($value[0]['coordinate_x'] < 0) {
                $value[0]['coordinate_x'] = 0;
            }
			
			foreach($colomnsList as $colomn){
				if($value[0]['coordinate_x'] == $colomn['coordinate_x'] and $value[0]['coordinate_y'] == $colomn['coordinate_y'])
				{
					$value[0]['coordinate_x'] = $value[0]['coordinate_x']+1;	
				}
			}
			
			
			

        } else if ($sens == 'droite') {
            $value[0]['coordinate_x'] = $value[0]['coordinate_x'] + 1;
            if ($value[0]['coordinate_x'] > 14) {
                $value[0]['coordinate_x'] = 14;
            }
			
			foreach($colomnsList as $colomn){
				if($value[0]['coordinate_x'] == $colomn['coordinate_x'] and $value[0]['coordinate_y'] == $colomn['coordinate_y'])
				{
					$value[0]['coordinate_x'] = $value[0]['coordinate_x']-1;	
				}
			}
			
        } else if ($sens == 'haut') {
            $value[0]['coordinate_y'] = $value[0]['coordinate_y'] - 1;
            if ($value[0]['coordinate_y'] < 0) {
                $value[0]['coordinate_y'] = 0;
            }
			
			foreach($colomnsList as $colomn){
				if($value[0]['coordinate_x'] == $colomn['coordinate_x'] and $value[0]['coordinate_y'] == $colomn['coordinate_y'])
				{
					$value[0]['coordinate_y'] = $value[0]['coordinate_y']+1;	
				}
			}
			
        } else if ($sens == 'bas') {
            $value[0]['coordinate_y'] = $value[0]['coordinate_y'] + 1;
            if ($value[0]['coordinate_y'] > 9) {
                $value[0]['coordinate_y'] = 9;
            }
			
			foreach($colomnsList as $colomn){
				if($value[0]['coordinate_x'] == $colomn['coordinate_x'] and $value[0]['coordinate_y'] == $colomn['coordinate_y'])
				{
					$value[0]['coordinate_y'] = $value[0]['coordinate_y']-1;	
				}
			}
        }
		
		foreach($trapsList as $trap){
				if($value[0]['coordinate_x'] == $trap['coordinate_x'] and $value[0]['coordinate_y'] == $trap['coordinate_y'])
				{
					$health[0]['skill_health'] = 0;
					echo "Vous etes tombé sur un piège, Vous etes mort !";
					//$this->deleteFighter($id_player);
					
				}
			}
			
		foreach($monstersInvList as $monsterInv){
			if($value[0]['coordinate_x'] == $monsterInv['coordinate_x'] and $value[0]['coordinate_y'] == $monsterInv['coordinate_y'])
			{
				$health[0]['skill_health'] = 0;
				echo "Un monstre invisible vous a térassé !";				
				//$this->deleteFighter($id_player);
				
			}
		}

        $this->query()
            ->update()
            ->set(['coordinate_x' => $value[0]['coordinate_x'], 'coordinate_y' => $value[0]['coordinate_y'], 'skill_health' => $health[0]['skill_health']])
            ->where(['player_id' => $id_player])
            ->execute();
    }

    function actionPossible($case) //determine les actions possibles en fonction de la case
    {
        if ($case['type'] == '0') {
            return array('action' => 'Deplacer', 'fighter' => 'null');
        } else if ($case['type'] == 'fighter') {
            return array('action' => 'Attaquer', 'fighter' => $case['player_id']);
        }
		else if($case['type'] == 'Colomn'){
			return array('action' => 'Pas d action', 'fighter' => 'null');
		}
		else if($case['type'] == 'monsterInv'){
			return array('action' => 'Attaquer', 'fighter' => 'null');
		}
    }

    function actionPossibleFighter($map, $id_player)//determine les actions possible du combatant en fonction de la carte 
    {
        $fighter = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
            ->where(['player_id' => $id_player])
            ->toArray()[0];

        $x = $fighter['coordinate_x'];
        $y = $fighter['coordinate_y'];

        if (isset($map[$y][$x - 1])) {
            $actions['gauche'] = $this->actionPossible($map[$y][$x - 1]); //action gauche
        }
        else{ $actions['gauche'] = array('action' => 'Pas d\'action', 'fighter' => null); }
        if (isset($map[$y][$x + 1])) {
            $actions['droite'] = $this->actionPossible($map[$y][$x + 1]); //action droite
        }
        else{ $actions['droite'] = array('action' => 'Pas d\'action', 'fighter' => null); }
        if (isset($map[$y - 1][$x])) {
            $actions['haut'] = $this->actionPossible($map[$y - 1][$x]); //action haut
        }
        else{ $actions['haut'] = array('action' => 'Pas d\'action', 'fighter' => null); }
        if (isset($map[$y + 1][$x])) {
            $actions['bas'] = $this->actionPossible($map[$y + 1][$x]); //action bas
        }
        else{ $actions['bas'] = array('action' => 'Pas d\'action', 'fighter' => null); }

        return $actions;
    }

    function attaqueReussie($level_attaquant, $level_attaque)//determine si une attaque est reussie ou non
    {
        if (rand(1, 20) > (10 + $level_attaque - $level_attaquant)) {
            return true;
        }
        return false;
    }
	
	function deleteFighter($id_player)//supprime un combattant
	{
		$this->query()
                    ->delete()
                    ->where(['player_id' => $id_player])
                    ->execute();
	}

    function attaquer($id_fighter_attaque, $id_fighter_attaquant, $attaqueBonusGuilde)//fonction d'attaque d'un combattant en fonction de l'attaque et du bonus de guilde
    {
		$level_up=false;
        $fighter_attaque = $this->find()
            ->select()
            ->where(['player_id' => $id_fighter_attaque])
            ->toArray()[0];

        $fighter_attaquant = $this->find()
            ->select()
            ->where(['player_id' => $id_fighter_attaquant])
            ->toArray()[0];

        if ($this->attaqueReussie($fighter_attaquant['level'], $fighter_attaque['level'])) {
			$degats = $fighter_attaquant['skill_strength'] + $attaqueBonusGuilde;
            $vie_attaque = $fighter_attaque['current_health'] - $degats;

            if ($vie_attaque > 0) {
                $this->query()
                    ->update()
                    ->set(['xp' => $fighter_attaquant['xp'] + 1])
                    ->where(['player_id' => $id_fighter_attaquant])
                    ->execute();

                $this->query()
                    ->update()
                    ->set(['current_health' => $vie_attaque])
                    ->where(['player_id' => $id_fighter_attaque])
                    ->execute();
            } else {
                $this->query()
                    ->update()
                    ->set(['xp' => $fighter_attaquant['xp'] + 1 + $fighter_attaque['level']])
                    ->where(['player_id' => $id_fighter_attaquant])
                    ->execute();

                $this->query()
                    ->delete()
                    ->where(['player_id' => $id_fighter_attaque])
                    ->execute();
            }

            $xp = $this->find()
                ->select('xp')
                ->where(['player_id' => $id_fighter_attaquant])
                ->toArray()[0]['xp'];

            if ($xp % 4 == 0) {
                $this->query()
                    ->update()
                    ->set(['level' => $fighter_attaquant['level'] + 1])
                    ->where(['player_id' => $id_fighter_attaquant])
                    ->execute();
					
				
				$level_up=true;
				
            }
			if($level_up)
			{
				return $fighter_attaquant['name']." a attaqué ".$fighter_attaque['name']." et a réussi en ingligeant ".$degats." pdv !  ".$fighter_attaquant['name']." level up !";
			}
			else
			{
				return $fighter_attaquant['name']." a attaqué ".$fighter_attaque['name']." et a réussi en ingligeant ".$degats." pdv !  ".$fighter_attaquant['name']." a maintenant ".$xp." xp !";
			}

        } else {
            return $fighter_attaquant['name']." a attaqué ".$fighter_attaque['name']." et a échoué";;
        }
		
		
    }

	function findPlayerId($playerName)//permet de trouver l'id d'un combatant selon son nom
	{
        return $this->find()
            ->select(['id'])
            ->where(['name' => $playerName])
            ->toArray();
	}
	
	function getListFromName($listMessage)//recupère la liste des expediteurs en fonction de leur message (l'index est correler)
	{
		$myListFromName=[];
		$i=0;
		foreach($listMessage as $message){
			$id = $message['fighter_id_from'];
			$name=$this->find()->select(['name'])->where(['id' => $id])->toArray();
			
			$myListFromName[$i]=$name[0]['name'];
			$i++;
			 
        }
		return $myListFromName;
		
	}
	
	function hasGuild($id_player, $guildId)//affecte une guilde a un combattant
	{
		$this->query()
				->update()
				->set(['guild_id' => $guildId])
				->where(['player_id' => $id_player])
				->execute();
		
	}

    function getGuild($id_player){//recupère l'id de la guilde d'un combattant
        return $this->find()
            ->select('guild_id')
            ->where(['player_id' => $id_player])
            ->toArray()[0]['guild_id'];
    }

    function soutienGuilde($guild_id, $cible){//retourne le bonus d'attaque de guilde si la cible est a coté d'un membre
        $adBonus = 0;
        $coordonee = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
            ->where(['player_id' => $cible])
            ->toArray()[0]->toArray();


        $fightersGuilde = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
            ->where(['guild_id' => $guild_id])
            ->toArray();

        foreach($fightersGuilde as $fighter){
            $fighter = $fighter->toArray();

            if( abs(intval($fighter['coordinate_x'])-intval($coordonee['coordinate_x'])) + abs(intval($fighter['coordinate_y'])-intval($coordonee['coordinate_y'])) == 1){
                ++$adBonus;
            }
        }
        return $adBonus - 1;
    }

    function getSight($id_player){//obtient la vue d'un combattant
        return $this->find()
            ->select(['skill_sight', 'coordinate_x', 'coordinate_y'])
            ->where(['player_id' => $id_player])
            ->toArray()[0];

    }
	
	function alertSurrounding($id_player, $trapsList, $monstersInvList)//retourne une alerte et le fait qu'un combattant est a proximité d'un décors nocif
	{
		$value = $this->find()
            ->select(['coordinate_x', 'coordinate_y'])
            ->where(['player_id' => $id_player])
            ->toArray()[0];
		$monster_nextTo = false;
		$surroundingMessage = "";
			
		foreach($trapsList as $trap){
				if(($value['coordinate_x'] == $trap['coordinate_x']+1 and $value['coordinate_y'] == $trap['coordinate_y']) 
					or  ($value['coordinate_x'] == $trap['coordinate_x']-1 and $value['coordinate_y'] == $trap['coordinate_y'])
						or ($value['coordinate_y'] == $trap['coordinate_y']-1 and $value['coordinate_x'] == $trap['coordinate_x']) 
							or  ($value['coordinate_y'] == $trap['coordinate_y']+1 and $value['coordinate_x'] == $trap['coordinate_x']))
				{
					$surroundingMessage = "Brise Suspecte";
					
				}
		}
			
		foreach($monstersInvList as $monstersInv){
				if(($value['coordinate_x'] == $monstersInv['coordinate_x']+1 and $value['coordinate_y'] == $monstersInv['coordinate_y']) 
					or  ($value['coordinate_x'] == $monstersInv['coordinate_x']-1 and $value['coordinate_y'] == $monstersInv['coordinate_y'])
						or ($value['coordinate_y'] == $monstersInv['coordinate_y']-1 and $value['coordinate_x'] == $monstersInv['coordinate_x']) 
							or  ($value['coordinate_y'] == $monstersInv['coordinate_y']+1 and $value['coordinate_x'] == $monstersInv['coordinate_x']))
				{
					$surroundingMessage = "Puanteur";
					$monster_nextTo=true;
					
				}
		}
		
		return array('message' => $surroundingMessage, 'nextTo' => $monster_nextTo);
		
		
	}
}


