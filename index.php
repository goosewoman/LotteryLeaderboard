<?php
  require_once "Database.php";
  require_once "Page.php";
  $request = preg_replace( "/\/lottery\//", "", $_SERVER['REQUEST_URI'], 1 );
  $request = str_replace( "/", "-", $request );
  $default = "latest";
  if ( $request == "" )
  {
    $request = $default;
  }
  $type = "regular";
  if ( strpos( $request, '1ticket' ) !== FALSE )
  {
    $type = "1ticket";
  }
  if ( $request == 'latest' )
  {
    $type = "latest";
  }
  $username = "";
  if ( strpos( $request, 'user' ) !== FALSE )
  {
    $type = "user";
    $username = str_replace("user-", "", $request);
  }

  $page = new Page( $request, $username);
  if(isset($_GET['srch-term']))
  {
    header("Location: /lottery/user/".$_GET['srch-term']);
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title><?php echo $page->getTitle(); ?></title>
    <link rel="icon" type="image/png" href="http://excelsion.net/favicon.ico">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/darkly/bootstrap.min.css" rel="stylesheet">
    <link href="/lottery/css/typeahead.css" rel="stylesheet">
  </head>
  <body>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/lottery/">Lottery Leaderboard</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
<!--            <li>-->
<!--              <a href="http://excelsion.net/xen/">Forums</a>-->
<!--            </li>-->
            <li class="dropdown <?php echo $type == "regular" ? "active" : ""; ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i>&nbsp;Regular Lotteries<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/lottery/regular/mostwins"><i class="fa fa-star-half-o "></i>&nbsp;Most Wins</a></li>
                <li><a href="/lottery/regular/bigwins"><i class="fa fa-star"></i>&nbsp;Biggest Wins</a></li>
                <li><a href="/lottery/regular/totalwins"><i class="fa fa-trophy"></i>&nbsp;Total Winnings</a></li>
              </ul>
            </li>
            <li class="dropdown <?php echo $type == "1ticket" ? "active" : ""; ?>n">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i>&nbsp;1 Ticket Lotteries<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/lottery/1ticket/mostwins"><i class="fa fa-star-half-o "></i>&nbsp;Most Wins</a></li>
                <li><a href="/lottery/1ticket/bigwins"><i class="fa fa-star"></i>&nbsp;Biggest Wins</a></li>
                <li><a href="/lottery/1ticket/totalwins"><i class="fa fa-trophy"></i>&nbsp;Total Winnings</a></li>
              </ul>
            </li>
            <li class="<?php echo $type == "latest" ? "active" : ""; ?>">
              <a href="/lottery/latest"><i class="fa fa-exclamation"></i>&nbsp;Latest Lotteries</a>
            </li>
            <li>
              <div>
                <form class="navbar-form" role="search">
                  <div class="prefetch">
                    <input type="text" class="form-control typeahead" placeholder="Search User" name="srch-term" id="srch-term">
                  </div>
                </form>
              </div>
            </li>
          </ul>

        </div>
      </div>
    </div>

    <div class="container theme-showcase" role="main">

      <div class="jumbotron" style="text-align: center;">
        <br/>
        <br/>

        <img src="http://excelsion.net/xen/styles/excelsion%20custom/header/banner%20prime.png" class="img-responsive" alt="image" title="image" style="display: inline-block; height: 150px;">
      </div>

      <div class="page-header" style="text-align: center;">
        <h1 style="display: inline-block;"><?php echo $page->getTitle(); ?></h1>
      </div>
      <?php
        $count = FALSE;
        if ( strpos( $page->getName(), 'mostwins' ) !== FALSE )
        {
          $count = TRUE;
        }
      ?>
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <table class="table table-striped table-bordered">
            <thead>
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Tickets</th>
              <th><?php echo $count ? "Total Wins" : "Winnings" ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
              $dollar = $count ? "" : "$";
              $i = 0;
              foreach ( $page->getRows() as $row )
              {
                foreach ( $row as $key => $value )
                {
                  if ( $key == "COUNT(lottery.winnings)" || $key == "SUM(lottery.winnings)" )
                  {
                    $key = "winnings";
                  }
                  else if ( $key == "COUNT(lottery.totaltickets)" || $key == "SUM(lottery.totaltickets)" )
                  {
                    $key = "totaltickets";
                  }
                  $row[$key] = $value;
                }
                $i++;
                ?>
                <tr>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $row['username']; ?></td>
                  <td><?php echo $row['totaltickets']; ?></td>
                  <td><?php echo $dollar; echo $row['winnings']; ?></td>
                </tr>
              <?php
              }
            ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="footer">
        <div class="container">
          <p class="text-muted"><i class="fa fa-copyright"></i>&nbsp;Luuk Jacobs 2014</p>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="/lottery/js/typeahead.bundle.js"></script>
    <script src="/lottery/js/search.js?sdf"></script>
  </body>
</html>