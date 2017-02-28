<?php $this->assign('title', 'Login');?>

<div class="row">
    <div class="col-md-6">
        <h3>Inscription</h3>
    <?php
    echo $this->Form->create();
    echo $this->Form->input('formCreaCompte', ['type' => 'hidden']);
    echo $this->Form->input('emailIns', ['label' => 'Adresse Mail', 'type' => 'email', 'required' => true]);
    echo $this->Form->input('mdpIns', ['label' => 'Mot de passe', 'type' => 'password', 'required' => true]);
    echo $this->Form->button('S\'inscrire');
    echo $this->Form->end();
    ?>
    </div>
    <div class="col-md-6">
        <h3>Connexion</h3>
    <?php
    echo $this->Form->create();
    echo $this->Form->input('formConnection', ['type' => 'hidden']);
    echo $this->Form->input('emailCo', ['label' => 'Adresse Mail', 'type' => 'email', 'required' => true]);
    echo $this->Form->input('mdpCo', ['label' => 'Mot de passe', 'type' => 'password', 'required' => true]);
    echo $this->Form->button('Se connecter');
    echo $this->Form->end();
    ?>
    </div>
</div>