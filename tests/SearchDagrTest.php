<?php
require_once("/home/nerraw/mochila/searchFunctions.php");

class SearchDagrTest extends PHPUnit_Framework_TestCase
{
    
    protected $db;
    
    protected function setUp(){
        $this->db = new mysqli("localhost", "root", "dude1313", "mochila_db");
        $statement = $this->db->prepare("insert into `DAGRS` values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $tag_stmnt = $this->db->prepare("insert into `TAGS` values(?,?)");
        $guid = "1";
        $title = "Title";
        $date = "2011-1-2";
        $size = 100;
        $type = "parent";
        $file_type = "parent";
        $file_loc = NULL;
        $author = "warren";
        $parent_guid = NULL;
        $tags = "tag1";
        $statement->bind_param("sssisssss", $guid, $title , $date, $size, $type, $file_type, $file_loc, $author, $parent_guid);
        $tag_stmnt->bind_param("ss", $guid, $tags);
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "2";
        $title = "Google";
        $date = "2012-12-13";
        $size = 188;
        $type = "url";
        $file_loc = "https://www.google.com";
        $file_type = "url";
        $author = "Stephen";
        $parent_guid = "1";
        $tags = "tag2";
        
        
        $statement->execute();
        $tag_stmnt->execute();
        $tag = "another tag";
        $tag_stmnt->execute();
        
        $guid = "3";
        $title = "example.pdf";
        $date = "2013-12-31";
        $size = 1200;
        $type = "file";
        $file_type = "odt";
        $file_loc = "example.odt";
        $author = "subsitute";
        $parent_guid = "1";
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "4";
        $title = "Sub container";
        $date = "2013-10-31";
        $size = 130;
        $type = "parent";
        $file_type = "parent";
        $file_loc = NULL;
        $author = "nelson";
        $parent_guid = "1";
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "5";
        $title = "subexample.odt";
        $date = "2013-9-1";
        $size = 13;
        $type = "file";
        $file_type = "odt";
        $file_loc = "subexample.odt";
        $author = "Joe Long";
        $parent_guid = "4";
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "6";
        $title = "Top Level";
        $date = "2013-3-15";
        $size = 130;
        $type = "file";
        $file_type = "odt";
        $file_loc = "topLevel.odt";
        $author = "Joe Long";
        $parent_guid = NULL;
        $tags = "tag3";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        $guid = "7";
        $title = "Sterile";
        $date = "2011-3-15";
        $size = 133;
        $type = "parent";
        $file_type = "parent";
        $file_loc = NULL;
        $author = "Joseph";
        $parent_guid = NULL;
        $tags = "tag5";
        
        $statement->execute();
        $tag_stmnt->execute();
        
        //        $this->db->close();
    }
    
    public function tearDown(){
//        $this->db->prepare("delete from `DAGRS`")->execute();
//        $this->db->prepare("truncate `TAGS`")->execute();
//        $this->db->close();
        //print "Tear down Called";
    }
//    
//    public function testFreeSearch() {
//        $dagr_list = free_search("sub");
//        $this->assertEquals(sizeof($dagr_list), 3, "Incorrect number of GUIDS returned");
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("3", "4", "5"), "GUID not 3, 4, or 5");
//        }
//        
//    }
//    
//    public function testFreeSearchEmpty() {
//        $dagr_list = free_search("XWXW");
//        $this->assertEquals(sizeof($dagr_list), 0, "Returning extraneous DAGRS");
//        
//        $dagr_list = free_search("");
//        $this->assertEquals(sizeof($dagr_list), 0, "Returning extraneous DAGRS");
//        
//    }
//    
//    
//    public function testAuthorSearch(){
//        $dagr_list = author_search("Joe Long");
//        $this->assertEquals(sizeof($dagr_list), 2);
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("5", "6"));
//        }
//        
//    }
//    
//    public function testDateSearch(){
//        $dagr_list = date_search("2013-12-31");
//        $this->assertEquals(sizeof($dagr_list), 1);
//        $this->assertEquals($dagr_list[0]["guid"], "3", "Date search failed. Date not found");
//        
//        $dagr_list = date_search("1995-12-31");
//        $this->assertEquals(sizeof($dagr_list), 0, "Date Search failed. Date should not have been found");                                     
//        
//    }
//    
//    public function testTypeSearch(){
//        $dagr_list = type_search("file");
//        $this->assertEquals(sizeof($dagr_list), 3, "File search dagr_list fail");
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("3", "5", "6"), "Search 'file' fail");
//        }
//        
//        
//        $dagr_list = type_search("odt");
//        $this->assertEquals(sizeof($dagr_list), 3, "odt search dagr_list fail");
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("3", "5", "6"), "Search 'odt' fail");
//        }
//        
//        $dagr_list = type_search("adfadf");
//        $this->assertEquals(sizeof($dagr_list), 0, "Non existent search dagr_list fail");
//        
//    }
//    
//    public function testTitleSearch(){
//        $dagr_list = title_search("Top Level");
//        $this->assertEquals(sizeof($dagr_list), 1);
//        $this->assertEquals($dagr_list[0]["guid"], "6");
//        
//        $dagr_list = title_search("");
//        $this->assertEquals(sizeof($dagr_list), 0);
//        
//        
//    }
//    
//    public function testOrphanSearch(){
//        $dagr_list = orphan_search();
//        $this->assertEquals(sizeof($dagr_list), 3);
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("1", "6", "7"));
//        }
//        
//    }
//    
//    public function testReachSearch() {
//        $dagr_list = reach_search("title");
//        $this->assertEquals(5, sizeof($dagr_list));
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("1","2", "3", "4", "5"));
//        }
//        
//        $dagr_list = reach_search("sub container");
//        $this->assertEquals(sizeof($dagr_list), 2);
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("4", "5"));
//        }
//        
//        
//        $dagr_list = reach_search("");
//        $this->assertEquals(sizeof($dagr_list), 0);
//    }
//    
//    
//    
//    public function testSizeSearch() {
//        $dagr_list = size_search(130);
//        $this->assertEquals(2, sizeof($dagr_list));
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("4", "6"));
//        }
//        
//        $dagr_list = size_search(0);
//        $this->assertEquals(0, sizeof($dagr_list));
//    }
//    
//    public function testSterileSearch() {
//        $dagr_list = sterile_search();
//        $this->assertEquals(1, sizeof($dagr_list));
//        $this->assertEquals($dagr_list[0]["guid"], "7");
//    }
//    
//    public function testTagSearch() {
//        $dagr_list =  tag_search("tag3");
//        $this->assertEquals(4, sizeof($dagr_list));
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("3", "4", "5", "6"));
//        }
//        
//        $dagr_list =  tag_search("Not a tag");
//        $this->assertEquals(0, sizeof($dagr_list));
//        
//        
//    }
//    
//    public function testTimeRangeSearch() {
//        $dagr_list = time_range_search("2011-1-2", "2014-1-1");
//        $this->assertEquals(7, sizeof($dagr_list));
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("1","2","3", "4", "5", "6", "7"));
//        }
//        
//        $dagr_list = time_range_search("2013-9-1", "2013-12-31");
//        $this->assertEquals(3, sizeof($dagr_list));
//        foreach($dagr_list as $dagr){
//            $this->assertContains($dagr["guid"], array("3", "4", "5", "6", "7"));
//        }
//
//    }
    
    public function testEmpty(){
    }
    
}

?>