<?php
require_once("/home/nerraw/mochila/dagrDelete.php");


class DeleteDagrTest extends PHPUnit_Framework_TestCase    
{
    
    public function setUp(){
        $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
        $statement = $db->prepare("insert into `DAGRS` values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $tag_stmnt = $db->prepare("insert into `TAGS` values(?,?)");
        $guid = "1";
        $title = "Title";
        $date = "12-31-2013";
        $size = 100;
        $type = "parent";
        $file_type = "parent";
        $file_loc = NULL;
        $author = "author";
        $parent_guid = NULL;
        $tags = "tag1";
        $statement->bind_param("ssdisssss", $guid, $title , $date, $size, $type, $file_type, $file_loc, $author, $parent_guid);
        $tag_stmnt->bind_param("ss", $guid, $tags);
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "2";
        $title = "Google";
        $date = "12-31-2013";
        $size = 100;
        $type = "url";
        $file_loc = "https://www.google.com";
        $file_type = "url";
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
        $file_type = "odt";
        $file_loc = "example.odt";
        $author = "author";
        $parent_guid = "1";
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "4";
        $title = "Sub container";
        $date = "12-31-2013";
        $size = 100;
        $type = "parent";
        $file_type = "parent";
        $file_loc = NULL;
        $author = "author";
        $parent_guid = "1";
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "5";
        $title = "subexample.odt";
        $date = "12-31-2013";
        $size = 100;
        $type = "file";
        $file_type = "odt";
        $file_loc = "subexample.odt";
        $author = "author";
        $parent_guid = "4";
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "6";
        $title = "Top Level";
        $date = "12-31-2013";
        $size = 100;
        $type = "file";
        $file_type = "odt";
        $file_loc = "topLevel.odt";
        $author = "author";
        $parent_guid = NULL;
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        $db->close();
    }
    
    public function tearDown(){
     
//        $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
//        $db->prepare("delete from `DAGRS`")->execute();
//        $db->prepare("truncate `TAGS`")->execute();
//        $db->close();
//        print "Tear down Called";
    }
    
//    public function testSingularDeleteWorks(){
//        $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
//        singular_delete("1", $db);
//        $verify = $db->prepare("select `DAGR_GUID` from `DAGRS` where `DAGR_GUID`='1' or DAGR_PARENT_GUID='1'");
//        $verify->execute();
//        $verify->bind_result($res);
//        $results = array();
//        while($verify->fetch()){
//            $results[] = $res;
//        }
//        $this->assertEmpty($results, "Still contains DAGR");
//        
//        $verify = $db->prepare("select `DAGR_GUID` from `DAGRS` where `DAGR_GUID`='2' or `DAGR_GUID`='3'");
//        $verify->execute();
//        $verify->bind_result($res);
//        $results = array();
//        while($verify->fetch()){
//            $results[] = $res;
//        }
//        $this->assertNotEmpty($results, "Children parents were not updated");
//        $this->assertContains("2", $results, "Children parents were not updated");
//        $this->assertContains("3", $results, "Children parents were not updated");
//        
//        
//        
//        $verify = $db->prepare("select `TAG_TITLE` from `TAGS` where `DAGR_GUID`='1'");
//        $verify->execute();
//        $verify->bind_result($res);
//        $results = array();
//        while($verify->fetch()){
//            $results[] = $res;
//        }
//        
//        $this->assertEmpty($results, "Tags were not deleted");
//        $db->close();
//    }
//    
//    public function testRecursiveDeleteWorks(){
//        $db = new mysqli("localhost", "root", "dude1313", "mochila_db");
//        recursive_delete("1", $db);
//        $verify = $db->prepare("select `DAGR_GUID` from `DAGRS`");
//        $verify->execute();
//        $verify->bind_result($res);
//        $results = array();
//        while($verify->fetch()){
//            $results[] = $res;
//        }
//        
//        $this->assertEquals(1, sizeof($results), "Contains extra DAGRS");
//        
//        $verify = $db->prepare("select `DAGR_GUID` from `TAGS`");
//        $verify->execute();
//        $verify->bind_result($res);
//        $results = array();
//        while($verify->fetch()){
//            $results[] = $res;
//        }
//        
//        $this->assertEquals(1, sizeof($results), "Contains extra DAGRS");
//        
//        
//        $db->close();
//    }
//    
    public function testSetUp(){
        $this->assertTrue(true);
    }
    
}

?>