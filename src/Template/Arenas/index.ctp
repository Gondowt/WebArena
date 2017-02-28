
<div class="container imgacc">
    <h1 class="row" >
        <div class="col-xs-offset-4 col-xs-4 well">
            <h1>Bienvenu dans WebArena!</h1>            
        </div>
    </h1>    
    <section class="row"> 
        <div class="col-lg-8 col-lg-offset-2 well">
            <h2>Regles de jeu</h2>
            <p>Découvrez ce monde plein de supprise et d'aventure !</p> 
            <p>Les regles du jeu sont simples !<p>
            <p>Une fois que vous avez creer un compte, puis un joueur vous pouvez affrontez vos adversaires, explorer un nouveau monde, Augmenter vos capacites, Rejoindre une guilde.<p>
            <p> Attention des obstacles pourraient se trouver sur votre route ... </p>
            <p>Soyez vigilent, la mort peut vous surprendre a tout moment et vous devrez tout recommencer de zero.</p>
            <p>Vous pourrez réalisé vos actions via des choix proposés sur les différentes pages:</p>
            <div class="list-group">
                <div><?php echo $this->Html->link('Vision : Vous avez accès a ce que vous voyez, votre emplacement dans le monde et vous pouvez vous déplacez',['controller' => 'Arenas', 'action' => 'sight'], ['class' => 'list-group-item']); ?></div>
                <div><?php echo $this->Html->link('Combattant : Vous avez accès a votre combatant et ses caractèristiques', ['controller' => 'Arenas', 'action' => 'fighter'], ['class' => 'list-group-item']); ?></div>
                <div><?php echo $this->Html->link('Journal : Vous avez accès a votre journal qui vous présentera les événement près de vous les dernière 24h',['controller' => 'Arenas', 'action' => 'diary'], ['class' => 'list-group-item']); ?></div>
                <div><?php echo $this->Html->link('Guilde : Participez a la vie de guilde !', ['controller' => 'Arenas', 'action' => 'guild'], ['class' => 'list-group-item']); ?></div>
                <div><?php echo $this->Html->link('Communication : Parler avec les autres joueur !', ['controller' => 'Arenas', 'action' => 'communication'], ['class' => 'list-group-item']); ?></div>
            </div>
            <h6>Maintenant à vous!</h6> 
            <p><?php echo $this->Html->link('JOUER!',['controller' => 'Arenas', 'action' => 'login'], ['class' => 'btn btn-lg']) ?> </p>
        </div>
    </section>
</div>




