<ul>
    <li id="cabecera" style="border-bottom:1px solid #2D2D2D;">
        <a>
            <img src="<?php echo BASE_URL; ?>public/app/img/logo-inei.png" />
        </a>

        <button  type="button"  class="emnbtnopt"  id="btnlogin">
            <span class="glyphicon glyphicon-user eglyphicondtmn"  ></span>
        </button>
    </li>
 <?php if ($this->_request->session('rol') === 1): ?>
    
    
        
    <li ><a href="<?php echo BASE_URL; ?>index.php/index/local">Registro de Control de Asistencia al Local</a></li>
   

    
    <li>
        <a href="#mm-m0-p2" class="mm-subopen">Reportes</a>
        <ul class="mm-list mm-panel" id="mm-m0-p2"> 
            <li class="mm-opened mm-selected"><a href="<?php echo BASE_URL; ?>index.php/listar/">Listado de Asistencia local</a></li>
            <li><a href="<?php echo BASE_URL; ?>index.php/listar/reporte">Cuadro Resumen Asistencia</a></li>
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/reporteinventario">Cuadro Resumen Inventario</a></li> -->
        </ul>
    </li>
    

    


 <?php endif; ?>
    
    
    
     <?php if ($this->_request->session('rol') === 2): ?>
    <li>
        <a href="#mm-m0-p1" class="mm-subopen">Registro de Control de Asistencia</a>
        <ul class="mm-list mm-panel" id="mm-m0-p1"> 
            <li class="mm-opened mm-selected"><a href="<?php echo BASE_URL; ?>index.php/index/local">- local</a></li>
            <!-- <li ><a href="<?php #echo BASE_URL; ?>index.php/aula/">- Aula</a></li> -->
        </ul> 
    </li>
    
    <!-- <li>
        <a href="#mm-m0-p2" class="mm-subopen">Registro de Control de Inventario</a>
        <ul class="mm-list mm-panel" id="mm-m0-p2"> 
            <li><a href="<?php #echo BASE_URL; ?>index.php/ficha/">Ficha</a> </li>
            <li><a href="<?php #echo BASE_URL; ?>index.php/cartilla/">Cuadernillo</a></li>    
        </ul>
    </li>  -->
    
    <li>
        <a href="#mm-m0-p3" class="mm-subopen">Reportes</a>
        <ul class="mm-list mm-panel" id="mm-m0-p3"> 
            <li><a href="<?php echo BASE_URL; ?>index.php/listar/">Listado de Asistencia local</a></li>
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/aula">Listado de Asistencia por aula</a></li> -->
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/ficha">Listado de Inventario - Ficha</a></li>
            <li><a href="<?php #echo BASE_URL; ?>index.php/listar/cartilla">Listado de Inventario - Cuadernillo</a></li> -->
                        <li><a href="<?php echo BASE_URL; ?>index.php/listar/reporte">Cuadro Resumen Asistencia</a></li>
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/reporteinventario">Cuadro Resumen Inventario</a></li> -->
        </ul>
    </li>


 <?php endif; ?>
    
   <?php if ($this->_request->session('rol') === 3): ?>
    <li>
        <a href="#mm-m0-p1" class="mm-subopen">Registro de Control de Asistencia</a>
        <ul class="mm-list mm-panel" id="mm-m0-p1"> 
            <li class="mm-opened mm-selected">
                <a href="<?php echo BASE_URL; ?>index.php/index/local">- Local</a>
            </li>
           <!--  <li >
                <a href="<?php #echo BASE_URL; ?>index.php/aula/">- Aula</a>
            </li> -->
        </ul> 
    </li>
    
   <!--  <li>
        <a href="#mm-m0-p2" class="mm-subopen">Registro de Control de Inventario</a>
        <ul class="mm-list mm-panel" id="mm-m0-p2"> 
            <li><a href="<?php #echo BASE_URL; ?>index.php/ficha/">Ficha</a> </li>
            <li><a href="<?php #echo BASE_URL; ?>index.php/cartilla/">Cuadernillo</a></li>    
        </ul>
    </li>  -->
    
    <li>
        <a href="#mm-m0-p3" class="mm-subopen">Reportes</a>
        <ul class="mm-list mm-panel" id="mm-m0-p3"> 
            <li><a href="<?php echo BASE_URL; ?>index.php/listar/">Listado de Asistencia local</a></li>
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/aula">Listado de Asistencia por aula</a></li> -->
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/ficha">Listado de Inventario - Ficha</a></li> -->
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/cartilla">Listado de Inventario - Cuadernillo</a></li> -->
                       <li><a href="<?php echo BASE_URL; ?>index.php/listar/reporte">Cuadro Resumen Asistencia</a></li>
            <!-- <li><a href="<?php #echo BASE_URL; ?>index.php/listar/reporteinventario">Cuadro Resumen Inventario</a></li> -->
        </ul>
    </li>
    <!-- <li>
        <a href="<?php #echo BASE_URL; ?>index.php/index/descargaractualizacion">Descargar Actualizacion</a>
    </li> -->
    
    <li>
        <a href="<?php echo BASE_URL; ?>index.php/index/resetBDIndex">Reset BD</a>
    </li>


 <?php endif; ?>
    
   
</ul>