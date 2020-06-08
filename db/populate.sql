INSERT INTO Usuario 
VALUES
 (1, 'Andres', 'Lopez', 'A01649372@itesm.mx', 'Monterrey','1.jpg', 19990622, 'LIN', 20200601, 'alopez22','A01649372' ),
 (2, 'Mariano', 'Perez', 'A03827499@itesm.mx','CDMX', '2.jpg', 19981002, 'LAE',20000520, 'mperez98', 'A03827499'),
 (3, 'Andrea', 'Garza', 'A01027497@itesm.mx','Monterrey', '3.jpg', 20000705, 'ARQ', 20200305, 'agarza00', 'A01027497'),
 (4, 'Javier', 'Peña','A01274665@itesm.mx','Houston','4.jpg', 19970321, 'ITC', 20200602,'jpeña97', 'A01274665'),
 (5, 'Sofia', 'Gomez','A01748277@itesm.mx','Bogotá','5.jpg', 19990415,'LEC', 20200303,'sgomez99','A01748277');


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

INSERT INTO DetalleAmbito_Interes
VALUES('cine, helado, comedia, futbol', 2, 2),
 ('café, patinaje, bicicleta, gimnasio', 4, 2),
 ('fiestas, antros, bares, conciertos', 5, 3);

INSERT INTO DetalleAmbito_Gusto     
VALUES('libros, cine clásico, poesía', 1, 3),
 ('basquet, fiestas, bares', 3, 3),
 ('discotecas, antros, fiestas, salsa, reggaeton', 2, 3),
 ('museos, café, cine clásico, idiomas', 5, 3);

INSERT INTO DetalleAmbito_Actividad   
VALUES('profesionalismo, actitud positiva, trabajador', 1, 1),
 ('Activo, consistencia, perseverancia, estándares', 2, 1);

INSERT INTO Mensaje
VALUES(1, 'hey, cómo estás?',	1, 1, 3, 3),
 (2,' buenas tardes, cómo te encuentras?', 2, 1, 2, 1),
 (3, 'Qué onda!',	5, 2, 5, 2);