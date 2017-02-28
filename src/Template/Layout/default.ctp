<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('bootstrap.css') ?>
    <?= $this->Html->css('webarena.css') ?>
    <?= $this->Html->script('jquery-3.1.1.js') ?>
    <?= $this->Html->script('bootstrap.js') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
 <div class="navbar-header"><a class="navbar-brand" href="#">Web Arena</a>
      <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
                <li>
                    <?php echo $this->Html->link('Accueil', ['controller' => 'Arenas', 'action' => 'index']); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Combattant', ['controller' => 'Arenas', 'action' => 'fighter']); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Vision', ['controller' => 'Arenas', 'action' => 'sight']); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Journal', ['controller' => 'Arenas', 'action' => 'diary']); ?>
                </li>
				<li>
                    <?php echo $this->Html->link('Guilde', ['controller' => 'Arenas', 'action' => 'guild']); ?>
                </li>
				<li>
                    <?php echo $this->Html->link('Communication', ['controller' => 'Arenas', 'action' => 'communication']); ?>
                </li>
            </ul>
            <?php if ($connect) { ?>
            
                <ul class="nav navbar-nav navbar-right nav-webarena">
                    <li>
                        <?php echo $this->Html->link('Logout', ['controller' => 'Arenas', 'action' => 'logout']); ?>
                    </li>
                </ul>
            <?php } else { ?>
                <ul class="nav navbar-nav navbar-right nav-webarena">
                    <li>
                        <?php echo $this->Html->link('Login', ['controller' => 'Arenas', 'action' => 'login']); ?>
                    </li>
                </ul>
            <?php } ?>
        </div>
</nav>


<?= $this->Flash->render() ?>
<div class="container clearfix" class="transparant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </div>                
</div>
<footer class="text-center">
    <p>Jeu développé par le groupe 05SI2</p>
    <p>Pierre Haikal, Grégoire Jorant-Houzé, Ryan Ugolini, Nicolas Wilhelm</p>
    <p>Options : B, F et D</p>
    <a href="https://github.com/Gondowt/05SI2">Versionning sur GitHub</a>
	<p>Mise en ligne : http://webarena05si2.esy.es/05SI2/arenas </p>
    <p>Copyright ECE Paris</p>
</footer>
</body>
</html>
