DROP TABLE IF EXISTS product;
create table product (
    id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name varchar(256),
    col varchar(10)) 
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
;