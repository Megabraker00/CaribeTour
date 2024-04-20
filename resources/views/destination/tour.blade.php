@extends('main_template')

@section('title', 'Unos destinos de puta madre')

@section('site-content')

    <div class="row">
        <div class="full-tour clearfix">
            <div class="sixcol column">
                <div class="content-slider-container tour-slider-container">
                    <div class="content-slider tour-slider fade-effect">
                        <ul style="height: 379px;">
                            <li class="" style="display: none; position: relative; z-index: 1;"><img
                                    src="{{ asset( 'images/' . $tour->mainImage()->path )}}"
                                    alt="Imagen de {{ $tour->name }}"
                                    title="Natura Park Beach Eco Resort &amp; Spa"></li>
                            <li style="position: relative; z-index: 1; display: none;" class=""><img
                                    src="img/natura-park-beach-eco-resort-y-spa_14694525191.jpg"
                                    alt="Imagen de Natura Park Beach Eco Resort &amp; Spa"
                                    title="Natura Park Beach Eco Resort &amp; Spa"></li>
                            <li style="position: relative; z-index: 1; display: none;" class=""><img
                                    src="img/natura-park-beach-eco-resort-y-spa_14694525192.jpg"
                                    alt="Imagen de Natura Park Beach Eco Resort &amp; Spa"
                                    title="Natura Park Beach Eco Resort &amp; Spa"></li>
                                    <!-- MAIN IMAGEN -->
                            <li style="position: relative; z-index: 1; display: list-item;" class="current"><img
                                    src="{{ asset( 'images/' . $tour->mainImage()->path )}}"
                                    alt="Imagen de {{ $tour->name }}"
                                    title="{{ $tour->name }}"></li>
                                    <!-- \MAIN IMAGEN -->
                        </ul>
                        <div class="arrow arrow-left content-slider-arrow"></div>
                        <div class="arrow arrow-right content-slider-arrow"></div>
                        <input type="hidden" class="slider-speed" value="400">
                        <input type="hidden" class="slider-pause" value="6000">
                        <div class="controls">
                            <a href="#" class=""></a><a href="#" class=""></a>
                            <a href="#" class=""></a><a href="#" class="current"></a>
                        </div>
                    </div>
                    <div class="block-background layer-1"></div>
                    <div class="block-background layer-2"></div>
                </div>
            </div>
            <div class="sixcol column last">
                <div class="section-title">
                    <h1>{{ $tour->name }}</h1>
                </div>
                <ul class="tour-meta">
                    <li>
                        <div class="colored-icon icon-2"></div>
                        <strong>Destino:</strong> <a hreflang="es" type="text/html" charset="iso-8859-1"
                            href="{{ route('destinos') }}/{{ $tour->category->fullSlug() }}" rel="tag"
                            title="Ver destinos en {{ $tour->category->name }}">{{ $tour->category->name }}</a>
                    </li>
                    <li>
                        <div class="colored-icon icon-1"><span></span></div>
                        <strong>Duracion:</strong> 8 Días
                    </li>
                    <li>
                        <div class="colored-icon icon-6"><span></span></div>
                        <strong>Salida:</strong> Martes 02 de Agosto del 2016
                    </li>
                    <li>
                        <div class="colored-icon icon-7"><span></span></div>
                        <strong>Regreso:</strong> Martes 09 de Agosto del 2016
                    </li>
                    <li style="font-size:1.8em;">
                        <div class="colored-icon icon-3"><span></span></div>
                        <strong>Precio:</strong> {{ $firstDate->price + $firstDate->taxes }} &euro;
                    </li>
                </ul>
                <p>En este resort se aprovechan los recursos naturales del área tales como las piedras, el coco, la madera y
                    la caña, creando un ambiente de extraordinaria belleza natural que parece estar soñando...<br>
                    <br>
                    viva la experiencia de unas vacaciones en completa armonía con la naturaleza en el lujoso resort de
                    punta cana, natura park. abundantes jardines tropicales que rebosan con una exótica vida de aves se
                    mezclan con lagunas y puentes a lo largo de caminos serpenteantes que llevan a la playa. el hotel está
                    ubicado en la maravillosa playa de , uno de los mejores en el caribe.<br>
                    <br>
                    el natura park beach eco resort se beneficia de un diseño arquitectónico original que utiliza los
                    recursos naturales de la zona como la piedra, los cocoteros, la madera y la caña para crear un ambiente
                    tranquilo y confortable. el natura park es un lugar idílico para unas vacaciones relajantes en el
                    caribe. nuestra excelente ubicación del punta cana resort ofrece una completa selección de actividades y
                    de servicios especiales.
                </p>
                <footer class="tour-footer">
                    <a hreflang="es" type="text/html" charset="iso-8859-1"
                        href="{{ route('reservation.create', ['producto' => $tour->slug, 'idF' => $firstDate->id]) }}"
                        title="Solicitar la reserva de {{ $tour->name }}"
                        class="button small">
                        <span>Reservar Ahora</span>
                    </a>
                    <a href="#question-form"
                        data-id="{{ $tour->slug }}" data-title="{{ $tour->name }}"
                        class="button grey small colorbox inline cboxElement">
                        <span>Consultar</span>
                    </a> 
                </footer>
            </div>
        </div>

        <!-- question form -->
        <div class="hidden">
            <div class="question-form popup-form" id="question-form">
                <div class="section-title popup-title">
                    <h4></h4>
                </div>
                <form action="mail.php" method="POST" class="formatted-form" name="clientes"
                    onsubmit="return validacion();">
                    <div class="sixcol column ">
                        <div class="field-container">
                            <input type="text" id="nombre" name="nombre" title="Introduzca su nombtre completo."
                                maxlength="50" value="" placeholder="Nombre Completo" required="">
                        </div>
                    </div>
                    <div class="sixcol column last">
                        <div class="clear"></div>
                        <div class="field-container">
                            <input type="email" id="email" name="email" title="Introduzca su email." maxlength="80"
                                value="" placeholder="Email" required="">
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="field-container">
                        <textarea id="mensaje" name="mensaje" title="Indique su consulta." maxlength="500" placeholder="Consultas"
                            required=""></textarea>
                    </div>
                    <input type="hidden" name="producto" id="producto" value="Natura Park Beach Eco Resort &amp; Spa"
                        class="popup-id">
                    <input type="hidden" name="volver"
                        value="http://localhost/caribetour/destinos/republica-dominicana/punta-cana/natura-park-beach-eco-resort-y-spa">
                    <input type="submit" value="Enviar" title="Enviar la consulta">
                    <!--<a class="submit-button button" href="#">Enviar</a> -->
                </form>
            </div>
            <!-- /question form -->

        </div>
        <div class="sixcol column">
            <div class="tour-itinerary">
                <div class="tour-day">
                    <div class="tour-day-number">
                        <h5>Itinerario</h5>
                    </div>
                    <div class="tour-day-text clearfix">
                        <div class="bubble-corner"></div>
                        <div class="bubble-text">
                            <div class="column twelvecol last">
                                <h5>{{ $tour->name }}</h5>
                                <p>{{ $tour->meta?->meta_data['itinerario'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tour-day">
                    <div class="tour-day-number">
                        <h5>Incluye</h5>
                    </div>
                    <div class="tour-day-text clearfix">
                        <div class="bubble-corner"></div>
                        <div class="bubble-text">
                            <div class="column twelvecol last">
                                <h5>¿Qué incluye {{ $tour->name }}?</h5>
                                <p>-Vuelo directo con la compañía Evelop desde Madrid con destino Punta Cana.<br>
                                    -Estancia en habitación Standard, 7 noches en régimen de todo incluído en el Hotel
                                    Natura Park Eco - Resort &amp; Spa 5* - Punta Cana.<br>
                                    -Traslados de entrada y salida Punta Cana.<br>
                                    -Tasas aéreas incluidas.<br>
                                    -Seguro obligatorio de Viaje.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sixcol column last">
            <div class="calendario">
                <table summary="Calendario con las fechas de salida para el tour Natura Park Beach Eco Resort &amp; Spa">
                    <caption>
                        <h3>Elíge la fecha de salida</h3>
                    </caption>
                    <colgroup span="7" style="width:14.29%; text-align:left; vertical-align:top; height:39px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>
                                <form role="form"
                                    action="/caribetour/destinos/republica-dominicana/punta-cana/natura-park-beach-eco-resort-y-spa"
                                    method="post"><input type="submit" value="< <--"
                                        title="Ver el mes anterior"><input type="hidden" name="mes"
                                        value="1"><input type="hidden" name="anio" value="2024"></form>
                            </th>
                            <th colspan="5">S�bado 10 de Febrero del 2024</th>
                            <th>
                                <form role="form"
                                    action="/caribetour/destinos/republica-dominicana/punta-cana/natura-park-beach-eco-resort-y-spa"
                                    method="post"><input type="submit" value="--> >"
                                        title="Ver el mes anterior"><input type="hidden" name="mes"
                                        value="3"><input type="hidden" name="anio" value="2024"></form>
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" abbr="Domingo">Dom</th>
                            <th scope="col" abbr="Lunes">Lun</th>
                            <th scope="col" abbr="Martes">Mar</th>
                            <th scope="col" abbr="Miércoles">Mie</th>
                            <th scope="col" abbr="Jueves">Jue</th>
                            <th scope="col" abbr="Viernes">Vie</th>
                            <th scope="col" abbr="Sábado">Sab</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>1 </td>
                            <td>2 </td>
                            <td>3 </td>
                        </tr>
                        <tr>
                            <td>4 </td>
                            <td>5 </td>
                            <td>6 </td>
                            <td>7 </td>
                            <td>8 </td>
                            <td>9 </td>
                            <td style="background-color:#E2E2E2">10 </td>
                        </tr>
                        <tr>
                            <td>11 </td>
                            <td>12 </td>
                            <td>13 </td>
                            <td>14 </td>
                            <td>15 </td>
                            <td>16 </td>
                            <td>17 </td>
                        </tr>
                        <tr>
                            <td>18 </td>
                            <td>19 </td>
                            <td>20 </td>
                            <td>21 </td>
                            <td>22 </td>
                            <td>23 </td>
                            <td>24 </td>
                        </tr>
                        <tr>
                            <td>25 </td>
                            <td>26 </td>
                            <td>27 </td>
                            <td>28 </td>
                            <td>29 </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clear"></div>

        <div class="related-tours clearfix">
            <div class="section-title">
                <h2>Destinos Relacionados</h2>
            </div>

            <div class="items-grid">

                @foreach($tour->related() as $index => $tourRelated)

                <div class="column threecol @if(($index + 1) % 4 == 0) last @endif">
                    <div class="tour-thumb-container">
                        <div class="tour-thumb">
                            <a hreflang="es" type="text/html" charset="iso-8859-1"
                                href="{{ route('destinos') }}/{{ $tourRelated->tourSlug() }}"
                                title="{{ $tourRelated->name }}">
                                <img width="440" height="330"
                                    src="{{ asset( 'images/' . $tourRelated->mainImage()->path )}}"
                                    class="attachment-preview wp-post-image"
                                    alt="{{ $tourRelated->name }}">
                            </a>
                            <div class="tour-caption">
                                <h5 class="tour-title">
                                    <a hreflang="es" type="text/html" charset="iso-8859-1"
                                        href="{{ route('destinos') }}/{{ $tourRelated->tourSlug() }}">
                                        {{ $tourRelated->name }}
                                    </a>
                                </h5>
                                <div class="tour-meta">
                                    <div class="tour-destination">
                                        <div class="colored-icon icon-2"></div>
                                        <a hreflang="es" type="text/html" charset="iso-8859-1"
                                            href="{{ route('destinos') }}/{{ $tourRelated->category->fullSlug() }}" rel="tag">
                                            {{ $tourRelated->category->name }}
                                        </a>
                                    </div>
                                    <div class="colored-icon icon-3"></div>1.126,40€
                                </div>
                            </div>
                        </div>
                        <div class="block-background"></div>
                    </div>
                </div>

                @endforeach

                <div class="clear"></div>

            </div>
        </div>
        <!-- related tours -->
    </div>

@endsection
