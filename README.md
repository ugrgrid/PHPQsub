PHPQstat
========

PHPQstat is a PHP interface for Grid Engine, aimed at providing easily job
submitting and basic monitoring capabilities.

Requisitos
----------

PHPQstat ha sido probado satisfactoriamente en el siguiente entorno:

1. Sun Grid Engine 6.2.
2. Volumen de directorios de trabajo habitual para cada grupo y usuario, del
   tipo `/SCRATCH/grupo/usuario`.
3. Directorio de trabajos enviados vía web en un directorio distinto al anterior
   y propiedad del usuario que ejecuta el servidor web, por ejemplo
   /SCRATCH/nobody. PHPQstat almacena los scripts, archivos de entrada,
   archivos de salida, etc. de los trabajos en subdirectorios a partir de esta
   ruta.
4. Utilidad `sudo` correctamente configurada en `/etc/sudoers` para ejecutar las
   órdenes incluidas en los scripts con la instrucción `exec` de PHP. Se
   recomienda otorgar los mínimos privilegios necesarios para la ejecución
   correcta. Por su parte, PHPQstat realiza varias comprobaciones de seguridad
   para evitar que se produzcan accesos no autorizados o se realicen inyecciones
   de código que puedan comprometer la integridad del sistema.

A continuación se comentan los parámetros del archivo `params.php`:

* `id_grupo`: identificación del grupo del usuario actual. PHPQstat ha sido
  probado con un código de tres letras mayúsculas y tres dígitos.
* `usuario`: nombre POSIX del usuario actual.
* `directorio_raiz`: directorio utilizado para colocar los archivos de los
  trabajos enviados con PHPQstat. Se crea un subdirectorio por cada trabajo.
* `directorio_descargas`: es un alias del directorio anterior, utilizado para
  no exponer totalmente la ruta del directorio donde se colocan los archivos
  cuando un usuario descarga archivos. Se debe crear un enlace simbólico en el
  sistema de archivos o un alias en el servidor web para establecer la
  asociación.
* `directorio_scripts`: directorio donde están alojados los scripts PHP de
  PHPQstat. Evidentemente, ha de ser accesible por el servidor web.
* directorio aplicaciones: directorio donde se colocan los scripts de shell
  `qdelweb`, `qconvert` y `qsub`, así como los scripts de lanzamiento de
  aplicaciones utilizados en la modalidad de envío sencilla.
* `sge_config`: archivo de configuración de Grid Engine, que configura el
  entorno de ejecución (variables de entorno, directorios, paths, etc.) para
  utilizar las distintas órdenes del mismo.
* `sudo`, `zip`, `chown`, `chmod`...: ruta completa en el sistema de archivos a
  estas utilidades.

Para conseguir una funcionalidad completa de PHPQstat es necesario añadir otros
archivos, por ejemplo para el aspecto de las páginas (CSS) o las identidades de
usuarios (gestión de sesiones, contraseñas, etc.). Asimismo, algunos literales,
expresiones y variables contenidos en los scripts se deben modificar de
acuerdo a las particularidades del sistema donde se ejecute PHPQstat. Se
recomienda revisar y adaptar todo el código en función de las necesidades
concretas.
