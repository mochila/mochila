use mochila_db;

CREATE TABLE DAGRS (DAGR_GUID VARCHAR(35), DAGR_TITLE VARCHAR(50), DAGR_DATE DATE, DAGR_SIZE INT, DAGR_FILE_TYPE VARCHAR(15), DAGR_FILE_LOC VARCHAR(100), DAGR_AUTHOR VARCHAR(50), DAGR_PARENT_GUID VARCHAR(35));

CREATE TABLE CHILD_DAGRS (PARENT_GUID VARCHAR(35), CHILD_GUID VARCHAR(35));

CREATE TABLE TAGS (DAGR_GUID VARCHAR(35), TAG_TITLE VARCHAR(20));
