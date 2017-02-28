<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
/**
 * Personal Controller
 * User personal interface
 *
 */
class ArenasController extends AppController
{

    public function index() //index de la page
    {
		//Verification de la connexion
        if($this->request->session()->check('player_id')) {
            $this->set('connect', true);
            
        }
        else {
            $this->set('connect', false);
        }
    }

    public function login() //page login et de creation de compte
    {
		//Verification de la connexion
        if($this->request->session()->check('player_id')) {
            $this->set('connect', true);
        }
        else {
            $this->set('connect', false);
        }

        $this->loadModel('Players');

        if($this->request->is('post')){
			//Reception du formulaire de Creation d'un compte
            if (isset($this->request->data['formCreaCompte'])){
                $id = $this->Players->inscription($this->request->data['emailIns'], $this->request->data['mdpIns']);

                if($id != null){
                    $this->request->session()->write('player_id', $id);
                    $this->redirect('/Arenas/fighter');
                }
            }
			//Reception du formulaire de connexion
            if (isset($this->request->data['formConnection'])){
                $id = $this->Players->seConnecter($this->request->data['emailCo'], $this->request->data['mdpCo']);

                if($id != null){
                    $this->request->session()->write('player_id', $id);
                    $this->redirect('/Arenas/fighter');
                }
            }
        }
    }

    public function fighter() //page perso fighter et creation fighter
    {
        $this->loadModel('Fighters');
        $this->loadModel('Guilds');
		//Verification de la connexion
        if($this->request->session()->check('player_id')) {
            $id_player = $this->request->session()->read('player_id');
            $this->set('connect', true);
            
        }
        else {
            return $this->redirect('/Arenas/login');
        }

        if ($this->request->is('post')) {
			//reception formulaire de création de combattant
            if (isset($this->request->data['formCreaCombattant'])) {
                $this->loadModel('Surroundings');
                $this->Fighters->addFighter($this->request->data['nameCombattant'], $id_player, $this->Surroundings->allObstacle());
            }
			//reception formulaire de passage de niveau
            if (isset($this->request->data['formPassageNiveau'])) {
                $this->Fighters->levelUp($id_player, $this->request->data['level_UP']);
            }
			//reception du formulaire d'insertion d'une image d'avatar
            if (isset($this->request->data['formAvatar'])) {
                if ($this->request->data['avatar']['error'] == 0) {
                    if ($this->request->data['avatar']['size'] <= 1000000) {
                        $infosfichier = pathinfo($this->request->data['avatar']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'png');
                        if (in_array($extension_upload, $extensions_autorisees)) {
                            move_uploaded_file($this->request->data['avatar']['tmp_name'], 'img/avatar/' . basename($id_player . '.' . $infosfichier['extension']));
                        }
                    }
                }
            }
        }

        $combattant = $this->Fighters->nbFighter($id_player);
		
		//S'il y a un combattant, on reçoit les infos le concernant(image d'avatar, guilde, ...)
        if ($combattant != 0) {
            $avatar = '/img/avatar/noone.png';

            foreach (scandir('img/avatar/') as $key => $img) {
                if ($img[0] != '.') {
                    if (strstr($img, $id_player)) {
                        $avatar = '/img/avatar/' . $img;
                    }
                }
            }

            $id_guild = $this->Fighters->getguild($id_player);
            if($id_guild != null) {
                $guild = $this->Guilds->nameguild($id_guild);
                $this->set('guilde', $guild[0]['name']);
            }
            else{
                $this->set('guilde', 'Pas de guilde');
            }


            $this->set('pathAvatar', $avatar);

            $fighter = $this->Fighters->getInfoFighter($id_player);

            $this->set('name', $fighter[0]['name']);
            $this->set('level', $fighter[0]['level']);
            $this->set('sight', $fighter[0]['skill_sight']);
            $this->set('strength', $fighter[0]['skill_strength']);
            $this->set('healthmax', $fighter[0]['skill_health']);
            $this->set('healthcurrent', $fighter[0]['current_health']);
            $this->set('ratioVie', $fighter[0]['current_health'] * 100/$fighter[0]['skill_health']);
            $this->set('xp', $fighter[0]['xp']);
            $this->set('combattant', true);

        }
        else {
            $this->set('combattant', false);
        }


    }

