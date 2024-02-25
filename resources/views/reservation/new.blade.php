@extends('main_template')

@section('title', 'Unos destinos de puta madre')

@section('site-content')

    <div class="row">
        <div class="section-title">
            <h1>Datos del Titular de la reserva</h1>
        </div>
        <div class="eightcol column">
            <div class="booking-form">
                <form method="POST" class="formatted-form" role="form" name="clientes" lang="es"
                    onsubmit="return validacion();" dir="ltr">
                    <div id="clientes">
                        <fieldset class="sixcol column" name="Titular">
                            <label for="nombre">Nombre</label>
                            <div class="field-container"><input id="nombre" type="text" width=""
                                    name="strNombre[]" value="" placeholder="Nombre" title="Introduzca su Nombre"
                                    maxlength="20" tabindex="1" required=""></div>
                            <label for="apelli2">Apellidos</label>
                            <div class="field-container"><input id="apelli2" type="text" name="strApellidos[]"
                                    value="" placeholder="Apellidos" title="Introduzca sus Apellidos" maxlength="30"
                                    tabindex="2" required=""></div>
                            <label for="dni">DNI o Pasaporte</label>
                            <div class="field-container"><input id="dni" type="text" name="strDNI[]" value=""
                                    placeholder="DNI o Pasaporte"
                                    title="Introduzca el DNI o Pasaporte sin guiones ni espacios." maxlength="10"
                                    tabindex="3" required=""></div>
                            <label for="telefono">Teléfono de contacto</label>
                            <div class="field-container"><input id="telefono" type="number" name="strTelefono[]"
                                    value="" placeholder="Teléfono"
                                    title="Introduzca su Número Telefónico sin guiones ni espacios" maxlength="15"
                                    tabindex="4" required=""></div>
                            <label for="email">Email</label>
                            <div class="field-container"><input type="email" name="strEmail[]" id="email"
                                    value="" placeholder="Email" title="Introduzca su Correo Eletrónico"
                                    maxlength="50" tabindex="5" required=""></div>
                        </fieldset>
                        <fieldset class="sixcol column" name="Direccion">
                            <label for="direccion">Dirección</label>
                            <div class="field-container"><input type="text" id="direccion" name="strDireccion[]"
                                    value="" placeholder="Dirección" title="Indique su Dirección" maxlength="30"
                                    tabindex="6" required=""></div>
                            <label for="ciudad">Ciudad o Pueblo</label>
                            <div class="field-container"><input type="text" id="ciudad" name="strCiudad[]"
                                    value="" placeholder="Ciudad o Pueblo" title="Introduzca su Ciudad" maxlength="30"
                                    tabindex="7" required=""></div>
                            <label for="provincia">Provincia</label>
                            <div class="field-container"><input type="text" id="provincia" name="strProvincia[]"
                                    value="" placeholder="Provincia" title="Introduzca su Provincia"
                                    maxlength="30" tabindex="8" required=""></div>
                            <label for="cp">C�digo Postal</label>
                            <div class="field-container"><input type="number" id="cp" name="intCodigoPostal[]"
                                    value="" placeholder="Código Postal"
                                    title="Introduzca el Código Postal sin guiones ni espacios" maxlength="6"
                                    tabindex="9" required=""></div>
                            <label for="notas">Notas</label>
                            <div class="field-container">
                                <textarea name="strNotas" id="notas" title="Agrega cualquier comentario que sea de utilidad..."
                                    placeholder="Agrega cualquier comentario que sea de utilidad..." maxlength="500" width="100%"
                                    spellcheck="true" tabindex="10"></textarea>
                            </div>
                            <input type="hidden" name="intDia[]" value="01">
                            <input type="hidden" name="strMes[]" value="01">
                            <input type="hidden" name="intAnios[]" value="1934">
                        </fieldset><br>
                        <fieldset class="sixcol  column">
                            <legend><input type="button" name="copyTitular"
                                    title="Copiar los datos del titular al pasajero 1" value="Copiar del Titular"
                                    onclick="javascript:copiar();"></legend>
                            <p>&nbsp;</p>
                        </fieldset>
                        <div class="clear"></div>
                        <fieldset class="sixcol  column" name="Pasajero 1">
                            <legend>
                                <h2>Datos del pasajero 1</h2>
                            </legend>
                            <hr>
                            <label for="nombrep">Nombre</label>
                            <div class="field-container"><input id="nombrep" type="text" width=""
                                    name="strNombre[]" value="" placeholder="Nombre" title="Introduzca su Nombre"
                                    maxlength="20" tabindex="12" required=""></div>
                            <label for="apelli2p">Apellidos</label>
                            <div class="field-container"><input id="apelli2p" type="text" name="strApellidos[]"
                                    value="" placeholder="Apellidos" title="Introduzca sus Apellidos"
                                    maxlength="30" tabindex="13" required=""></div>
                            <label for="dnip">DNI o Pasaporte</label>
                            <div class="field-container"><input id="dnip" type="text" name="strDNI[]"
                                    value="" placeholder="DNI o Pasaporte"
                                    title="Introduzca el DNI o Pasaporte sin guiones ni espacios." maxlength="10"
                                    tabindex="14" required=""></div>
                            <legend>Fecha de Nacimiento</legend>
                            <div class="threecol column">
                                <div class="field-container"><input type="number" name="intDia[]" value=""
                                        id="dia" placeholder="Día"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) strMes[].focus()"
                                        maxlength="2" max="31" min="1"
                                        title="Introduzca el Día de su nacimiento" tabindex="15" required=""></div>
                            </div>
                            <div class="sixcol column last">
                                <div class="field-container">
                                    <select name="strMes[]" id="mes" title="Seleccione el mes de su nacimiento"
                                        tabindex="16" required="">
                                        <option value="" selected="selected">Mes</option>
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="threecol column last">
                                <div class="field-container">
                                    <input type="number" id="anio" name="intAnios[]" value=""
                                        maxlength="4" max="2006" min="1934" placeholder="Año"
                                        title="Introduzca el año de su nacimiento" tabindex="17"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) strCiudad[].focus()"
                                        required="">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="sixcol  column" name="Pasajero 1">
                            <legend>
                                <h2>Datos del pasajero 2</h2>
                            </legend>
                            <hr>
                            <label for="nombrep2">Nombre</label>
                            <div class="field-container"><input id="nombrep2" type="text" width=""
                                    name="strNombre[]" value="" placeholder="Nombre" title="Introduzca su Nombre"
                                    maxlength="20" tabindex="18" required=""></div>
                            <label for="apelli2p2">Apellidos</label>
                            <div class="field-container"><input id="apelli2p2" type="text" name="strApellidos[]"
                                    value="" placeholder="Apellidos" title="Introduzca sus Apellidos"
                                    maxlength="30" tabindex="19" required=""></div>
                            <label for="dnip2">DNI o Pasaporte</label>
                            <div class="field-container"><input id="dnip2" type="text" name="strDNI[]"
                                    value="" placeholder="DNI o Pasaporte"
                                    title="Introduzca el DNI o Pasaporte sin guiones ni espacios." maxlength="10"
                                    tabindex="20" required=""></div>
                            <legend>Fecha de Nacimiento</legend>
                            <div class="threecol column">
                                <div class="field-container"><input type="number" name="intDia[]" value=""
                                        id="dia" placeholder="Día"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) strMes[].focus()"
                                        maxlength="2" max="31" min="1"
                                        title="Introduzca el Día de su nacimiento" tabindex="21" required=""></div>
                            </div>
                            <div class="sixcol column last">
                                <div class="field-container">
                                    <select name="strMes[]" id="mes" title="Seleccione el mes de su nacimiento"
                                        tabindex="22" required="">
                                        <option value="" selected="selected">Mes</option>
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="threecol column last">
                                <div class="field-container">
                                    <input type="number" id="anio" name="intAnios[]" value=""
                                        maxlength="4" max="2024" min="1934" placeholder="Año"
                                        title="Introduzca el año de su nacimiento" tabindex="23"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) strCiudad[].focus()"
                                        required="">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="clear"></div>
                    <hr>
                    <!--<input type="button" name="addPasajero" class="button dark" id="add_pasajero" onClick="javascript:add_pasajero();" value="Agregar Otro Pasajero" title="Agregue otro pasajero haciendo clic aqu&iacute;" /><br /><br />-->
                    <p>&nbsp;</p>
                    <div class="field-container"><input type="checkbox" name="chx_termsAndConditions"
                            id="chx_termsAndConditions" tabindex="24" required=""> <label
                            for="chx_termsAndConditions" title="Aceptar los terminos y condiciones">He leído y acepto las
                            <a href="legal/condiciones-de-uso" title="Ver condiciones generales"
                                target="_blank">Condiciones generales</a> de venta y la <a
                                href="legal/politica-de-privacidad" title="Ver Política de Seguridad y Privacidad"
                                target="_blank">Política de Seguridad y Privacidad</a></label></div>
                    <input type="hidden" name="MM_insert" value="clientes">
                    <input type="hidden" name="idproducto" value="5">
                    <input type="hidden" name="idFecha" value="140">
                    <input type="hidden" name="dblPrecio" value="1204.5">
                    <input type="hidden" name="dblTasas" value="150">
                    <input type="submit" name="submit" value="Confirmar Datos y Elegir Forma de Pago"
                        title="Confirmar Datos y Elegir Forma de Pago" tabindex="25" onclick="">
                </form>
            </div>
        </div>
        <div class="fourcol column last">
            <div class="featured-blog">
                <article class="post">
                    <div class="featured-image">
                        <img width="550" height="413" src="{{ asset( 'images/' . $product->mainImage()->path )}}"
                            class="attachment-extended wp-post-image" alt="Imagen de {{ $product->name }}" title="{{ $product->name }}">
                    </div>
                    <div class="post-content">
                        <h2 class="post-title">{{ $product->name }}</h2>
                        <ul class="tour-meta" style="padding-left: 1em; text-indent: -1em;">
                            <li>
                                <div class="colored-icon icon-2"></div> <strong>Destino:</strong> {{ $product->category->name }}
                            </li>
                            <li>
                                <div class="colored-icon icon-1"><span></span></div> <strong>Duración:</strong> 8 Días
                            </li>
                            <li>
                                <div class="colored-icon icon-6"><span></span></div> <strong>Salida:</strong> Lunes 08 de
                                Agosto del 2016 a las 14:00Hr
                            </li>
                            <li>
                                <div class="colored-icon icon-7"><span></span></div> <strong>Regreso:</strong> Lunes 15 de
                                Agosto del 2016 a las 19:15Hr
                            </li>
                            <li style="font-size:1.8em;">
                                <div class="colored-icon icon-3"><span></span></div><strong>Precio por persona:</strong>
                                1.354,50€
                            </li>
                        </ul>
                        <p>&nbsp;</p>
                    </div>
                    <div class="post-content">
                        <h3>Itinerario</h3>
                        <p>{{ $product->meta->meta_data['itinerario'] }}</p>

                        <p><strong>NOTA:</strong> Los horarios definitivos de los Vuelos se reconfirmar�n tras la
                            realizaci�n de la reserva.</p>
                    </div>
                </article>
            </div>
        </div>
    </div>

@endsection
