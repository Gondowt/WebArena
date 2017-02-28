<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class MessagesTable extends Table
{
	function addMessage($date, $title, $content, $id_from, $id_to)//ajout d'un message en prenant en compte date, titre, contenu, destinataire et emmeteur
	{
		$query = $this->query();

        $query->insert(['date', 'title', 'message', 'fighter_id_from', 'fighter_id'])
            ->values([
                'date' => $date,
                'title' => $title,
                'message' => $content,
                'fighter_id_from' => $id_from,
                'fighter_id' => $id_to
            ])
            ->execute();
		
	}
	
	function allMessage()//repertorie tout les messages
    {
        $list_Messages = $this->find()
            ->select(['id','date', 'title', 'message', 'fighter_id_from', 'fighter_id'])
            ->toArray();

        return $list_Messages;
    }
	
	function getMessage($messageList, $id)//retourne la liste des messages reçus pour un combattant
	{
		$myListMessage=[];
		$i=0;
		foreach($messageList as $message){
             if($message["fighter_id"] == $id){
				
                $myListMessage[$i]=$message;
				$i++;
             }
         }
		return $myListMessage;
         
		
	}
	
	
	
}