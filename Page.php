<?php
/**
 * User: MrRadthorne
 * Date: 28-10-14
 * Time: 11:37
 */
 

class Page
{
  /**
   * @var Database
   */
  var $db;
  /**
   * @var String
   */
  var $title;
  /**
   * @var String
   */
  var $name;
  /**
   * @var Array
   */
  var $rows;

  function Page($page, $username = "")
  {
    $this->db = Database::getInstance();
    $array = $this->db->getPage($page, $username);
    $this->name = $array["name"];
    $this->title = $array["title"];
    $this->rows = $array["rows"];
    if(empty($this->rows))
    {
      $username = preg_replace("/(.+) \-.*/", "$1", $this->title);
      $this->title = "The name '$username' does not exist in the database.";
    }
  }

  /**
   * @return String
   */
  public
  function getTitle()
  {
    return $this->title;
  }

  /**
   * @return Array
   */
  public
  function getRows()
  {
    return $this->rows;
  }

  /**
   * @return String
   */
  public
  function getName()
  {
    return $this->name;
  }


} 