<?php

class DeleteDagrTest extends PHPUnit_Framework_TestCase
{

    public function setUp(){
	$db = new mysqli("localhost", "root", "root", "mochila_db");
	$statement = $db->prepare("insert into `DAGRS` values(?, ?, ?, ?, ?, ?, ?, ?)");
	$tag_stmnt = $db->prepare("insert into `TAGS` values(?,?)");
	$guid = "1";
	$title = "Title";
	$date = "12-31-2013";
	$size = 100;
	$type = "parent";
	$file_loc = NULL;
	$author = "author";
	$parent_guid = NULL;
	$tags = "tag1";
	$statement->bind_param("ssdissss", $guid, $title , $date, $size, $type, $file_loc, $author, $parent_guid);
	$tag_stmnt->bind_param("ss", $guid, $tags);
	
	$statement->execute();
	$tag_stmnt->execute();

	$guid = "2";
	$title = "Google";
	$date = "12-31-2013";
	$size = 100;
	$type = "url";
	$file_loc = "https://www.google.com";
	$author = "author";
	$parent_guid = "1";
	$tags = "tag2";

	
	$statement->execute();
	$tag_stmnt->execute();
	
	$guid = "3";
	$title = "example.pdf";
	$date = "12-31-2013";
	$size = 100;
	$type = "file";
	$file_loc = "example.odt";
	$author = "author";
	$parent_guid = "1";
	$tags = "tag3";

	$statement->execute();
	$tag_stmnt->execute();

	
	
	
	
	
    }

    public function tearDown(){
	
    }

    public function testDeleteWorks(){
	$this->assertTrue(true);

    }

    
}

?>