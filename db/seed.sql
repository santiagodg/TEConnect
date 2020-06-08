INSERT INTO Usuario 
VALUES
 (1, 'Andres', 'Lopez', 'A01649372@itesm.mx', 'Monterrey','1.png', 19990622, 'LIN', 20200601, 'alopez22','A01649372' ),
 (2, 'Mariano', 'Perez', 'A03827499@itesm.mx','CDMX', '2.png', 19981002, 'LAE',20000520, 'mperez98', 'A03827499'),
 (3, 'Andrea', 'Garza', 'A01027497@itesm.mx','Monterrey', '3.png', 20000705, 'ARQ', 20200305, 'agarza00', 'A01027497'),
 (4, 'Javier', 'Peña','A01274665@itesm.mx','Houston','4.jpg', 19970321, 'ITC', 20200602,'jpeña97', 'A01274665'),
 (5, 'Sofia', 'Gomez','A01748277@itesm.mx','Bogotá','5.png', 19990415,'LEC', 20200303,'sgomez99','A01748277');


INSERT INTO Ambito 
VALUES
 (1, 'Profesional'),
 (2, 'Amistad'),
 (3, 'Amoroso');

INSERT INTO DetalleAmbito
VALUES
 ('Busco alguien ambicioso y profesional con ganas de lograr metas grandes', 1, 1),
 ('Me gusta comer helado e ir al cine', 2, 2),
 ('Me gusta la gente fiestera y amable', 3, 3),
 ('Me gusta tomar café y patinar', 4, 2),
 ('Me gusta alguien culto para ir a museos y hablar de cine clásico', 5, 3);

INSERT INTO Conexion
VALUES (20200601, 1, 3, 3),
 (20000520, 2, 4, 2),
 (20200305, 2, 5, 3),
 (20200602, 1, 2, 1),
 (20200303, 4, 5, 2);

-- simetría
INSERT INTO Conexion
VALUES (20200601, 3, 1, 3),
 (20000520, 4, 2, 2),
 (20200305, 5, 2, 3),
 (20200602, 2, 1, 1),
 (20200303, 5, 4, 2);

INSERT INTO DetalleAmbito_Interes
VALUES 
 ('cine', 2, 2),
 ('helado', 2, 2),
 ('comedia', 2, 2),
 ('futbol', 2, 2),
 ('café', 4, 2),
 ('patinaje', 4, 2),
 ('bicicleta', 4, 2),
 ('gimnasio', 4, 2),
 ('fiestas', 5, 3),
 ('antros', 5, 3),
 ('bares', 5, 3),
 ('conciertos', 5, 3);

INSERT INTO DetalleAmbito_Gusto     
VALUES
 ('libros', 1, 1),
 ('cine', 1, 1),
 ('clásico', 1, 1),
 ('poesía', 1, 1),
 ('basquet', 3, 3),
 ('fiestas', 3, 3),
 ('bares', 3, 3),
 ('discotecas', 2, 2),
 ('antros', 2, 2),
 ('fiestas', 2, 2),
 ('salsa', 2, 2),
 ('reggaeton', 2, 2),
 ('museos', 5, 3),
 ('café', 5, 3),
 ('cine clásico', 5, 3),
 ('idiomas', 5, 3);

INSERT INTO DetalleAmbito_Actividad   
VALUES
 ('profesionalismo', 1, 1),
 ('actitud positiva', 1, 1),
 ('trabajador', 1, 1),
 ('activo', 2, 2),
 ('consistencia', 2, 2),
 ('perseverancia', 2, 2),
 ('estándares', 2, 2);

INSERT INTO Mensaje
VALUES
 ("hey, cómo estás?", 20200603, 1, 1, 1, 3, 3),
 ("buenas tardes, cómo te encuentras?", 20200604, 2, 2, 1, 2, 1),
 ("Qué onda!", 20200605, 3, 5, 2, 5, 3);
