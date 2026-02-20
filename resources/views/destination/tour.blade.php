@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('content')
    <!-- container -->

    <section class="container py-4">

        <!-- carousel and description -->
        <div class="row">
            <!-- carousel -->
            <div class="col-md-12 col-lg-6 col-xl-6 mb-4">

                <div class="card shadow">

                    <div class="card-body">

                        <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                                    aria-label="Slide 4"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/i-love-bootstrap3.png') }}"
                                        class="d-block w-100" alt="..." width="662px">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>First slide label</h5>
                                        <p>Some representative placeholder content for the first slide.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/i-love-bootstrap2.png') }}"
                                        class="d-block w-100" alt="..." width="662px">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Second slide label</h5>
                                        <p>Some representative placeholder content for the second slide.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/i-love-bootstrap1.png') }}"
                                        class="d-block w-100" alt="..." width="662px">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Third slide label</h5>
                                        <p>Some representative placeholder content for the third slide.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/i-love-bootstrap4.png') }}"
                                        class="d-block w-100" alt="..." width="662px">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Fourth slide label</h5>
                                        <p>Some representative placeholder content for the second slide.</p>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                    </div>

                </div>

            </div>
            <!-- /carousel -->

            <!-- description -->
            <div class="col-md-12 col-lg-6 col-xl-6 mb-4">

                <h2>Hotel Carolina</h2>
                <hr>

                <ul class="tour-info">
                    <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                    <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> Punta Cana</li>
                    <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> Sábado 15 de Septiempre 2024</li>
                    <li><i class="bi bi-arrow-down-left-square-fill"></i><strong>Regreso:</strong> Domingo 22 de Septiembre 2024</li>
                    <li><i class="bi bi-calendar-week-fill"></i><strong>Duración:</strong> 8 Días - 7 Noches</li>
                    <li title="Precio por persona"><span class="fs-4"><i class="bi bi-tag-fill"></i><strong>Precio por Persona:</strong> 507.65€</span></li>
                </ul>
                
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur aliquid officiis corrupti, sed laborum nesciunt possimus delectus. Officia sequi iste animi quidem nulla nobis explicabo impedit dolor iusto? Corrupti, inventore.</p>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Modi nisi minima sapiente soluta cum a aspernatur repudiandae quos dolorum obcaecati inventore, illo quis. Molestias voluptatibus harum eveniet illum necessitatibus magnam.</p>

                <div>
                    <button class="btn btn-primary me-2">Reservar Fechas</button>
                    <button class="btn btn-secondary" title="Realizar una consulta sobre este tour">Consultar</button>
                </div>
            </div>
            <!-- /description -->

        </div>
        <!-- /carousel and description -->

        <!-- itinerary and calendar -->
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-6">
                <h5><i class="bi bi-card-list"></i> Itinerario</h5>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex quo reiciendis eius sunt quia debitis accusamus fugiat est veritatis fugit ducimus voluptatem, quas dolorum temporibus tempore cupiditate nesciunt nobis quibusdam.
                Nemo sit cupiditate eaque eveniet alias esse, aperiam vitae voluptatem! Consequatur, in? Porro laborum dolor similique vitae officia cupiditate obcaecati repudiandae, distinctio rem ratione, sunt, dolores et maxime culpa vero?</p>

                <h5><i class="bi bi-card-checklist"></i> Incluye</h5>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Itaque ut neque placeat impedit, aliquid perferendis at ipsum minima quidem ea eaque id vero voluptates aperiam. Architecto delectus maxime perspiciatis obcaecati.</p>

            </div>

            <!-- calendar -->
            <div class="col-md-12 col-lg-6 col-xl-6">
                
                <table class="table text-center">
                    <caption class="text-center">Fechas disponibles</caption>

                    <thead>
                        <tr>
                            <th>
                                <button id="prevMonth" class="btn btn-primary" title="Mes anterior">
                                <i class="bi bi-arrow-left"></i>
                                </button>
                            </th>

                            <th colspan="5" class="text-center">
                                <h4 id="monthTitle" class="mb-0">Septiembre 2024</h4>
                            </th>

                            <th>
                                <button id="nextMonth" class="btn btn-primary" title="Mes siguiente">
                                <i class="bi bi-arrow-right"></i>
                                </button>
                            </th>
                        </tr>

                        <tr>
                            <th scope="col">Lun</th>
                            <th scope="col">Mar</th>
                            <th scope="col">Mie</th>
                            <th scope="col">Jue</th>
                            <th scope="col">Vie</th>
                            <th scope="col">Sab</th>
                            <th scope="col">Dom</th>
                        </tr>
                    </thead>

                    <tbody id="calendarBody" >
                        <!-- JS pintará aquí -->
                    </tbody>
                </table>
            </div>
            <!-- /calendar -->
        </div>
        <!-- /itinerary and calendar -->

        <!-- related products -->
        <div class="row">
            <h4>Productos Relacionados</h4>
            <hr>

            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="tour.html" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap3.png') }}" class="card-img"  width="482px">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title">Vista Sol Punta Cana Beach Resort & Spa <span class="star-4 fs-6"></span></h5>
                            <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="tour.html" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap2.png') }}" class="card-img" width="482px">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title">Vista Sol Punta Cana Beach Resort & Spa <span class="star-4 fs-6"></span></h5>
                            <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="tour.html" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap4.png') }}" class="card-img" width="482px">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title">Vista Sol Punta Cana Beach Resort & Spa <span class="star-4 fs-6"></span></h5>
                            <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="tour.html" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap3.png') }}" class="card-img" width="482px">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title">Catalonia Bávaro Beach, Golf & Casino Lujo <span class="star-5 fs-6"></span></h5>
                            <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        <!-- /related products -->        

    </section>

    <!-- /container -->
