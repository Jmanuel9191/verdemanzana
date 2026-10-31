<?php
namespace app\components;

class CamposReservaHelper
{
    public static function getCamposPorGrupo()
    {
        return [

            'Datos Generales Eventos' => [
                ['campo' => 'salondeeventos', 'label' => 'Salón de eventos', 'orden' => 1],
                ['campo' => 'paqueteeventos', 'label' => 'Paquete elegido', 'orden' => 2],
                ['campo' => 'horainvitacionevento', 'label' => 'Hora de invitación', 'orden' => 3],
            ],
            'Datos Generales Iglesia' => [
                ['campo' => 'iglesia', 'label' => 'Iglesia', 'orden' => 1],
                ['campo' => 'direccioniglesia', 'label' => 'Dirección iglesia', 'orden' => 2],
                ['campo' => 'horaiglesia', 'label' => 'Hora en iglesia', 'orden' => 3],
            ],
            'Datos Generales Musica' => [
                ['campo' => 'musica', 'label' => 'Música en iglesia', 'orden' => 1],
                ['campo' => 'horasmusicas', 'label' => 'Hora de música', 'orden' => 2],
                ['campo' => 'maestradeceremonia', 'label' => 'Maestra de ceremonia', 'orden' => 3],
            ],

            'Datos Generales Reuniones y degustaciones(Generales) ' => [
                ['campo' => 'celebranter', 'label' => 'Reunión con celebrante', 'orden' => 1],
                ['campo' => 'decoradorar', 'label' => 'Reunión decoradora', 'orden' => 2],
                ['campo' => 'degustacioncomida', 'label' => 'Degustación comida', 'orden' => 3],
                ['campo' => 'desgustaciontorta', 'label' => 'Degustación torta', 'orden' => 4],
                ['campo' => 'degustacionbarra', 'label' => 'Degustación barra', 'orden' => 5],
                ['campo' => 'coordinadorar', 'label' => 'Reunión coordinadora', 'orden' => 6],

            ],

            'Datos Generales Reuniones y degustaciones(Aprox)' => [
                ['campo' => 'aproxcelebrante', 'label' => 'Aprox', 'orden' => 1],
                ['campo' => 'aproxdecoradora', 'label' => 'Aprox', 'orden' => 2],
                ['campo' => 'aproxcomida', 'label' => 'Aprox', 'orden' => 3],
                ['campo' => 'aproxtorta', 'label' => 'Aprox', 'orden' => 4],
                ['campo' => 'aproxbarra', 'label' => 'Aprox', 'orden' => 5],
                ['campo' => 'aproxcoordinadora', 'label' => 'Aprox', 'orden' => 6],
            ],
            'Antes de la fiesta (Solo para 15)(Generales)' => [
                ['campo' => 'invitaciones', 'label' => 'Invitaciones', 'orden' => 1],
                ['campo' => 'fotografia', 'label' => 'Fotografia', 'orden' => 2],
                ['campo' => 'video', 'label' => 'Video', 'orden' => 3],
                ['campo' => 'vestidodenovia', 'label' => 'Vestido de quinceañera(o de novia)', 'orden' => 4],
                ['campo' => 'salondebelleza', 'label' => 'Salón de belleza', 'orden' => 5],
                ['campo' => 'limosna', 'label' => 'Limosna', 'orden' => 7],
                ['campo' => 'horantesfiesta', 'label' => 'Hora', 'orden' => 6],
            ],

            'Antes de la fiesta (Solo para 15)(Recomendaciones)' => [
                ['campo' => 'recmfotoyvideo', 'label' => 'Recomendación foto y video', 'orden' => 2],
                ['campo' => 'recmsalonbelleza', 'label' => 'Recomendación salón de belleza', 'orden' => 5],
            ],
            'Ceremonia religiosa (Boda)(Generales)' => [
                ['campo' => 'horaceremonia', 'label' => 'Hora', 'orden' => 1],
                ['campo' => 'ccordinacions', 'label' => 'Coordinación', 'orden' => 2],
                ['campo' => 'cdecoracion', 'label' => 'Decoración', 'orden' => 3],
                ['campo' => 'censamblajemusical', 'label' => 'Ensamble musical', 'orden' => 4],
                ['campo' => 'cbouquet', 'label' => 'Bouquet y botonier', 'orden' => 5],
                ['campo' => 'ctransporte', 'label' => 'Transporte', 'orden' => 6],
                ['campo' => 'caccesorios', 'label' => 'Accesorios (aras, alianzas)', 'orden' => 7],
                ['campo' => 'csalidasesposos', 'label' => 'Salida de esposos', 'orden' => 8],
                ['campo' => 'cessionfotos', 'label' => 'Sesión de fotos exterior', 'orden' => 9],
            ],
            'Ceremonia religiosa (Boda) (Recomendaciones)' => [
                ['campo' => 'crecmcoodinacion', 'label' => 'Recomendación coordinación', 'orden' => 1],
                ['campo' => 'crecmdecoracion', 'label' => 'Recomendación decoració', 'orden' => 2],
                ['campo' => 'crecmensamblaje', 'label' => 'Recomendación ensamble musical', 'orden' => 3],
                ['campo' => 'csugsaludasesposo', 'label' => 'Recomendación salida de esposos', 'orden' => 6],
            ],

            'Ceremonia civil(Boda)' => [
                ['campo' => 'horacivil', 'label' => 'Hora', 'orden' => 1],
                ['campo' => 'oficialregistrocivil', 'label' => 'Oficial de registro civil', 'orden' => 2],
                ['campo' => 'tipodeceremoniaciv', 'label' => 'Tipo de ceremonia', 'orden' => 3],
                ['campo' => 'testigoscv', 'label' => 'Testigos:', 'orden' => 4],
                ['campo' => 'cvensabmlemusical', 'label' => 'Ensamble musical', 'orden' => 5],
                ['campo' => 'cvefectosespec', 'label' => 'Efectos especiales y pirotecnias:', 'orden' => 6],
                ['campo' => 'ccprecoctel', 'label' => 'PreCoctel', 'orden' => 7],
            ],

            'Ceremonia civil(Boda - Recomendaciones)' => [
                ['campo' => 'recmregistrocv', 'label' => 'Recomendación registro civil', 'orden' => 1],
                ['campo' => 'recmensambcivil', 'label' => 'Recomendación ensamble musical', 'orden' => 2],
                ['campo' => 'recmefectos', 'label' => 'Recomendación efectos', 'orden' => 6],
            ],

            'Bebidas(Generales)' => [
                ['campo' => 'cerveza', 'label' => 'Cerveza:', 'orden' => 1],
                ['campo' => 'sodas', 'label' => 'Soda', 'orden' => 2],
                ['campo' => 'agua', 'label' => 'Agua', 'orden' => 3],
                ['campo' => 'otrosbebidas', 'label' => 'Otros', 'orden' => 4],
                ['campo' => 'barracocteles', 'label' => 'Barra de cocteles', 'orden' => 5],
                ['campo' => 'horadeapertura', 'label' => 'Hora de apertura', 'orden' => 6],
                ['campo' => 'dulcebienvenida', 'label' => 'Dulce de bienvenida', 'orden' => 7, 'solo_quinceanos' => true],
                ['campo' => 'mesadulces', 'label' => 'Mesa de dulces', 'orden' => 8, 'solo_quinceanos' => true],
                ['campo' => 'mesabocaditos', 'label' => 'Mesa de bocaditos', 'orden' => 9, 'solo_quinceanos' => true],
                ['campo' => 'maquetatortafalsa', 'label' => 'Maqueta torta falsa', 'orden' => 10, 'solo_quinceanos' => true],
            ],

            'Bebidas(Recomendaciones)' => [
                ['campo' => 'recmbebidas', 'label' => 'Recomendación bebidas', 'orden' => 1],
                ['campo' => 'recmbarradecocteles', 'label' => 'Recomendación barra de cócteles', 'orden' => 5],

            ],

            'Ingreso de quinceañera(15) o Ingreso de novios, vals y brindis(Boda)' => [
                ['campo' => 'horaingresonovios', 'label' => 'Hora', 'orden' => 1, 'solo_quinceanos' => true],
                ['campo' => 'ingresoin', 'label' => 'Ingreso', 'orden' => 2, 'solo_quinceanos' => true],
                ['campo' => 'bailedeingreso', 'label' => 'Música de ingreso o Baile ingreso con canción', 'orden' => 3],
                ['campo' => 'coreografo', 'label' => 'Coreógrafo', 'orden' => 4],
                ['campo' => 'experienciasin', 'label' => 'Experiencias', 'orden' => 5, 'solo_quinceanos' => true],
                ['campo' => 'maestoceremoniadet', 'label' => 'Maestro de ceremonia', 'orden' => 6],
                ['campo' => 'baileprincip', 'label' => 'Baile vals o Baile principal', 'orden' => 8],
                ['campo' => 'brindis', 'label' => 'Brindis', 'orden' => 9],
                ['campo' => 'baileespecial', 'label' => 'Baile especial o inicio de fiesta', 'orden' => 10],
                ['campo' => 'efectosespeciales', 'label' => 'Efectos especiales o pirotecnia', 'orden' => 11],
            ],


            'Ingreso de quinceañera(15) o Ingreso de novios, vals y brindis(Boda) - Recomendaciones' => [
                ['campo' => 'recmcoreografo', 'label' => 'Recomendación coreógrafo', 'orden' => 3],
                ['campo' => 'recmmaestrocemonia', 'label' => 'Recomendación maestro de ceremonia', 'orden' => 5],
                ['campo' => 'recmefectosd', 'label' => 'Recomendación efectos', 'orden' => 10],


            ],

            'Cena, fiesta y torta(General)' => [
                ['campo' => 'cenac', 'label' => 'Cena', 'orden' => 1],
                ['campo' => 'tortac', 'label' => 'Torta', 'orden' => 2],
                ['campo' => 'bocaditosc', 'label' => 'Bocaditos', 'orden' => 3],
                ['campo' => 'dulcesc', 'label' => 'Dulces', 'orden' => 4],
                ['campo' => 'horalocac', 'label' => 'Hora loca', 'orden' => 5],
                ['campo' => 'mariachis', 'label' => 'Mariachis', 'orden' => 6, 'solo_quinceanos' => true],
                ['campo' => 'horacena', 'label' => 'Hora cena', 'orden' => 7],
            ],

            'Cena, fiesta y torta(Recomendaciones)' => [
                ['campo' => 'recmcena', 'label' => 'Recomendación cena', 'orden' => 1],
                ['campo' => 'recmtorta', 'label' => 'Recomendación torta', 'orden' => 2],
                ['campo' => 'recmbocaditos', 'label' => 'Recomendación bocadillos', 'orden' => 3],
                ['campo' => 'recmdul', 'label' => 'Recomendación dulces', 'orden' => 4],
                ['campo' => 'recmhoraloca', 'label' => 'Recomendación hora loca', 'orden' => 5],
            ],
            'Otros proveedores(Generales)' => [

                ['campo' => 'trajenovio', 'label' => 'Traje del novio', 'orden' => 6, 'solo_boda' => true],
                ['campo' => 'glitterbar', 'label' => 'Glitterbar', 'orden' => 7], // se muestra en ambos
                ['campo' => 'espejomagico', 'label' => 'Espejo mágico', 'orden' => 8],
                ['campo' => 'video360', 'label' => 'Video 360° o Túnel', 'orden' => 9],
                ['campo' => 'memoriphone', 'label' => 'Memori phone', 'orden' => 10],
            ],

            'Otros proveedores(Recomendaciones)' => [
                ['campo' => 'recmvestidonovia', 'label' => 'Recomendación vestido de novia', 'orden' => 2, 'solo_boda' => true],
                ['campo' => 'recmtrajenovio', 'label' => 'Recomendación traje del novio', 'orden' => 4, 'solo_boda' => true],
                ['campo' => 'recmgiiterbar', 'label' => 'Recomendación glitterbar', 'orden' => 5], // se muestra en ambos
                ['campo' => 'recmemori', 'label' => 'Recomendación memori phone', 'orden' => 6],
            ],

        ];
    }
}
