DROP DATABASE TEConnect;
CREATE DATABASE TEConnect;
USE TEConnect;

CREATE TABLE Usuario
(
  ID_User INT NOT NULL AUTO_INCREMENT,
  PrimerNombre VARCHAR(50) NOT NULL,
  Apellido VARCHAR(50) NOT NULL,
  Correo VARCHAR(50),
  LugarOrigen VARCHAR(50),
  Foto MEDIUMBLOB,
  FechaNacimiento DATETIME,
  Carrera CHAR(5),
  UltimaConexion DATETIME,
  Contrasena VARCHAR(50),
  Matricula VARCHAR(10),
  
  PRIMARY KEY (ID_User)
);

CREATE TABLE Ambito
(
  ID_Ambito INT NOT NULL AUTO_INCREMENT,
  Nombre VARCHAR(40) NOT NULL,
  
  PRIMARY KEY (ID_Ambito)
);

CREATE TABLE Conexion
(
  FechaCreada DATETIME,
  ID_User1 INT NOT NULL,
  ID_User2 INT NOT NULL,
  ID_Ambito INT NOT NULL,
  
  PRIMARY KEY (ID_User1, ID_User2, ID_Ambito),
  FOREIGN KEY (ID_User1) REFERENCES Usuario(ID_User),
  FOREIGN KEY (ID_User2) REFERENCES Usuario(ID_User),
  FOREIGN KEY (ID_Ambito) REFERENCES Ambito(ID_Ambito)
);

CREATE TABLE DetalleAmbito
(
  Descripción TEXT,
  ID_User INT NOT NULL,
  ID_Ambito INT NOT NULL,
  
  PRIMARY KEY (ID_User, ID_Ambito),
  FOREIGN KEY (ID_User) REFERENCES Usuario(ID_User),
  FOREIGN KEY (ID_Ambito) REFERENCES Ambito(ID_Ambito)
);

CREATE TABLE DetalleAmbito_Interes
(
  Interes VARCHAR(40),
  ID_User INT NOT NULL,
  ID_Ambito INT NOT NULL,
  
  PRIMARY KEY (Interes, ID_User, ID_Ambito),
  FOREIGN KEY (ID_User, ID_Ambito) REFERENCES DetalleAmbito(ID_User, ID_Ambito)
);

CREATE TABLE DetalleAmbito_Gusto
(
  Gusto VARCHAR(40),
  ID_User INT NOT NULL,
  ID_Ambito INT NOT NULL,
  
  PRIMARY KEY (Gusto, ID_User, ID_Ambito),
  FOREIGN KEY (ID_User, ID_Ambito) REFERENCES DetalleAmbito(ID_User, ID_Ambito)
);

CREATE TABLE DetalleAmbito_Actividad
(
  Actividad VARCHAR(40),
  ID_User INT NOT NULL,
  ID_Ambito INT NOT NULL,
  
  PRIMARY KEY (Actividad, ID_User, ID_Ambito),
  FOREIGN KEY (ID_User, ID_Ambito) REFERENCES DetalleAmbito(ID_User, ID_Ambito)
);

CREATE TABLE Mensaje
(
  Cuerpo TEXT,
  HoraEnviado DATETIME,
  ID_Mensaje INT NOT NULL AUTO_INCREMENT,
  ID_Sender INT NOT NULL,
  ID_User1 INT NOT NULL,
  ID_User2 INT NOT NULL,
  ID_Ambito INT NOT NULL,
  
  PRIMARY KEY (ID_Mensaje, ID_User1, ID_User2),
  FOREIGN KEY (ID_Sender) REFERENCES Usuario(ID_User),
  FOREIGN KEY (ID_User1, ID_User2, ID_Ambito) REFERENCES Conexion(ID_User1, ID_User2, ID_Ambito)
);
