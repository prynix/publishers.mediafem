@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general_home'); }} @stop

@section ('section-title') {{ Lang::get('general.ayuda'); }} @stop

@section ('content')

<form method="post" action="http://dashboard.imonomy.com/api/stat/network/8">

    <input type="text" name="network" value="8" />
    <input type="text" name="publisher" value="0" />
    <input type="text" name="website" value="0" />
    <input type="text" name="timePeriodType" value="last" />
    <input type="text" name="timePeriod" value="lastweek" />
    <input type="text" name="starttime" value="" />
    <input type="text" name="endtime" value="" />
    <input type="text" name="displayByDate" value="True" />
    <input type="text" name="displayByPlacement" value="True" />
    <input type="text" name="displayByCountry" value="True" />
    <input type="text" name="displayByWebsite" value="True" />
    <input type="text" name="displayByPublisher" value="True" />
    <input type="text" name="displayByNetwork" value="False" />
    <input type="text" name="export" value="csv" />
    <input type="text" name="username" value="adtomatik" />
    <input type="text" name="password" value="Adtomatik15" />

    <input type="submit" value="aceptars" />
</form>


<div class="row">
    <h2 id="freelancers">
        Freelancers
    </h2>

    <ul>
        <li><a href="#recomendations">Recomendaciones para afiliar Publishers</a></li>
        @if(Utility::hasPermission('users'))<li><a href="#nuevo_freelancer">Alta de un nuevo Freelancer</a></li>@endif
        @if(Utility::hasPermission('users'))<li><a href="#editar_freelancer">Edici&oacute;n de datos del freelancer</a></li>@endif
        <li><a href="#affiliate_sites">Afiliaci&oacute;n de sitios</a></li>
        <li><a href="#earnings">Control de ingresos por afiliaci&oacute;n</a></li>
        @if(Utility::hasPermission('payments'))<li><a href="#payments">Pagos a los Freelancers</a></li>@endif
        @if(Utility::hasPermission('constants'))<li><a href="#config">Configuraciones generales de freelancers</a></li>@endif
        <li><a href="#glosary">Glosario</a></li>
    </ul><hr />

    <h3 id="recomendations">Recomendaciones para afiliar Publishers</h3>
    <p>B&uacute;squeda en <a href="http://www.google.com" target="_blank">Google.com</a>, <a href="http://alexa.com" target="_blank">Alexa.com</a> 
        o <a href="http://similarsitesearch.com" target="_blank">Similarsitesearch.com</a> para obtener listado de sitios. 
        El mismo debe ubicarse entre el puesto 1 al 100.000 del ranking de Alexa. El mismo no debe poseer tr&aacute;fico generado por robots, clicks generados por robots, frases o leyendas que sugieran hacer click, violaciones de copyright, violencia, discriminaci&oacute;n, actividad ilegal, contenido adulto (xxx), etc.
        Buscar dentro del mismo la solapa Contact. En caso de no poseer datos se puede buscar el mismo en <a href="http://who.is" target="_blank">who.is</a>.</p>
    <p>Enviar un mail de contacto (el cual le proveeremos previamente). S&iacute; luego de pasados dos d&iacute;as no hay respuesta se recomienda reiterar el contacto por tel&eacute;fono preferentemente o a trav&eacute;s del env&iacute;o de un nuevo mail.</p>
    <p>Los requisitos de los sitios ser&aacute;n:</p>
    <ul>
        <li>Contenido en ingl&eacute;s</li>
        <li>Debe ubicarse entre el puesto 1 al 100.000 del ranking de Alexa (<a href="http://alexa.com" target="_blank">Alexa.com</a>).</li>
        <li>Contenido apto para todo p&uacute;blico.</li>
    </ul>
    <p><a href="#freelancers">&uarr; Volver</a></p><hr />

    @if(Utility::hasPermission('users'))
    <h3 id="nuevo_freelancer">Alta de un nuevo Freelancer</h3>
    <p>La incorporaci&oacute;n de un nuevo freelancer se realiza desde la solapa 
        <b>Usuarios</b>, haciendo clic sobre "<b>Agregar usuario</b>".</p>
    {{ HTML::image('images/help/free1.jpg') }}
    {{ HTML::image('images/help/free2.jpg') }}
    <p>En la ventana modal seleccionar perfil de usuario como <i>Freelancer</i>, 
        Adserver al cual estar&aacute; agregando nuevos publishers (<u>uno solo</u>) y el mail del mismo.</p>
