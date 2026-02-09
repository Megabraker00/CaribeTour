<!-- buscador -->
<div class="col-md-12 col-lg-4">

    <div class="card mb-4 tour-search-form ">

        <div class="card-header form-title">
            <h5 class="text-center">Encuentra tu Destino</h5>
        </div>

        <form action="provincia.html">
            <div class="card-body">

                <div class="mb-3">
                    <label for="origen" class="form-label">Origen</label>
                    <select name="origen" id="origen" class="form-select">
                        <option value="madrid" selected>Madrid</option>
                        <option value="asturias">Asturias</option>
                        <option value="barcelona">Barcelona</option>
                        <option value="bilbao">Bilbao</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="destino" class="form-label">Destino</label>
                    <select name="destino" id="destino" class="form-select">
                        <option>-</option>
                        <option value="all">Todos los Destinos</option>
                        <optgroup label="Rep. Dominicana">
                            <option>Punta Cana</option>
                            <option>Bávaro</option>
                            <option>La Romana</option>
                            <option>Santo Domingo</option>
                        </optgroup>
                        <optgroup label="Cuba">
                            <option>La Habana</option>
                            <option>Varadero</option>
                            <option>Santiago</option>
                        </optgroup>
                        <optgroup label="M&eacute;xico">
                            <option>Canc&uacute;n</option>
                            <option>Acapulco</option>
                            <option>Rivera Maya</option>
                        </optgroup>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha-salida" class="form-label">Fecha de Salida</label>
                    <input name="f-salida" type="date" class="form-control" id="fecha-salida"
                        placeholder="Fecha de Salida" />
                </div>

                <!-- <div class="mb-3">
                    <label for="fecha-regreso" class="form-label">Fecha de Refreso</label>
                    <input type="date" class="form-control" id="fecha-regreso"
                        placeholder="Fecha de Regreso" />
                </div> -->

                <!-- <div class="row">
                    <div class="col">
                        <label for="adulto" class="form-label">Adultos</label>
                        <input type="number" class="form-control" id="adulto" value="2" min="1" max="10">
                    </div>

                    <div class="col">
                        <label for="ninio" class="form-label">Niños</label>
                        <input type="number" class="form-control" id="ninio" value="0" min="0" max="4">
                    </div>

                    <div class="col">
                        <label for="bebe" class="form-label">Bebés</label>
                        <input type="number" class="form-control" id="bebe" value="0" min="0" max="2">
                    </div>
                </div> -->

            </div>
            <div class="card-footer text-center">
                <div class="col-12">
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>
<!-- /buscador -->