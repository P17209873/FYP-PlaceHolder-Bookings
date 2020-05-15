/* Ensure that the database is created before performing table creation */
DROP DATABASE IF EXISTS PlaceHolder;
CREATE DATABASE PlaceHolder;
USE PlaceHolder;
GRANT ALL ON PlaceHolder.* TO 'PlaceHolderUser'@'localhost' IDENTIFIED BY 'ubG4hofF';

/* Start creating tables */
DROP TABLE IF EXISTS Country;
CREATE TABLE Country (
    CountryID INT(11) NOT NULL AUTO_INCREMENT,
    CountryName VARCHAR(256) NOT NULL,
    PRIMARY KEY (CountryID)
);

DROP TABLE IF EXISTS City;
CREATE TABLE City (
    CityID INT(11) NOT NULL AUTO_INCREMENT,
    CityName VARCHAR(256) NOT NULL,
    CountryID INT(11) NOT NULL,
    PRIMARY KEY (CityID),
    FOREIGN KEY (CountryID) REFERENCES Country(CountryID)
);

DROP TABLE IF EXISTS UserAddress;
CREATE TABLE UserAddress (
    UserAddressID INT(11) NOT NULL AUTO_INCREMENT,
    StreetNumber INT(20) NOT NULL,
    StreetLine1 VARCHAR(512) NOT NULL,
    StreetLine2 VARCHAR(512),
    Postcode VARCHAR(28) NOT NULL,
    CityID INT(11) NOT NULL,
    AddressTypeID INT(11) NOT NULL,
    PRIMARY KEY (UserAddressID),
    FOREIGN KEY (CityID) REFERENCES City(CityID)
);

DROP TABLE IF EXISTS LocationAddress;
CREATE TABLE LocationAddress (
     LocationAddressID INT(11) NOT NULL AUTO_INCREMENT,
     StreetNumber INT(20) NOT NULL,
     StreetLine1 VARCHAR(512) NOT NULL,
     StreetLine2 VARCHAR(512),
     Postcode VARCHAR(28) NOT NULL,
     CityID INT(11) NOT NULL,
     PRIMARY KEY (LocationAddressID),
     FOREIGN KEY (CityID) REFERENCES City(CityID)
);

DROP TABLE IF EXISTS UserType;
CREATE TABLE UserType (
    UserTypeID INT(11) NOT NULL AUTO_INCREMENT,
    UserType VARCHAR(256) NOT NULL,
    PRIMARY KEY (UserTypeID)
);

DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
    UserID INT(11) NOT NULL AUTO_INCREMENT,
    Username VARCHAR(48) NOT NULL,
    UserPassword VARCHAR(356) NOT NULL,
    UserEmail VARCHAR(250) NOT NULL,
    UserFirstName VARCHAR(64) NOT NULL,
    UserLastName VARCHAR(64) NOT NULL,
    UserTypeID INT(11) NOT NULL,
    UserAddressID INT(11),
    UserCreationTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (UserID),
    FOREIGN KEY (UserTypeID) REFERENCES UserType(UserTypeID),
    FOREIGN KEY (UserAddressID) REFERENCES UserAddress(UserAddressID)
);

DROP TABLE IF EXISTS Currency;
CREATE TABLE Currency (
    CurrencyID INT(11) NOT NULL AUTO_INCREMENT,
    CurrencyName VARCHAR(256) NOT NULL,
    CurrencyISOCode VARCHAR(3) NOT NULL,
    PRIMARY KEY (CurrencyID)
);

DROP TABLE IF EXISTS EventType;
CREATE TABLE EventType (
    EventTypeID INT(11) NOT NULL AUTO_INCREMENT,
    EventType VARCHAR(256) NOT NULL,
    PRIMARY KEY (EventTypeID)
);

DROP TABLE IF EXISTS Events;
CREATE TABLE Events (
    EventID INT(11) NOT NULL AUTO_INCREMENT,
    EventName VARCHAR(256) NOT NULL,
    EventDescription LONGTEXT NOT NULL,
    TimeStart DATETIME NOT NULL,
    TimeEnd DATETIME NOT NULL,
    EventTypeID INT(11) NOT NULL,
    CreatedByUserID INT(11) NOT NULL,
    TimeCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TimeLastUpdated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (EventID),
    FOREIGN KEY (EventTypeID) REFERENCES EventType(EventTypeID),
    FOREIGN KEY (CreatedByUserID) REFERENCES Users(UserID)
);

