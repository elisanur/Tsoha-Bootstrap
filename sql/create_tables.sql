CREATE TABLE Username(id SERIAL PRIMARY KEY,
firstname varchar(50) NOT NULL,
lastname varchar(50) NOT NULL,
address varchar(50) NOT NULL,
postalcode varchar(5) NOT NULL,
city varchar(50) NOT NULL,
name varchar(20) NOT NULL,
password varchar(20) NOT NULL,
email varchar(500) UNIQUE NOT NULL);

CREATE TABLE Poster(id SERIAL PRIMARY KEY,
name varchar(50) NOT NULL,
publisher INTEGER REFERENCES Username(id),
artist varchar(50) NOT NULL,
price INTEGER NOT NULL,
location varchar(50) NOT NULL, 
height INTEGER NOT NULL,
width INTEGER NOT NULL,
image OID,
filesize BIGINT,
sold boolean DEFAULT FALSE);

CREATE TABLE Category(name varchar(20) PRIMARY KEY);

CREATE TABLE PosterCategory(category varchar(20) REFERENCES Category(name),
poster INTEGER REFERENCES Poster(id));

CREATE TABLE Purchase(id SERIAL PRIMARY KEY,
 poster INTEGER REFERENCES Poster(id),
 username INTEGER REFERENCES Username(id));
