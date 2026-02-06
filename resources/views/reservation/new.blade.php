@extends('main_template')

@section('title', 'Unos destinos de puta madre')

@section('site-content')

    <div class="row">
        <div class="section-title">
            <h1>Datos del Titular de la reserva</h1>
            {{ $errors }}
        </div>
        <div class="eightcol column">
            <div class="booking-form">
                <form action="{{ route('reservation.create', $product->slug) }}" method="POST" class="formatted-form"
                    role="form" name="clientes" lang="es" dir="ltr">
                    @csrf
                    <div id="clientes">
                        <fieldset class="sixcol column" name="Titular">
                            <label for="nombre">Nombre</label>
                            <div class="field-container">
                                <input id="nombre" type="text" name="nombreT" value="{{old('nombreT')}}"
                                    placeholder="Nombre" title="Introduzca su Nombre" maxlength="20" tabindex="1"
                                    >
                                @error('nombreT')
                                    {{ $message }}
                                @enderror
                            </div>
                            <label for="apelli2">Apellidos</label>
                            <div class="field-container"><input id="apelli2" type="text" name="apellidosT"
                                    value="{{old('apellidosT')}}" placeholder="Apellidos" title="Introduzca sus Apellidos" maxlength="30"
                                    tabindex="2" >
                                @error('apellidosT')
                                    {{ $message }}
                                @enderror
                            </div>
                            <label for="dni">DNI o Pasaporte</label>
                            <div class="field-container"><input id="dni" type="text" name="dniT" value="{{old('dniT')}}"
                                    placeholder="DNI o Pasaporte"
                                    title="Introduzca el DNI o Pasaporte sin guiones ni espacios." maxlength="10"
                                    tabindex="3" >
                                @error('dniT')
                                    {{ $message }}
                                @enderror
                            </div>
                            <label for="telefono">Teléfono de contacto</label>
                            <div class="field-container"><input id="telefono" type="number" name="telefono" value="{{old('telefono')}}"
                                    placeholder="Teléfono" title="Introduzca su Número Telefónico sin guiones ni espacios"
                                    maxlength="15" tabindex="4" >
                                @error('telefono')
                                    {{ $message }}
                                @enderror
                            </div>
                            <label for="email">Email</label>
                            <div class="field-container"><input type="email" name="mail" id="email" value="{{old('mail')}}"
                                    placeholder="Email" title="Introduzca su Correo Eletrónico" maxlength="50"
                                    tabindex="5" >
                                @error('mail')
                                    {{ $message }}
                                @enderror
                            </div>
                        </fieldset>
                        <fieldset class="sixcol column" name="Direccion">
                            <label for="ciudad">Ciudad o Pueblo</label>
                            <div class="field-container"><input type="text" id="ciudad" name="ciudad" value="{{old('ciudad')}}"
                                    placeholder="Ciudad o Pueblo" title="Introduzca su Ciudad" maxlength="30" tabindex="7"
                                    ></div>
                            <label for="provincia">Provincia</label>
                            <div class="field-container"><input type="text" id="provincia" name="provincia"
                                    value="{{old('provincia')}}" placeholder="Provincia" title="Introduzca su Provincia" maxlength="30"
                                    tabindex="8" ></div>
                            <label for="cp">C�digo Postal</label>
                            <div class="field-container"><input type="number" id="cp" name="codigoPostal"
                                    value="{{old('codigoPostal')}}" placeholder="Código Postal"
                                    title="Introduzca el Código Postal sin guiones ni espacios" maxlength="6"
                                    tabindex="9" ></div>
                            <label for="notas">Notas</label>
                            <div class="field-container">
                                <textarea name="notas" id="notas" title="Agrega cualquier comentario que sea de utilidad..."
                                    placeholder="Agrega cualquier comentario que sea de utilidad..." maxlength="500" width="100%"
                                    spellcheck="true" tabindex="10">{{old('notas')}}</textarea>
                            </div>
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
                            <div class="field-container"><input id="nombrep" type="text" name="nombreP[]"
                                    value="" placeholder="Nombre" title="Introduzca su Nombre" maxlength="20"
                                    tabindex="12" ></div>
                            <label for="apelli2p">Apellidos</label>
                            <div class="field-container"><input id="apelli2p" type="text" name="apellidosP[]"
                                    value="" placeholder="Apellidos" title="Introduzca sus Apellidos"
                                    maxlength="30" tabindex="13" ></div>
                            <label for="dnip">DNI o Pasaporte</label>
                            <div class="field-container"><input id="dnip" type="text" name="dniP[]"
                                    value="" placeholder="DNI o Pasaporte"
                                    title="Introduzca el DNI o Pasaporte sin guiones ni espacios." maxlength="10"
                                    tabindex="14" ></div>
                            <legend>Fecha de Nacimiento</legend>
                            <div class="threecol column">
                                <div class="field-container"><input type="number" name="diaP[]" value=""
                                        id="dia" placeholder="Día"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) mesP[].focus()"
                                        maxlength="2" max="31" min="1"
                                        title="Introduzca el Día de su nacimiento" tabindex="15" ></div>
                            </div>
                            <div class="sixcol column last">
                                <div class="field-container">
                                    <select name="mesP[]" id="mes" title="Seleccione el mes de su nacimiento"
                                        tabindex="16" >
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
                                    <input type="number" id="anio" name="anioP[]" value="" maxlength="4"
                                        max="2006" min="1934" placeholder="Año"
                                        title="Introduzca el año de su nacimiento" tabindex="17"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) strCiudad[].focus()"
                                        >
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="sixcol  column" name="Pasajero 1">
                            <legend>
                                <h2>Datos del pasajero 2</h2>
                            </legend>
                            <hr>
                            <label for="nombrep2">Nombre</label>
                            <div class="field-container"><input id="nombrep2" type="text" name="nombreP[]"
                                    value="" placeholder="Nombre" title="Introduzca su Nombre" maxlength="20"
                                    tabindex="18" ></div>
                            <label for="apelli2p2">Apellidos</label>
                            <div class="field-container"><input id="apelli2p2" type="text" name="apellidosP[]"
                                    value="" placeholder="Apellidos" title="Introduzca sus Apellidos"
                                    maxlength="30" tabindex="19" ></div>
                            <label for="dnip2">DNI o Pasaporte</label>
                            <div class="field-container"><input id="dnip2" type="text" name="dniP[]"
                                    value="" placeholder="DNI o Pasaporte"
                                    title="Introduzca el DNI o Pasaporte sin guiones ni espacios." maxlength="10"
                                    tabindex="20" ></div>
                            <legend>Fecha de Nacimiento</legend>
                            <div class="threecol column">
                                <div class="field-container"><input type="number" name="diaP[]" value=""
                                        id="dia" placeholder="Día"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) mesP[].focus()"
                                        maxlength="2" max="31" min="1"
                                        title="Introduzca el Día de su nacimiento" tabindex="21" ></div>
                            </div>
                            <div class="sixcol column last">
                                <div class="field-container">
                                    <select name="mesP[]" id="mes" title="Seleccione el mes de su nacimiento"
                                        tabindex="22" >
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
                                    <input type="number" id="anio" name="anioP[]" value="" maxlength="4"
                                        max="2024" min="1934" placeholder="Año"
                                        title="Introduzca el año de su nacimiento" tabindex="23"
                                        onkeyup="if (this.value.length == this.getAttribute('maxlength')) strCiudad[].focus()"
                                        >
                                </div>
                            </div>
                        </fieldset>
                        <p>&nbsp;</p>
                        <div class="clear"></div>
                        <fieldset class="sixcol  column" name="Forma de Pago">
                            <legend>
                                <h2>Forma de Pago</h2>
                            </legend>
                            <div class="field-container">
                                <label title="Pagar con tarjeta"><input name="formaPago" type="radio" value="1" checked ><strong> Tarjeta Crédito / Debito</strong></label>
                            </div>
                            <div class="field-container">
                                <label title="Pagar con Paypal"><input name="formaPago" type="radio" value="2"><strong> PayPal</strong></label>
                            </div>
                            <div class="field-container">
                                <label title="Pagar con Vizum"><input name="formaPago" type="radio" value="3"><strong> Vizum</strong></label>
                            </div>
                        </fieldset>
                    </div>
                    
                    <div class="clear"></div>
                    <hr>
                    <!--<input type="button" name="addPasajero" class="button dark" id="add_pasajero" onClick="javascript:add_pasajero();" value="Agregar Otro Pasajero" title="Agregue otro pasajero haciendo clic aqu&iacute;" /><br /><br />-->
                    <p>&nbsp;</p>
                    <div class="field-container"><input type="checkbox" name="chx_termsAndConditions"
                        id="chx_termsAndConditions" tabindex="24" > 
                        <label for="chx_termsAndConditions" title="Aceptar los terminos y condiciones">He leído y acepto las
                            <a href="legal/condiciones-de-uso" title="Ver condiciones generales"
                                target="_blank">Condiciones generales</a> de venta y la <a
                                href="legal/politica-de-privacidad" title="Ver Política de Seguridad y Privacidad"
                                target="_blank">Política de Seguridad y Privacidad</a>
                        </label>
                    </div>
                    <input type="hidden" name="idD" value="{{ $itinerary->id }}">
                    <input type="hidden" name="totalAmount" value="{{ $itinerary->price + $itinerary->taxes }}">
                    <input type="hidden" name="dblTasas" value="{{ $itinerary->taxes }}">
                    <input type="submit" name="submit" value="Confirmar Datos y Pagar"
                        title="Confirmar Datos y Pagar" tabindex="25">
                </form>
            </div>
        </div>
        <div class="fourcol column last">
            <div class="featured-blog">
                <article class="post">
                    <div class="featured-image">
                        <img width="550" height="413" src="{{ asset('images/' . $product->mainImage?->path) }}"
                            class="attachment-extended wp-post-image" alt="Imagen de {{ $product->name }}"
                            title="{{ $product->name }}">
                    </div>
                    <div class="post-content">
                        <h2 class="post-title">{{ $product->name }}</h2>
                        <ul class="tour-meta" style="padding-left: 1em; text-indent: -1em;">
                            <li>
                                <div class="colored-icon icon-2"></div> <strong>Destino:</strong>
                                {{ $product->category->name }}
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
                                {{ $itinerary->price + $itinerary->taxes }} &euro;
                            </li>
                        </ul>
                        <p>&nbsp;</p>
                    </div>
                    <div class="post-content">
                        <h3>Itinerario</h3>
                        <p>{{ $product->meta?->meta_data['itinerario'] }}</p>

                        <p><strong>NOTA:</strong> Los horarios definitivos de los Vuelos se reconfirmar�n tras la
                            realizaci�n de la reserva.</p>
                    </div>
                </article>
            </div>
        </div>
    </div>

@endsection
