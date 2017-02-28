<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class EventsTable extends Table
{
    public function displayEvents(){//affichage d'un evenement
        
        $eventReturn = Array();

        $eventList  = $this->find()
                        ->select()
                        ->toArray();


        
        $aujourdhui = new \DateTime();
        $unJour = new \DateInterval('P1D');

        foreach($eventList as $event){
            $event = $event->toArray();
            $date = $event["date"];
            
            if($aujourdhui->sub($unJour) < $date){
                array_push($eventReturn, $event);
            }
            
        }

        return $eventReturn;
    }
    
    
    public function addEvent($eventName, $eventCoordinate_x, $eventCoordinate_y){//ajout d'un evenement
        $eventDate = new \DateTime();

        $query = $this->query();
        $query->insert(['id', 'name', 'date', 'coordinate_x', 'coordinate_y'])
            ->values([
                'id' => "",
                'name' => $eventName,
                'date' => $eventDate,
                'coordinate_x' => $eventCoordinate_x,
                'coordinate_y' => $eventCoordinate_y
            ])
            ->execute();
    }
    
}