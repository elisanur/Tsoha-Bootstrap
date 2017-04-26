INSERT INTO Username( firstname, lastname, address, postalcode, city, name,
 password, email) VALUES ('Make', 'Makkonen', 'Pengerkatu 5 A 5', '00150',
 'Helsinki', 'makemies', 'makeonjees', 'nurmi.elisa@gmail.com');

INSERT INTO Username( firstname, lastname, address, postalcode, city, name,
 password, email) VALUES ('Liisa', 'Laatu', 'Pengerkatu 6 A 8', '00150',
 'Espoo', 'laatunainen', 'laadullaonväliä', 'elisa.nurmi@me.com');

INSERT INTO Username( firstname, lastname, address, postalcode, city, name,
 password, email) VALUES ('Make', 'Mikkonen', 'Alkonkatu 4 B 12', '00160',
 'Helsinki', 'mikkonen', 'mikkonen12', 'elisa.nurmi@helsinki.fi');

INSERT INTO Poster (name, publisher, artist, price, location, height, width) 
VALUES ('Eye', 1, 'Make Makkonen', 23, 'Helsinki', 50, 40);

INSERT INTO Poster (name, publisher, artist, price, location, height, width) 
VALUES ('Young and beautiful', 2, 'Liisa Laatu', 15, 'Espoo', 100, 70);

INSERT INTO Poster (name, publisher, artist, price, location, height, width) 
VALUES ('Wrong', 3, 'Make Mikkonen', 40, 'Helsinki', 200, 150);

INSERT INTO Poster (name, publisher, artist, price, location, height, width, sold) 
VALUES ('Wrong', 3, 'Make Mikkonen', 40, 'Helsinki', 200, 150, TRUE);

INSERT INTO Poster (name, publisher, artist, price, location, height, width, sold) 
VALUES ('Wrong', 3, 'Make Mikkonen', 40, 'Helsinki', 200, 150, TRUE);

INSERT INTO Poster (name, publisher, artist, price, location, height, width, sold) 
VALUES ('Wrong', 3, 'Make Mikkonen', 40, 'Helsinki', 200, 150, TRUE);

INSERT INTO Poster (name, publisher, artist, price, location, height, width, sold) 
VALUES ('Wrong', 3, 'Make Mikkonen', 40, 'Helsinki', 200, 150, TRUE); 

INSERT INTO Poster (name, publisher, artist, price, location, height, width, sold) 
VALUES ('Young and beautiful', 2, 'Liisa Laatu', 15, 'Espoo', 100, 70, TRUE);

INSERT INTO Poster (name, publisher, artist, price, location, height, width, sold) 
VALUES ('Young and beautiful', 2, 'Liisa Laatu', 15, 'Espoo', 100, 70, TRUE); 

INSERT INTO Category (name) VALUES ('modernism');
INSERT INTO Category (name) VALUES ('post-modernism');
INSERT INTO Category (name) VALUES ('postminimalism');
INSERT INTO Category (name) VALUES ('pointilism');
INSERT INTO Category (name) VALUES ('art deco');
INSERT INTO Category (name) VALUES ('futurism');
INSERT INTO Category (name) VALUES ('cubism');
INSERT INTO Category (name) VALUES ('impressionism');
INSERT INTO Category (name) VALUES ('constructivism');
INSERT INTO Category (name) VALUES ('surrealism');
INSERT INTO Category (name) VALUES ('art nouveau');

INSERT INTO PosterCategory (category, poster) VALUES ('cubism', 1);
INSERT INTO PosterCategory (category, poster) VALUES ('modernism', 1);
INSERT INTO PosterCategory (category, poster) VALUES ('surrealism', 2);
INSERT INTO PosterCategory (category, poster) VALUES ('futurism', 2);
INSERT INTO PosterCategory (category, poster) VALUES ('modernism', 2);
INSERT INTO PosterCategory (category, poster) VALUES ('impressionism', 3);

INSERT INTO Purchase (poster, username) VALUES (2, 1);
INSERT INTO Purchase (poster, username) VALUES (3, 2);
INSERT INTO Purchase (poster, username) VALUES (1, 3);