DROP TABLE IF EXISTS LocationType;
CREATE TABLE LocationType (
    LocationTypeID INT(11) NOT NULL AUTO_INCREMENT,
    LocationType VARCHAR(256) NOT NULL,
    PRIMARY KEY (LocationTypeID)
);

DROP TABLE IF EXISTS Location;
CREATE TABLE Location (
    LocationID INT(11) NOT NULL AUTO_INCREMENT,
    LocationTypeID INT(11) NOT NULL,
    AddressID INT(11) NOT NULL,
    PRIMARY KEY (LocationID),
    FOREIGN KEY (LocationTypeID) REFERENCES LocationType(LocationTypeID),
    FOREIGN KEY (AddressID) REFERENCES LocationAddress(LocationAddressID)
);

DROP TABLE IF EXISTS Bookings;
CREATE TABLE Bookings (
    BookingID INT(11) NOT NULL AUTO_INCREMENT,
    DateBooked TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UserID INT(11) NOT NULL,
    EventID INT(11) NOT NULL,
    PRIMARY KEY (BookingID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (EventID) REFERENCES Events(EventID)
);

DROP TABLE IF EXISTS UserSessions;
CREATE TABLE UserSessions (
    UserSessionID INT(11) NOT NULL AUTO_INCREMENT,
    SessionData LONGTEXT NOT NULL,
    UserID INT(11) NOT NULL,
    PRIMARY KEY (UserSessionID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

DROP TABLE IF EXISTS UserLoginLogs;
CREATE TABLE UserLoginLogs (
	LoginLogID INT(11) NOT NULL AUTO_INCREMENT,
	UserID INT(11) NOT NULL,
	LoginCompleted BOOLEAN,
	LoginTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (LoginLogID),
	FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

DROP TABLE IF EXISTS AccessControl;
CREATE TABLE AccessControl (
    AccessControlID INT(11) NOT NULL AUTO_INCREMENT,
    UserID INT(11) NOT NULL,
    EventID INT(11) NOT NULL,
    ModificationFlag BOOLEAN,
    PRIMARY KEY (AccessControlID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (EventID) REFERENCES Events(EventID)
);

/* Insert application data */
INSERT INTO Country (CountryName) VALUES ('England');
INSERT INTO Country (CountryName) VALUES ('Wales');
INSERT INTO Country (CountryName) VALUES ('Scotland');
INSERT INTO Country (CountryName) VALUES ('Northern Ireland');
INSERT INTO Country (CountryName) VALUES ('Republic of Ireland');
INSERT INTO Country (CountryName) VALUES ('Albania');
INSERT INTO Country (CountryName) VALUES ('Andorra');
INSERT INTO Country (CountryName) VALUES ('Armenia');
INSERT INTO Country (CountryName) VALUES ('Austria');
INSERT INTO Country (CountryName) VALUES ('Azerbaijan');
INSERT INTO Country (CountryName) VALUES ('Belarus');
INSERT INTO Country (CountryName) VALUES ('Belgium');
INSERT INTO Country (CountryName) VALUES ('Bosnia and Herzegovina');
INSERT INTO Country (CountryName) VALUES ('Bulgaria');
INSERT INTO Country (CountryName) VALUES ('Croatia');
INSERT INTO Country (CountryName) VALUES ('Cyprus');
INSERT INTO Country (CountryName) VALUES ('Czech Republic');
INSERT INTO Country (CountryName) VALUES ('Denmark');
INSERT INTO Country (CountryName) VALUES ('Estonia');
INSERT INTO Country (CountryName) VALUES ('Finland');
INSERT INTO Country (CountryName) VALUES ('France');
INSERT INTO Country (CountryName) VALUES ('Georgia');
INSERT INTO Country (CountryName) VALUES ('Germany');
INSERT INTO Country (CountryName) VALUES ('Greece');
INSERT INTO Country (CountryName) VALUES ('Hungary');
INSERT INTO Country (CountryName) VALUES ('Iceland');
INSERT INTO Country (CountryName) VALUES ('Italy');
INSERT INTO Country (CountryName) VALUES ('Kazakhstan');
INSERT INTO Country (CountryName) VALUES ('Kosovo');
INSERT INTO Country (CountryName) VALUES ('Latvia');
INSERT INTO Country (CountryName) VALUES ('Liechtenstein');
INSERT INTO Country (CountryName) VALUES ('Lithuania');
INSERT INTO Country (CountryName) VALUES ('Luxembourg');
INSERT INTO Country (CountryName) VALUES ('Malta');
INSERT INTO Country (CountryName) VALUES ('Moldova');
INSERT INTO Country (CountryName) VALUES ('Monaco');
INSERT INTO Country (CountryName) VALUES ('Montenegro');
INSERT INTO Country (CountryName) VALUES ('Netherlands');
INSERT INTO Country (CountryName) VALUES ('North Macedonia (formerly Macedonia)');
INSERT INTO Country (CountryName) VALUES ('Norway');
INSERT INTO Country (CountryName) VALUES ('Poland');
INSERT INTO Country (CountryName) VALUES ('Portugal');
INSERT INTO Country (CountryName) VALUES ('Romania');
INSERT INTO Country (CountryName) VALUES ('Russia');
INSERT INTO Country (CountryName) VALUES ('San Marino');
INSERT INTO Country (CountryName) VALUES ('Serbia');
INSERT INTO Country (CountryName) VALUES ('Slovakia');
INSERT INTO Country (CountryName) VALUES ('Slovenia');
INSERT INTO Country (CountryName) VALUES ('Spain');
INSERT INTO Country (CountryName) VALUES ('Sweden');
INSERT INTO Country (CountryName) VALUES ('Switzerland');
INSERT INTO Country (CountryName) VALUES ('Turkey');
INSERT INTO Country (CountryName) VALUES ('Ukraine');
INSERT INTO Country (CountryName) VALUES ('Vatican City');

INSERT INTO City (CityName, CountryID) VALUES ('Aberdeen', 3);
INSERT INTO City (CityName, CountryID) VALUES ('Armagh', 4);
INSERT INTO City (CityName, CountryID) VALUES ('Bangor', 2);
INSERT INTO City (CityName, CountryID) VALUES ('Bath', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Belfast', 4);
INSERT INTO City (CityName, CountryID) VALUES ('Birmingham', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Bradford', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Brighton & Hove', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Bristol', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Cambridge', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Canterbury', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Cardiff', 2);
INSERT INTO City (CityName, CountryID) VALUES ('Carlisle', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Chelmsford', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Chester', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Chichester', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Coventry', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Derby', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Derry', 4);
INSERT INTO City (CityName, CountryID) VALUES ('Dundee', 3);
INSERT INTO City (CityName, CountryID) VALUES ('Edinburgh', 3);
INSERT INTO City (CityName, CountryID) VALUES ('Ely', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Exeter', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Glasgow', 3);
INSERT INTO City (CityName, CountryID) VALUES ('Gloucester', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Hereford', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Inverness', 3);
INSERT INTO City (CityName, CountryID) VALUES ('Kingston upon Hull', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Lancaster', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Leeds', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Leicester', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Lichfield', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Lincoln', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Lisburn', 4);
INSERT INTO City (CityName, CountryID) VALUES ('Liverpool', 1);
INSERT INTO City (CityName, CountryID) VALUES ('London', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Manchester', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Newcastle upon Tyne', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Newport', 2);
INSERT INTO City (CityName, CountryID) VALUES ('Newry', 4);
INSERT INTO City (CityName, CountryID) VALUES ('Norwich', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Nottingham', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Oxford', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Perth', 3);
INSERT INTO City (CityName, CountryID) VALUES ('Peterborough', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Plymouth', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Portsmouth', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Preston', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Ripon', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Salford', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Salisbury', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Sheffield', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Southampton', 1);
INSERT INTO City (CityName, CountryID) VALUES ('St Asaph', 2);
INSERT INTO City (CityName, CountryID) VALUES ('St Davids', 2);
INSERT INTO City (CityName, CountryID) VALUES ('Stirling', 3);
INSERT INTO City (CityName, CountryID) VALUES ('Stoke-on-Trent', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Sunderland', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Swansea', 2);
INSERT INTO City (CityName, CountryID) VALUES ('Truro', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Wakefield', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Wells', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Westminster', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Winchester', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Wolverhampton', 1);
INSERT INTO City (CityName, CountryID) VALUES ('Worcester', 1);
INSERT INTO City (CityName, CountryID) VALUES ('York', 1);

INSERT INTO UserType (UserType) VALUES ('Guest');
INSERT INTO UserType (UserType) VALUES ('Normal');
INSERT INTO UserType (UserType) VALUES ('Administrator');

INSERT INTO LocationType (LocationType) VALUES ('Village Hall');
INSERT INTO LocationType (LocationType) VALUES ('Church');
INSERT INTO LocationType (LocationType) VALUES ('Convention Center');
INSERT INTO LocationType (LocationType) VALUES ('School');
INSERT INTO LocationType (LocationType) VALUES ('Public House');
INSERT INTO LocationType (LocationType) VALUES ('Offices');

INSERT INTO Currency (CurrencyName, CurrencyISOCode) VALUES ('Great British Pound Sterling' ,'GBP');
INSERT INTO Currency (CurrencyName, CurrencyISOCode) VALUES ('United States Dollar', 'USD');
INSERT INTO Currency (CurrencyName, CurrencyISOCode) VALUES ('European Euro', 'EUR');
INSERT INTO Currency (CurrencyName, CurrencyISOCode) VALUES ('Russian Ruble', 'RUB');
INSERT INTO Currency (CurrencyName, CurrencyISOCode) VALUES ('Albanian Lek', 'ALL');
INSERT INTO Currency (CurrencyName, CurrencyISOCode) VALUES ('Danish Krone', 'DZK');

INSERT INTO EventType (EventType) VALUES ('Wedding');
INSERT INTO EventType (EventType) VALUES ('Gig');
INSERT INTO EventType (EventType) VALUES ('Party');
INSERT INTO EventType (EventType) VALUES ('Film Showing');
INSERT INTO EventType (EventType) VALUES ('Coffee Morning');
INSERT INTO EventType (EventType) VALUES ('Social Gathering');
INSERT INTO EventType (EventType) VALUES ('Opera');
INSERT INTO EventType (EventType) VALUES ('Community Event');
INSERT INTO EventType (EventType) VALUES ('Trade Show');
INSERT INTO EventType (EventType) VALUES ('Reunion');
INSERT INTO EventType (EventType) VALUES ('Conference');

INSERT INTO Users VALUES (1, 'Matt', '$2y$12$tvU92KX9CwyEhTi6TVt9q.x08A5JTyCIM6ZA6aMCp/2sQ/O0rqvam', 'contact@matttrim.com', 'Matt', 'Trim', 2, NULL, '2020-04-14 00:44:20');
INSERT INTO Users VALUES (2, 'JohnnyTest', '$2y$12$tvU92KX9CwyEhTi6TVt9q.x08A5JTyCIM6ZA6aMCp/2sQ/O0rqvam', 'john@test.com', 'John', 'Smith', 2, NULL, '2020-04-14 14:10:06');
INSERT INTO Users VALUES (3, 'Admin', '$2y$12$tvU92KX9CwyEhTi6TVt9q.x08A5JTyCIM6ZA6aMCp/2sQ/O0rqvam', 'admin@admin.com', 'Administrator', 'Administrative', 3, NULL, '2020-04-15 16:00:20');

INSERT INTO Events (EventID, EventName, EventDescription, TimeStart, TimeEnd, EventTypeID, CreatedByUserID) VALUES (1, 'Arctic Monkeys', 'This is a gig by the Arctic Monkeys. Come on down!', '2022-10-12 10:30:00', '2022-10-12 14:30:00', 2, 1);
INSERT INTO Events (EventID, EventName, EventDescription, TimeStart, TimeEnd, EventTypeID, CreatedByUserID) VALUES (2, 'Sandra and Steve', 'This is the lovely wedding of Sandra and Steve.', '2021-11-12 13:30:00', '2021-11-12 19:30:00', 1, 2);
