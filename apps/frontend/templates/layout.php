<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>

        

        <title>
            <?php if (!include_slot('title')): ?>
                Quesos Artisan - Sistema de Administración de la Cadena de Suministros
            <?php endif; ?>
            </title>
            <link rel="shortcut icon" href="/favicon.ico" />
            <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
    </head>
            <body>
        <script>$(function() {$("[id*=filtrar]").datepicker();});</script>

                <div id="header-w">
                    <div id="header" class="_">
                        <div id="header-links">
                    <?php if ($sf_user->isAuthenticated()): ?>
                        <div id="user-box">
                            Bienvenido <?php echo $sf_user->getGuardUser()->getName() ?><span>|</span><?php echo link_to('Salir', 'sf_guard_signout') ?>
                        </div>
                        <div>
                           <span id="space-role"><a href="#ayuda" rel="ibox&width=640&height=480">Ayuda</a></span>
                        </div>
                        <div id="ayuda" style="display:none;">
                            <?php include_component_slot('ayuda'); ?>
                        </div>


                    <?php endif ?>
                    </div>

                    <div id="logo">
                        <a href="<?php echo url_for('homepage')?>">
                            <img src="/web/images/logo.png" height="50" width="49" alt="Ir al inicio" title="Ir al inicio"></img>
                        </a>
                    </div>
                    <h1 class="header-w clear-float">
                        <span>Sistema de Administración de la Cadena de Suministros</span>
                    </h1>


                    <div class="cut">&nbsp;</div>

                </div><!-- /header -->
            </div><!-- /header-w -->

            <!-- menu -->
            <?php if ($sf_user->isAuthenticated()): ?>
                <?php include_component_slot('menu'); ?>
				 
            <?php endif ?>
                        <!-- end menu -->

                        <div class="cut">

                        </div>
                        <div id="content">


                <?php if ($sf_user->isAuthenticated()): ?>
                    <div class="ttop-bar" id="NoVerMenu" style="display: block">
                        <?php include_component_slot('submenu'); ?>
                    </div>
                <?php endif ?>

                <?php echo $sf_content ?>

        </div><!-- /content -->


        <div id="footer-w">
            <div class="tutorial-and-bookmark">
            </div>
            <div id="footer">
                <p>
                    <a href="<?php link_to('@homepage')?>">Inicio</a>
                    / <a href="mailto:contacto@quesosartisan.cl">Contacto</a>
                </p>
                <p id="copyr-contact">
                    Copyright © 2011 Quesos Artisan //
                    <strong>Teléfono</strong> 56 - 2 - 4534534 / <strong>E-mail</strong>: <a href="mailto:contacto@quesosartisan.cl">contacto@quesosartisan.cl</a>
                </p>
            </div><!-- /footer -->
        </div><!-- /footer-w -->

    </body>
</html>
