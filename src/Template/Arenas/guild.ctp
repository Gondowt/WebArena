<?php $this->assign('title', 'Guild'); ?>

<div class="row">
    <div class="col-md-5 well ">
        <h3> Creation d'une Guilde </h3>
        <?php 
        echo $this->Form->create();
        echo $this->Form->input('formCreateGuild', ['type' => 'hidden']);
        echo $this->Form->input('Guilde');
        echo $this->Form->button('Creer une guilde', ['class'=>'btn btn-primary']);
        echo $alertGuildToC;
        echo $this->Form->end();

        ?>
        </div>
        <div class="col-xs-offset-2 col-md-5 well">
        <h3> Rejoindre une Guilde </h3>
        <?php
        echo $this->Form->create();
        echo $this->Form->input('formJoinGuild', ['type' => 'hidden']);
        echo $this->Form->input('Rejoindre');
        echo $this->Form->button('Rejoindre la guilde !', ['class'=>'btn btn-primary']);
        echo $alertGuildToJ;
        echo $this->Form->end();
        ?>
    </div>
</div>