    public function sight() // page vue avec la carte et les différentes actions
    {
		//Verification de la connexion
        if($this->request->session()->check('player_id')) {
            $id_player = $this->request->session()->read('player_id');
            $this->set('connect', true);
        }
        else {
            return $this->redirect('/Arenas/login');
        }

        $this->loadModel('Fighters');
		$this->loadModel('Events');
		
		//Si le joeur n'a pas de combattant, on le redirige sur la page de création
		if($this->Fighters->nbFighter($id_player)==0)
		{
			return $this->redirect('/Arenas/fighter');
			
		}

		//si le joueur n'a plus de vie, on le supprime de la bdd + redirige vers la page de creation de combattant + on déclare l'event
		if($this->Fighters->getInfoFighter($id_player)[0]['skill_health']==0)
		{
			$coordX = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_x'];
			$coordY = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_y'];
			$name = $this->Fighters->getInfoFighter($id_player)[0]['name'];
			$event = $name." est mort !";
			$this->Events->addEvent($event, $coordX, $coordY);
			$this->Fighters->deleteFighter($id_player);return $this->redirect('/Arenas/fighter');
			return $this->redirect('/Arenas/fighter');
		}

		//definition des caractéristiques de la map
        $largeur = 15;
        $longueur = 10;
		
		
		$this->loadModel('Surroundings');
		
		//Si il n'y a deja pas de decors, on les génère aléatoirement
		if($this->Surroundings->nbSurrounding()==0)
		{
			$posx = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_x'];
			$posy = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_y'];
			$xCol=1;
			$yCol=1;
			$xTrap=1;
			$yTrap=1;
			for($i = 0; $i < intval(max($longueur,$largeur)/10)+1; ++$i)
			{
				$xCol = rand($xCol,$largeur-1);
				$yCol = rand($yCol,$longueur-1);
				if($posx != $xCol and $posy != $yCol)
				{
					$this->Surroundings->addSurrounding("Colomn", $xCol, $yCol);
				}
				$xTrap = rand($xTrap,$largeur-1);
				$yTrap = rand($yTrap,$longueur-1);
				if($posx != $xTrap and $posy != $yTrap)
				{
					$this->Surroundings->addSurrounding("Trap", $xTrap, $yTrap);
				}
			}
				
			
			
			$xMonster = rand(1,$largeur-1);
			$yMonster = rand(1,$longueur-1);
			if($posx != $xMonster and $posy != $yMonster)
			{
				$this->Surroundings->addSurrounding("monsterInv", $xMonster, $yMonster);
			}
			
		}
		
		//on recupère les décors par types
		$allColomns = $this->Surroundings->allColomn();
		$allTraps = $this->Surroundings->allTrap();
		$allMonstersInv = $this->Surroundings->allMonsterInv();
		
		$surroundingMessage="";

		//on récupère l'action de l'utilisateur
        if ($this->request->is('post')) {
            if (isset($this->request->data['formAction'])) {
				//on recupère ici son action
                $action_a_faire = explode('/', $this->request->data['action']);
				
				//si le joueur attaque...
                if ($action_a_faire[0] == 'Attaquer') {
                    $guild_id = $this->Fighters->getGuild($id_player);
                    if($guild_id != null){
                        $attaqueBonusGuilde = $this->Fighters->soutienGuilde($guild_id, $action_a_faire[2]);
                    }
                    else{
                        $attaqueBonusGuilde = 0;
                    }
				
                    //gestion d'un monstre invisible a proximité
					$monster_nextTo = $this->Fighters->alertSurrounding($id_player, $allTraps, $allMonstersInv)['nextTo'];
					
					//si l'entité attaquée est un monstre ...
					if($monster_nextTo)
					{
						$x = $this->Surroundings->allMonsterInv()[0]['coordinate_x'];
						$y = $this->Surroundings->allMonsterInv()[0]['coordinate_y'];
						$this->Surroundings->deleteMonster();
						$map[$y][$x]['type'] = '0';
						return $this->redirect('/Arenas/sight');
						
					}// si l'entité attaquée est un autre joueur
					else
					{
						$event = $this->Fighters->attaquer($action_a_faire[2], $id_player, $attaqueBonusGuilde);
						$coordX = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_x'];
						$coordY = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_y'];
						$this->Events->addEvent($event, $coordX, $coordY);
					} // si le joueur se déplace ...
                } else if ($action_a_faire[0] == 'Deplacer') {
                    $this->Fighters->deplacement($action_a_faire[1], $id_player, $allColomns, $allTraps, $allMonstersInv);
					$surroundingMessage = $this->Fighters->alertSurrounding($id_player, $allTraps, $allMonstersInv)['message'];
					$coordX = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_x'];
					$coordY = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_y'];
					$name = $this->Fighters->getInfoFighter($id_player)[0]['name'];
					$event = $name." se deplace sur la case {".$coordX.','.$coordY.'}';
					$this->Events->addEvent($event, $coordX, $coordY);
                }
            }
        }
		
		
		//ajout de l'aspect graphique de la map - Initialisation
        for ($y = 0; $y < $longueur; ++$y) {
            for ($x = 0; $x < $largeur; ++$x) {
                $map[$y][$x]['type'] = '0';
                $map[$y][$x]['name'] = '0';
            }
        }

        $list_fighters = $this->Fighters->allFighter();
		//ajout de l'aspect graphique de la map - ajout des joueurs
        foreach ($list_fighters as $fighter) {
            $x = $fighter->toArray()['coordinate_x'];
            $y = $fighter->toArray()['coordinate_y'];

            $map[$y][$x]['name'] = $fighter->toArray()['name'];
            $map[$y][$x]['type'] = 'fighter';
            $map[$y][$x]['player_id'] = $fighter->toArray()['player_id'];
        }
		//ajout de l'aspect graphique de la map - ajout des colonnes
		foreach ($allColomns as $colomn) {
            $x = $colomn['coordinate_x'];
            $y = $colomn['coordinate_y'];
            $map[$y][$x]['name'] = 'C';
            $map[$y][$x]['type'] = 'Colomn';
            
        }
		//ajout de l'aspect graphique de la map - ajout des monstres invisibles
		foreach ($allMonstersInv as $monsterInv) {
            $x = $monsterInv['coordinate_x'];
            $y = $monsterInv['coordinate_y'];
            $map[$y][$x]['name'] = '0';
            $map[$y][$x]['type'] = 'monsterInv';
            
        }
		//Receuil des actions possible du combatant
        $actions = $this->Fighters->actionPossibleFighter($map, $id_player);

        $this->set('map', $map);
        $this->set('action', $actions);
        $this->set('porteeVision', $this->Fighters->getSight($id_player));
		$this->set('surroundingMessage', $surroundingMessage);
		
		
    }
	
