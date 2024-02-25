@extends('main_template')

@section('title', 'Contacta con nosotros')

@section('site-content')
<div class="row">
    <div class="eightcol column">
        <div class="section-title">
            <h1>Contacta con nosotros</h1>
        </div>
        <form action="mail.php" method="POST" class="formatted-form" name="clientes" onsubmit="return validacion();">        
            <div class="sixcol column ">
                <div class="field-container">
                    <input type="text" id="nombre" name="nombre" title="Introduzca su Nombtre Completo." maxlength="50" placeholder="Nombre Completo" value="" required="">
                </div>
            </div>
            <div class="sixcol column last">
                <div class="field-container">
                    <input type="email" id="email" name="email" title="Introduzca su Email." maxlength="80" placeholder="Email" value="" required="">
                </div>
            </div>                            
            <div class="clear"></div>
            <div class="field-container">
                <textarea id="mensaje" name="mensaje" title="Introduzca su Mensaje." maxlength="1000" placeholder="Mensaje" required=""></textarea>
            </div>
            <input type="submit" class="action" value="Enviar" title="Enviar">
            <input type="hidden" name="producto" value="algo">
            <input type="hidden" name="volver" value="http://localhost/caribetour/contactos">
        </form>
    </div>
    <div class="fourcol column last">
        <div class="widget widget-subscribe">
            <div class="section-title"><h4>Noticias</h4></div>
            <p>Suscríbete a nuestras noticias para mantenerte actualizado con las ofertas de último minuto disponibles en CaribeTour.es</p>
            <form action="/caribetour/contacto.php?" method="POST" name="suscribir" onsubmit="return suscriptor();">
                <div class="message" id="srequired" style="display:none;">Por favor indique un correo válido</div>
                                                                                                                <div class="field-container">
                    <input type="email" name="email" id="suscribir" placeholder="Email" value="" title="Introduzca su Email para suscribirse." maxlength="60" required="">
                </div><br>
                <input type="submit" class="action" value="Suscribirse" title="Suscribirse">
                <input type="hidden" name="MM_insert" value="suscribir">
            </form>
      </div>
        <div class="widget widget_text">
            <div class="section-title">
                <h4>Detalles</h4>
            </div>
            <div class="textwidget">
              <!--<strong>Ofic&iacute;na:</strong> 6258 Amesbury St, San Diego, CA<br />
              <strong>Tel&eacute;fono:</strong> (619) 264-5690<br />-->
              <strong>Email:</strong><img src="{{ asset('images/email.gif') }}" alt="Email" width="119" height="15">
          </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
@endsection