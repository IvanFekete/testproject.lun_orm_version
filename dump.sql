PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE cities (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL);
INSERT INTO cities VALUES(1,'Київ');
INSERT INTO cities VALUES(2,'Харків');
INSERT INTO cities VALUES(3,'Одеса');
INSERT INTO cities VALUES(4,'Львів');
INSERT INTO cities VALUES(5,'Дніпро');
CREATE TABLE localities_cities (city_id INTEGER NOT NULL, locality_id INTEGER NOT NULL, PRIMARY KEY(city_id, locality_id), CONSTRAINT FK_11BA85638BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_11BA856388823A92 FOREIGN KEY (locality_id) REFERENCES localities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE);
CREATE TABLE complexes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_B03120CD8BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO complexes VALUES(1,1,'Сонячна брама');
INSERT INTO complexes VALUES(2,1,'Ліко-град');
INSERT INTO complexes VALUES(3,3,'Еллада');
INSERT INTO complexes VALUES(4,1,'Воздвиженка');
CREATE TABLE flats (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, house_id INTEGER DEFAULT NULL, square DOUBLE PRECISION NOT NULL, price INTEGER NOT NULL, flatType_id INTEGER DEFAULT NULL, CONSTRAINT FK_6AEA0028ECA26995 FOREIGN KEY (flatType_id) REFERENCES flat_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6AEA00286BB74515 FOREIGN KEY (house_id) REFERENCES houses (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO flats VALUES(1,3,50.0,967000,2);
INSERT INTO flats VALUES(2,3,50.0,967000,2);
INSERT INTO flats VALUES(3,3,50.0,967000,2);
INSERT INTO flats VALUES(4,3,50.0,967000,2);
INSERT INTO flats VALUES(5,3,50.0,967000,2);
CREATE TABLE flat_types (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL);
INSERT INTO flat_types VALUES(1,'студія');
INSERT INTO flat_types VALUES(2,'1к');
INSERT INTO flat_types VALUES(3,'2к');
INSERT INTO flat_types VALUES(4,'3к');
INSERT INTO flat_types VALUES(5,'4к');
INSERT INTO flat_types VALUES(6,'5к');
INSERT INTO flat_types VALUES(7,'6к');
INSERT INTO flat_types VALUES(8,'5к дворівнева');
INSERT INTO flat_types VALUES(9,'6к дворівнева');
CREATE TABLE houses (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, complex_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_95D7F5CBE4695F7C FOREIGN KEY (complex_id) REFERENCES complexes (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO houses VALUES(1,1,'Будинок А');
INSERT INTO houses VALUES(2,1,'Будинок Б');
INSERT INTO houses VALUES(3,3,'Генуезька, 1/1');
INSERT INTO houses VALUES(4,2,'Будинок 1');
INSERT INTO houses VALUES(5,2,'Будинок 2');
INSERT INTO houses VALUES(7,4,'Будинок Б(зелений)');
CREATE TABLE localities (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL);
INSERT INTO localities VALUES(1,'Правий берег');
INSERT INTO localities VALUES(2,'Поділ');
INSERT INTO localities VALUES(3,'Теремки-2');
INSERT INTO localities VALUES(4,'Голосіїв');
INSERT INTO localities VALUES(5,'Аркадія');
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('flat_types',9);
INSERT INTO sqlite_sequence VALUES('cities',5);
INSERT INTO sqlite_sequence VALUES('complexes',4);
INSERT INTO sqlite_sequence VALUES('houses',7);
INSERT INTO sqlite_sequence VALUES('flats',5);
INSERT INTO sqlite_sequence VALUES('localities',7);
CREATE INDEX IDX_11BA85638BAC62AF ON localities_cities (city_id);
CREATE INDEX IDX_11BA856388823A92 ON localities_cities (locality_id);
CREATE INDEX IDX_B03120CD8BAC62AF ON complexes (city_id);
CREATE INDEX IDX_6AEA0028ECA26995 ON flats (flatType_id);
CREATE INDEX IDX_6AEA00286BB74515 ON flats (house_id);
CREATE INDEX IDX_95D7F5CBE4695F7C ON houses (complex_id);
COMMIT;