	public function guild() // Page de Guilde (Creation, rejoindre)
    {
		//Verification de la connexion
		if($this->request->session()->check('player_id')) {
            $id_player = $this->request->session()->read('player_id');
            $this->set('connect', true);
        }
        else {
            return $this->redirect('/Arenas/login');
        }
		$this->loadModel('Guilds');
		$this->loadModel('Fighters');
		$this->loadModel('Events');
		
		//Si le joueur n'a pas de combattant on redirige sur la page de création
		if($this->Fighters->nbFighter($id_player)==0)
		{
			return $this->redirect('/Arenas/fighter');
		}
		$this->set('alertGuildToC', "");
		$this->set('alertGuildToJ', "");
		
		//Reception du formulaire de création de guilde
		if ($this->request->is('post')) {
            if (isset($this->request->data['formCreateGuild'])) {
				
				$nameGuildToC= $this->request->data['Guilde'];
				$currentGuildId = $this->Fighters->getInfoFighter($id_player)[0]['guild_id'];
				if($this->Guilds->existGuild($this->Guilds->allGuild(), $nameGuildToC))
				{
					$alertGuildToC =  "Guilde déja existante !";
				}
				else if($currentGuildId!=NULL)
				{
					$alertGuildToC =  "Vous etes deja dans une guilde !";
				}
				else
				{
					$this->Guilds->addGuild($nameGuildToC);
					$idGuild = $this->Guilds->findId($nameGuildToC)['id'];
					$this->Fighters->hasGuild($id_player, $idGuild);
					$alertGuildToC =  "Guilde créée !";
				}
				$this->set('alertGuildToC', $alertGuildToC);
            }
        }
		//Reception du formulaire de rejoint de guilde
		if ($this->request->is('post')) {
            if (isset($this->request->data['formJoinGuild'])) {
				
				$nameGuildToJ= $this->request->data['Rejoindre'];
				$idGuild = $this->Guilds->findId($nameGuildToJ)['id'];
				$currentGuildId = $this->Fighters->getInfoFighter($id_player)[0]['guild_id'];
				if($currentGuildId!=NULL)
				{
					$alertGuildToJ =  "Vous etes deja dans une guilde !";
				}
				else if($this->Guilds->existGuild($this->Guilds->allGuild(), $nameGuildToJ)==false)
				{
					$alertGuildToJ =  "Guilde non existante !";
				}
				else
				{
					$this->Fighters->hasGuild($id_player, $idGuild);
					$coordX = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_x'];
					$coordY = $this->Fighters->getInfoFighter($id_player)[0]['coordinate_y'];
					$name = $this->Fighters->getInfoFighter($id_player)[0]['name'];
					$event = $name." a rejoint la guilde ".$nameGuildToJ;
					$this->Events->addEvent($event, $coordX, $coordY); //On déclare l'event
					$alertGuildToJ =  "Vous avez rejoins une nouvelle guilde !";
					
				}
			
                $this->set('alertGuildToJ', $alertGuildToJ);
            }
        }

    }
	
