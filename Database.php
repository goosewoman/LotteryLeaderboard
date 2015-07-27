<?php


  class Database
  {
    protected static $instance;

    public static function getInstance() {
      if (!self::$instance) {
        self::$instance = new Database();
        }
      return self::$instance;
    }

    /* @var $db PDO */
    private $db;

    function Database()
    {
      $this->init();
    }

    function init()
    {
      $username = "blah";
      $password = "@blah";
      $host     = "localhost";
      $dbname   = "blah";

      $options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );

      try
      {
        $this->db = new PDO( "mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options );
      }
      catch ( PDOException $ex )
      {
        die( "Failed to connect to the database" );
      }

      $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      $this->db->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

      if ( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() )
      {
        function undo_magic_quotes_gpc( &$array )
        {
          foreach ( $array as &$value )
          {
            if ( is_array( $value ) )
            {
              undo_magic_quotes_gpc( $value );
            }
            else
            {
              $value = stripslashes( $value );
            }
          }
        }

        undo_magic_quotes_gpc( $_POST );
        undo_magic_quotes_gpc( $_GET );
        undo_magic_quotes_gpc( $_COOKIE );
      }
    }

    function getPage( $page, $username = "" )
    {
      if($username != "")
      {
        $page = "user";
      }
      $queryData = $this->getQuery($page);
      if(!$queryData)
      {
        return false;
      }
      $page = array();
      $page['title'] = str_replace("{name}", $username, $queryData['Title']);
      $page['name'] = $queryData['name'];
      $query = $queryData['query'];
      try
      {
        $stmt = $this->db->prepare( $query );
        if($username != "")
        {
          $stmt->bindParam(":username", $username);
        }
        $stmt->execute();
      }
      catch ( PDOException $ex )
      {
        die( "Failed to run query." );
      }
      $rows = $stmt->fetchAll();
      $page['rows'] = $rows;
      return $page;
    }

    function getQuery( $page )
    {
      if(!$this->queryExists($page))
      {
        return false;
      }

      $query = "SELECT * FROM queries WHERE name = :page";

      try
      {
        $stmt = $this->db->prepare( $query );
        $stmt->bindParam( ':page', $page );
        $stmt->execute();
      }
      catch ( PDOException $ex )
      {
        die( "Failed to run query." );
      }
      $query = $stmt->fetch();
      return empty($query) ? false : $query;
    }

    function queryExists($page)
    {
      $query = "SELECT 1 FROM queries WHERE name = :page";

      try
      {
        $stmt = $this->db->prepare( $query );
        $stmt->bindParam( ':page', $page );
        $stmt->execute();
      }
      catch ( PDOException $ex )
      {
        die( "Failed to run query." );
      }
      $result = $stmt->fetch();
      return !empty($result);
    }

    public function getUsers()
    {
      $query = "SELECT players.username
FROM `lottery`
RIGHT JOIN players ON lottery.UUID = players.UUID
WHERE lottery.UUID IS NOT NULL
GROUP BY players.username
ORDER BY players.username ASC;";
      try
      {
        $stmt = $this->db->prepare( $query );
        $stmt->execute();
      }
      catch ( PDOException $ex )
      {
        die( "Failed to run query.");
      }
      return $stmt->fetchAll();
    }
  }