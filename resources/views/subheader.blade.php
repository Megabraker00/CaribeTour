<div class="row subheader">
    <div class="threecol column subheader-block">
        <!-- tour search form -->
        <script type="text/javascript">
            /* <![CDATA[ */
            var labels = {"dateFormat":"dd-mm-yy","dayNames":["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],"dayNamesMin":["Do","Lu","Ma","Mi","Ju","Vi","Sa"],"monthNames":["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],"firstDay":"1","prevText":"Mes Anterior","nextText":"Mes Siguiente"};
            /* ]]> */
        </script>
        <div class="tour-search-form placeholder-form">
            <div class="form-title">
                <h4>Encuentra tu Destino</h4>
            </div>
            <form role="search" method="get" action="resultado.php">
                <div class="select-field">
                    <span>Todos los Destinos</span>
                    <select name="cat" id="destination" class="postform" style="opacity: 0;">
                        <option value="0">Todos los Destinos</option>
                        <option value="1">República Dominicana</option>
                        <option value="5">&nbsp;&nbsp;&nbsp; Santo Domingo</option>
                        <option value="7">&nbsp;&nbsp;&nbsp; Punta Cana</option>
                        <option value="11">&nbsp;&nbsp;&nbsp; La Romana</option>
                        <option value="12">&nbsp;&nbsp;&nbsp; Puerto Plata</option>
                        <option value="2">México</option>
                        <option value="6">&nbsp;&nbsp;&nbsp; Rivera Maya</option>
                        <option value="9">&nbsp;&nbsp;&nbsp; Yucatan</option>
                        <option value="10">&nbsp;&nbsp;&nbsp; Cancún</option>
                        <option value="3">Cuba</option>
                        <option value="8">&nbsp;&nbsp;&nbsp; Varadero</option>
                        <option value="13">Puerto Rico</option>
                        <option value="14">&nbsp;&nbsp;&nbsp; San Juan</option>
                    </select>
                </div>
                <div class="field-container">
                    <input type="text" name="fechaI" class="date-field hasDatepicker" value="Fecha de Salida" id="dp1707566547949">
                </div>
                <div class="field-container">
                    <input type="text" name="fechaR" class="date-field reverse hasDatepicker" value="Fecha de Regreso" id="dp1707566547950">
                </div>
                <div class="range-slider">
                    <div class="range-min"><span>200</span> €</div>
                    <div class="range-max"><span>2000</span> €</div>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
                        <div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 0%; width: 100%;"></div>
                        <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a>
                        <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 100%;"></a>
                    </div>
                    <input type="hidden" name="precio_min" value="200" class="range-min">
                    <input type="hidden" name="precio_max" value="2000" class="range-max">
                    <input type="hidden" value="200" class="range-min-value">
                    <input type="hidden" value="2000" class="range-max-value">
                </div>
                <div class="form-button">
                    <div class="button-container">
                        <a href="#" class="button submit-button" title="Buscar">Buscar</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- /tour search form -->	
    </div>

    <div class="ninecol column subheader-block last">
        <div class="main-slider-container content-slider-container">
            <div class="content-slider main-slider fade-effect" id="slider" style="position:relative">
                
                <ul style="height: 370px;">

                    <li class="current" style="display: block; position: relative; z-index: 1;">
                        <div class="featured-image">
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="destinos/republica-dominicana/punta-cana">
                                <div class="etiqueta-categoria" id="etiqueta">
                                    Punta Cana desde 783,40€
                                </div>
                                <img width="824" height="370" src="img/paradisuspalmareal.jpg" class="attachment-large wp-post-image" alt="Punta Cana" title="Ver Todos Los destinos de Punta Cana">
                            </a>
                        </div>
                    </li>
                    
                    <li style="position: relative; z-index: 1; display: none;" class="">
                        <div class="featured-image">
                            <a hreflang="es" type="text/html" charset="iso-8859-1" href="destinos/mexico/rivera-maya">
                                <div class="etiqueta-categoria" id="etiqueta">
                                    Rivera Maya desde 783,60€
                                </div>
                                <img width="824" height="370" src="img/rivera-maya.jpg" class="attachment-large wp-post-image" alt="Rivera Maya" title="Ver Todos Los destinos de Rivera Maya">
                            </a>
                        </div>
                    </li>

                </ul>
                <div class="arrow arrow-left content-slider-arrow"></div>
                <div class="arrow arrow-right content-slider-arrow"></div>
                <input type="hidden" class="slider-pause" value="7008">
                <input type="hidden" class="slider-speed" value="400">
                <input type="hidden" class="slider-effect" value="fade">
                <div class="controls">
                    <a href="#" class="current"></a>
                    <a href="#" class=""></a>
                </div>

            </div>

            <div class="block-background layer-1"></div>
            <div class="block-background layer-2"></div>

        </div>
    </div>
</div>