	public function communication() // page de comunication (envoie message + reception + Crier)
    {
		//Verification de la connexion
		if($this->request->session()->check('player_id')) {
            $id_player = $this->request->session()->read('player_id');
            $this->set('connect', true);
        }
        else {
            return $this->redirect('/Arenas/login');
        }

		$this->loadModel('Fighters');
		
		//Si le joueur n'a pas de combatant il est redirigé sur la page de creation
		if($this->Fighters->nbFighter($id_player)==0)
		{
			return $this->redirect('/Arenas/fighter');
		}
        
		$myId = $this->Fighters->getInfoFighter($id_player)[0]['id'];
		$this->loadModel('Messages');
		//Recupération du formulaire de creation de message
		if ($this->request->is('post')) {
            if (isset($this->request->data['formChat'])) {
				
				$destName= $this->request->data['Destinataire'];
				$title= $this->request->data['Titre'];
                $content = $this->request->data['chatBox'];
				$date = Time::now();

				$destId = $this->Fighters->findPlayerId($destName);

                if (!empty($destId)) {
                    $this->Messages->addMessage($date, $title, $content, $myId, $destId[0]->toArray()['id']);
                }

            }
        }
		//on receptionne ici les messages et les emetteurs les concernant
		$myListMessage = "Pas de Message Reçu";
		$listFromName ="";
		$myListMessage = $this->Messages->getMessage($this->Messages->allMessage(),$myId);
		$listFromName = $this->Fighters->getListFromName($myListMessage);
		
	    
		
		$this->set('myListMessage', $myListMessage);
		$this->set('listFromName', $listFromName);
		
		//Reception du formulaire de crie
		$this->loadModel('Events');
		if ($this->request->is('post')) {
            if (isset($this->request->data['formCrier'])) {
				
				$descriptionEvent= $this->request->data['descriptionEvent'];
				$date = Time::now();
				$fighter = $this->Fighters->getInfoFighter($id_player);
				$coordX = $fighter[0]['coordinate_x'];
				$coordY = $fighter[0]['coordinate_y'];
				
				$this->Events->addEvent($descriptionEvent, $coordX, $coordY);

                return $this->redirect('/Arenas/diary');
            }
        }

    }

    public function diary() //page du journal où tout les events sont listés
    {
		//Verification de la co
        if($this->request->session()->check('player_id')) {
            $this->set('connect', true);
        }
        else {
            return $this->redirect('/Arenas/login');
        }

        $this->loadModel('Events');
		//affichage des events
        $this->set('events', $this->Events->displayEvents());
    }

    public function logout() //page de déconnexion
    {
        $this->request->session()->destroy();
        $this->redirect('/Arenas/login');
    }

}
