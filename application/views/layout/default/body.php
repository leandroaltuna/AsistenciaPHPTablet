<body>
    <div id="page">
        <div id="header" class="mm-fixed-top">
            <a href="#menu" id="nav-menu" ></a>
            <div class="btn-menu-left">
                <div class="elismnright">
                    <ul>
                        <li class="emnlibtn">
                            <img src="<?php echo BASE_URL ?>public/app/img/logo-inei-desktop.png">
                        </li>
                        <li class="emnlileft">   CAPACITACI&Oacute;N DE LA EDNOM 2014  </li>
                    </ul>
                </div>
            </div>

            <div class="ebtnmnight">
                <div class="elismnright">
                    <ul>
                        <li class="emnlibtn btn-space">

                            <div class="btn-group">
                                <button type="button" class="emnbtnopt dropdown-toggle"
                                        data-toggle="dropdown">
                                    <a href="#"><span class="glyphicon glyphicon-user eglyphicondtmn" id="glp-btn-menu"></span></a>
                                </button>

                                <ul class="dropdown-menu-login" role="menu">
                                    <li>
                                        <div class="arrow"></div>
                                        <div id="popoverExampleTwoHiddenContent" style="display: inherit">
                                           
                                            <div class="clogin">
                                                <span  class="tlogin espace-bottom"> Rol:&nbsp;<?php echo $this->_request->session('usuario');?></span>
                       
                                            </div>
                                        </div>
                                        <div class="eloginbtn">
                                            <a href="<?php echo BASE_URL; ?>index.php/auth/auth/cerrar"> <button type="button" class="btn ebtn-default" >Cerrar Sesión</button></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <nav id="menu">
            <?php include_once 'menu.php' ?>
        </nav>
        <div id="content">
            <div class="container-fluid">
                <br/><br/><br/>