<?php $this->assign('title', 'WebArena - Your fighter'); ?>

<?php
if ($combattant == true) { ?>
    <div class="fighter">
        <div class="col-md-6">
            <div class="panel panel-default statfighter">
                <div class="panel-body avatar">
                    <?php echo $this->Html->image($pathAvatar, ['alt' => 'Avatar Image']); ?>

                    <?php
                    echo $this->Form->create(null, ['type' => 'file']);
                    echo $this->Form->input('formAvatar', ['type' => 'hidden']);
                    echo $this->Form->input('avatar', ['label' => 'Choisir un avatar :', 'type' => 'file']);
                    echo $this->Form->button('Envoyer l\'avatar', ['class' => 'btn btn-primary']);
                    echo $this->Form->end();
                    ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item  statfighter">Nom : <?php echo $name; ?></li>
                <li class="list-group-item  statfighter">Niveau : <?php echo $level; ?></li>
                <li class="list-group-item  statfighter">Guilde : <?php echo $guilde; ?></li>
                <li class="list-group-item  statfighter">Vision : <?php echo $sight; ?></li>
                <li class="list-group-item  statfighter">Force : <?php echo $strength; ?></li>

                <li class="list-group-item  statfighter">
                    Vie :

                    <div class="progress  statfighter">
                        <div class="progress-bar vie" role="progressbar" aria-valuenow="<?php echo $ratioVie; ?>"
                             aria-valuemin="0"
                             aria-valuemax="<?php echo $healthmax; ?>" style="width:<?php echo $ratioVie; ?>%">
                            <?= $healthcurrent; ?> /<?= $healthmax; ?>
                        </div>
                    </div>

                </li>
                <li class="list-group-item  statfighter"> Xp :

                    <div class="progress  statfighter">
                        <div class="progress-bar xp" role="progressbar" aria-valuenow="<?php echo($xp % 4); ?>"
                             aria-valuemin="0"
                             aria-valuemax="4" style="width:<?php echo(($xp % 4) * 100 / 4); ?>%">
                            <?php echo $xp % 4; ?> / 4
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <?php if ($xp >= 4) { ?>

            <div class="col-md-12">
                <div class="panel panel-default statfighter">
                    <div class="panel-body chxNiveau">
                        <div class="statfighter">Passer Niveau : <?php echo floor($xp / 4); ?> Choix Restants</div>
                        <?php
                        echo $this->Form->create();
                        echo $this->Form->input('formPassageNiveau', ['type' => 'hidden']);
                        ?>
                        <div class="col-md-12">
                            <?php
                            echo $this->Form->radio(
                                'level_UP',
                                [
                                    ['label' => 'Plus de vie'],
                                    ['label' => 'Plus de vision'],
                                    ['label' => 'Plus de force']
                                ]
                            ); ?>
                        </div>
                        <div class="col-md-12">
                            <?php
                            echo $this->Form->button('Passer le niveau', ['class' => 'btn btn-primary']);
                            echo $this->Form->end();
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>

<?php } else { ?>
    <div class="panel panel-default statfighter">
        <div class="panel-body">
            <?php
            echo $this->Form->create();
            echo $this->Form->input('formCreaCombattant', ['type' => 'hidden']);
            echo $this->Form->input('nameCombattant', ['label' => 'Nom du Combattant']);
            echo $this->Form->button('Creer Combattant', ['class' => 'btn btn-primary']);
            echo $this->Form->end();
            ?>
        </div>
    </div>
<?php } ?>