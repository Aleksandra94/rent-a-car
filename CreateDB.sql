drop database if exists rent_a_car;
CREATE DATABASE rent_a_car;
drop table if exists rent_a_car.category;
create table rent_a_car.category (
	id integer not null  auto_increment,
    name varchar(150) not null,
    price double not null,
    parent_id integer,
    PRIMARY KEY(id),
    FOREIGN KEY(parent_id) REFERENCES category(id)
);
drop table if exists rent_a_car.car;
create table rent_a_car.car (
	id integer not null auto_increment,
    registration_licence varchar(15) not null,
    brand varchar(150) not null,
    model varchar(150) not null,
    manufacture_date date not null,
    car_description varchar(255),
    properties varchar(255),
    category_id integer,
    slug varchar(255),
    PRIMARY KEY(id),
	FOREIGN KEY(category_id) REFERENCES category(id)
);
drop table if exists rent_a_car.reservation;
create table rent_a_car.reservation (
	id integer not null auto_increment,
    reserved_from date not null,
    reserved_to date not null,
    car_id integer,
    PRIMARY KEY(id),
    FOREIGN KEY(car_id) REFERENCES car(id)
);