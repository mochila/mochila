drop database IF exists mochila_db;
create database mochila_db;

use mochila_db;

CREATE TABLE DAGRS (DAGR_GUID VARCHAR(40), DAGR_TITLE VARCHAR(80), DAGR_DATE DATE, DAGR_SIZE INT, DAGR_TYPE VARCHAR(10), DAGR_FILE_TYPE VARCHAR(15), DAGR_FILE_LOC VARCHAR(150), DAGR_AUTHOR VARCHAR(80), DAGR_PARENT_GUID VARCHAR(40));

CREATE TABLE CHILD_DAGRS (PARENT_GUID VARCHAR(40), CHILD_GUID VARCHAR(40));

CREATE TABLE TAGS (DAGR_GUID VARCHAR(40), TAG_TITLE VARCHAR(35));