<p>Es importante que el freelancer tenga acceso al mail ingresado ya que adem&aacute;s de recibir el link
    de activaci&oacute;n de cuenta recibe la contrase&ntilde;a para acceder a la herramienta.</p>
{{ HTML::image('images/help/free3.jpg') }}
<p><a href="#freelancers">&uarr; Volver</a></p><hr />
@endif

@if(Utility::hasPermission('users'))
<h3 id="editar_freelancer">Edici&oacute;n de datos del freelancer</h3>
<p>Desde la tabla de usuarios (dentro de la solapa de <b>Usuarios</b>) hacer clic en <b>Ver &#187;</b> al usuario
    freelancer creado, en la parte inferior de la pantalla aparece un formulario para cambiar tanto el nombre del
    freelancer como su revenue share, que en caso de dejar en <i>0</i> tomar&aacute; valor que est&aacute; 
    definido por defecto.</p>
{{ HTML::image('images/help/free4.jpg') }}
<p><a href="#freelancers">&uarr; Volver</a></p><hr />
@endif

<h3 id="affiliate_sites">Afiliaci&oacute;n de sitios</h3>
<p>Una vez activada la cuenta (mediante el link que llega al mail) se procede a la afiliaci&oacute;n de Sitios.
    En la solapa de Escritorio se encuentra la URL que sirve para registrar los sitios bajo el ejecutivo logueado. 
<u>Es importante no modificar la URL</u>, de lo contrario el sitio se podr&aacute; registrar de todas formas pero 
no estar&aacute; asignado a ning&uacute;n ejecutivo.</p>
{{ HTML::image('images/help/free5.jpg') }}
<p>Una vez que el Sitio realiz&oacute; todo el registro <u>completo</u>, aparece en la solapa <b>Publishers</b>
mostrando su URL principal, nombre y email. Para ver informaci&oacute;n detallada del mismo hacer clic en 
<b>Ver &#187;</b>, donde tambi&eacute;n se puede ver el historial de pagos de cada sitio.</p>
{{ HTML::image('images/help/free6.jpg') }}
<p><a href="#freelancers">&uarr; Volver</a></p><hr />

<h3 id="earnings">Control de ingresos por afiliaci&oacute;n</h3>
<p>En la parte superior de la pantalla se muestra el balance actual del freelancer, son los ingresos hasta la fecha de
    <i>AYER</i> inclusive y no incluyen los pagos en proceso.</p>
<p>Para ver el historial de ingresos y pagos mensuales y los pagos que se encuentran en proceso hacer clic en 
    <b>Balance actual</b> o ingresando al men&uacute; de usuario solapa <b>Pagos</b>.</p>
{{ HTML::image('images/help/free7.jpg') }}
{{ HTML::image('images/help/free8.jpg') }}
<p>Tambi&eacute;n se puede ver un detalle de los ingresos que se tiene por cada (publisher, sitio, pa&iacute;s) afiliado 
    ingresando a la solapa <b>Ingresos de Afiliaci&oacute;n</b>, en dicha secci&oacute;n debe seleccionar el rango de fechas 
    del reporte (<u>No mayor a un mes</u>) y ejecutar el reporte.</p>
{{ HTML::image('images/help/free9.jpg') }}
{{ HTML::image('images/help/free10.jpg') }}
<p>El reporte muestra las impresiones, los clics, el CTR, promedio del CPM y los ingresos que tuvo cada (publisher, sitio o pa&iacute;s) 
    en el rango de fechas seleccionado. Adem&aacute;s lleva una columna que indica el ingreso del freelancer.</p>
{{ HTML::image('images/help/free11.jpg') }}
<p><a href="#freelancers">&uarr; Volver</a></p><hr />