@endsection

@section('custom-js')
<script>


document.addEventListener('DOMContentLoaded', () => {

  const monthTitle = document.getElementById('monthTitle');
  const calendarBody = document.getElementById('calendarBody');
  const prevBtn = document.getElementById('prevMonth');
  const nextBtn = document.getElementById('nextMonth');

  const today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();

  let viewMonth = currentMonth;
  let viewYear = currentYear;

  const monthNames = [
    'Enero','Febrero','Marzo','Abril','Mayo','Junio',
    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
  ];


  function getAdjacentMonth(month, year, direction) {
    let newMonth = month + direction;
    let newYear = year;

    if (newMonth < 0) {
        newMonth = 11;
        newYear--;
    }

    if (newMonth > 11) {
        newMonth = 0;
        newYear++;
    }

    return { month: newMonth, year: newYear };
  }

    function renderCalendar() {

  monthTitle.textContent = `${monthNames[viewMonth]} ${viewYear}`;
  calendarBody.innerHTML = '';

  const firstDay = new Date(viewYear, viewMonth, 1);
  let startDay = firstDay.getDay();
  startDay = (startDay === 0) ? 6 : startDay - 1;

  const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

  let day = 1;

  // 🔥 SIEMPRE 6 FILAS
  for (let week = 0; week < 6; week++) {
    let row = '<tr>';

    for (let i = 0; i < 7; i++) {

      if (week === 0 && i < startDay) {
        row += '<td class="text-muted"></td>';
      } 
      else if (day > daysInMonth) {
        row += '<td class="text-muted"></td>';
      } 
      else {

        const isToday =
          day === today.getDate() &&
          viewMonth === today.getMonth() &&
          viewYear === today.getFullYear();

        row += `<td class="${isToday ? 'table-primary fw-bold' : ''}">
                  ${day}
                </td>`;
        day++;
      }
    }

    row += '</tr>';
    calendarBody.innerHTML += row;
  }

    // bloquear mes anterior si estamos en el actual
    prevBtn.disabled =
        viewMonth === currentMonth &&
        viewYear === currentYear;

    // titles dinámicos
    const prev = getAdjacentMonth(viewMonth, viewYear, -1);
    const next = getAdjacentMonth(viewMonth, viewYear, 1);

    prevBtn.title = `Mes anterior: ${monthNames[prev.month]} ${prev.year}`;
    nextBtn.title = `Mes siguiente: ${monthNames[next.month]} ${next.year}`;

    prevBtn.setAttribute('aria-label', prevBtn.title);
    nextBtn.setAttribute('aria-label', nextBtn.title);
  }

  prevBtn.addEventListener('click', () => {
    viewMonth--;
    if (viewMonth < 0) {
      viewMonth = 11;
      viewYear--;
    }
    renderCalendar();
  });

  nextBtn.addEventListener('click', () => {
    viewMonth++;
    if (viewMonth > 11) {
      viewMonth = 0;
      viewYear++;
    }
    renderCalendar();
  });

  renderCalendar();
});
</script>
@endsection