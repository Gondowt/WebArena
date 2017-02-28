<?php $this->assign('title', 'Communication'); ?>

<div class="row">
    <div class="col-md-6 well">
        <h3> Envoyer un message </h3>
        <?php
        echo $this->Form->create();
        echo $this->Form->input('formChat', ['type' => 'hidden']);
        echo $this->Form->input('Destinataire');
        echo $this->Form->input('Titre');
        echo $this->Form->textarea('chatBox');
        ?>
      
        <?php
        echo $this->Form->button('Send' , ['class'=>'btn btn-primary marg']);
        echo $this->Form->end();
        ?>
    </div>
    <div class="col-xs-offset-1 col-md-5 panel panel-default panel-primary msgrecu">
        
        <h3 class="panel-heading" > Messages Reçus </h3>
        <ul class="panel-body list-group" >
                <?php $i=0 ?>
            <?php foreach($myListMessage as $message){ ?>
                <li class="list-group-item">
                    <p> Message de <?php echo  $listFromName[$i]; ?> posté le <?php echo $message['date']; ?> : </p>
                                <p>Titre : <?php echo  $message['title']; ?></p>
                                <p>Message : <?php echo  $message['message']; ?></p>
                                <?php $i++ ?> 

                </li>
            <?php } ?>
        </ul>
        <?php if ($i==0) {
            echo "<p>Aucun message</p>";
        }  ?>
    </div>
</div>
    
<div class="row">
    <div class="col-md-6 well">
        <h3> Déclarer un événement </h3>
        <?php
        echo $this->Form->create();
        echo $this->Form->input('formCrier', ['type' => 'hidden']);
        echo $this->Form->textarea('descriptionEvent');
        echo $this->Form->button('Crier !', ['class'=>'btn btn-primary marg']);
        echo $this->Form->end();
        ?>
    </div>
</div>