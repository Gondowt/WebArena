<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class PlayersTable extends Table
{
    function seConnecter($email, $mdp){//fonction de connexion

        $player = $this->find()
            ->select()
            ->where(['email' => $email])->toArray();


        if(!empty($player)) {
            $player = $player[0]->toArray();

            echo $player['password'];

            if($player['password'] == hash('md5',$mdp)){
                return $player['id'];
            }
            else{
                return null;
            }
        }
        return 0 ;

    }
    function inscription($email, $mdp){//fonction d'inscription
        $player = $this->find()
            ->select()
            ->where(['email' => $email])->toArray();

        if(empty($player)) {
            $id = hash('md5', $email);
            $mdp = hash('md5', $mdp);

            $this->query()
                ->insert(['email', 'password', 'id'])
                ->values([
                    'email' => $email,
                    'password' => $mdp,
                    'id' => $id
                ])
                ->execute();

            return $id;
        }
        return null;

    }
}