@if(Utility::hasPermission('payments'))
<h3 id="payments">Pagos a los Freelancers</h3>
<p>El control de pagos que se realizar&aacute; a los freelancers se encuentra en la solapa <b>Pagos</b>><b>Ingresos de Freelancers</b>
    donde las tablas de ingresos, pagos en proceso e historial de pagos es exactamente igual a Pagos de Publishers, por lo que la 
    &uacute;nica diferencia es que los freelancers se listan por su <i>EMAIL</i> y no hay filtro por Media Buyer.</p>
{{ HTML::image('images/help/free12.jpg') }}
{{ HTML::image('images/help/free13.jpg') }}
<p><a href="#freelancers">&uarr; Volver</a></p><hr />
@endif

@if(Utility::hasPermission('constants'))
<h3 id="config">Configuraciones generales de freelancers</h3>
<p>En la solapa <b>Constantes</b> el grupo llamado "<b>Control de Freelancers</b>" permite modificar tanto la cantidad de 
    d&iacute;as de pago a los Freelancers como el revenue share default de estos. <u><b>Importante!</b></u> Tener en cuenta que 
la aplicaci&oacute;n del revenue share es din&aacute;mico de modo que el balance actual y el reporte de inventario mostrar&aacute;
los ingresos correspondientes al share actual.</p>
{{ HTML::image('images/help/free14.jpg') }}
<p><a href="#freelancers">&uarr; Volver</a></p>
@endif

<h3 id="glosary">Glosario</h3>
<ul>
    <li><b>Freelance</b>: se denomina freelance (o trabajador aut&oacute;nomo, cuenta-propia o trabajador independiente) a la persona cuya actividad consiste en realizar trabajos propios de su ocupaci&oacute;n, oficio o profesi&oacute;n, de forma aut&oacute;noma, para terceros que requieren sus servicios para tareas determinadas, que generalmente le abonan su retribuci&oacute;n.</li>
    <li><b>Sitio web</b>: es una colecci&oacute;n de p&aacute;ginas de internet relacionadas y comunes a un dominio de Internet o subdominio en la World Wide Web en Internet (www).</li>
    <li><b>Publisher</b>: el sitio web que se utilice para abrir la cuenta en la plataforma de Adtomatik.com ser&aacute; considerado como un "Publisher". Luego podr&aacute; ingresar m&aacute;s sitios web bajo esta cuenta registrada.</li>
    <li><b>Alexa</b>: es un ranking que se genera en base las visitas que tienen los sitios web.</li>
    <li><b>PayPal</b>: es una empresa estadounidense que pertenece al sector de comercio electr&oacute;nico y permite pagar en sitios web, as&iacute; como transferir dinero entre usuarios que tengan correo electr&oacute;nico, una alternativa al tradicional m&eacute;todo en papel como los cheques o giros postales.</li>
    <li><b>URL</b>: es una secuencia de caracteres, de acuerdo a un formato mod&eacute;lico y est&aacute;ndar, que se usa para nombrar recursos en Internet para su localizaci&oacute;n o identificaci&oacute;n, como por ejemplo documentos textuales, im&aacute;genes, v&iacute;deos.</li>
    <li><b>Impresi&oacute;n</b>: es cada una de las veces que un determinado usuario se ve expuesto a un anuncio publicitario, independientemente de la atenci&oacute;n que le preste.</li>
    <li><b>CTR</b>: costo por mil impresiones.</li>
    <li><b>CPM</b>: tasa de clicks, es decir, n&uacute;mero de clicks dividido por el de impresiones. O dicho de otro modo, proporci&oacute;n entre las impresiones de un banner y las veces que los visitantes han hecho click sobre &eacute;l.</li>
</ul>
<p><a href="#freelancers">&uarr; Volver</a></p>
</div>


@stop