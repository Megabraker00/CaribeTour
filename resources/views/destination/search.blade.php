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
                <option value="">Todos los Destinos</option>

                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
                
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