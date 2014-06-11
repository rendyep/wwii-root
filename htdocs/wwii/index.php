<?php
    // BEGIN: TITIP
    error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);

    $includePaths = array(
        __DIR__ . '/../../vendor',
        __DIR__ . '/../../vendor/wwii',
    );
    set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $includePaths));

    function __autoload($className)
    {
        if ($className === 'PEAR_Error') {
            return;
        }

        include($className . '.php');
    }

    include_once('init_autoload.php');

    if (isset($_GET['bypass']) || isset($_GET['print'])) {
        ob_start();
        session_start();
        require_once('bootstrap.php');
        ob_end_flush();
        return;
    }
    // END: TITIP

      ob_start();
      require_once("./libs/sesschk.php");
      require_once("./libs/lib_konci.php");
      include_once("./libs/myfunctions.php");
      include_once("./libs/agent.php");
      header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
        <meta name="author" content="Gatot S" />
        <title>
            <?php include("./includes/title.php");?>
        </title>
        <link href="./styles/arina.css" rel="stylesheet" type="text/css"/>
        <link href="./styles/form.css" rel="stylesheet" type="text/css"/>
        <link href="./styles/topmenu.css" rel="stylesheet" type="text/css"/>
        <link href="./styles/leftmenu2.css" rel="stylesheet" type="text/css"/>
        <script src="./libs/leftmenu2.js" type="text/javascript"></script>
        <?php
            sessionBreak($_SESSION['arinaSess']);
        ?>
    </head>
    <?php //$agent->init(); ?>
    <body>
    <div id="head">
        <div class="left">PT. Woodworth Wooden </div>
        <div class="right">Online Reporting System</div>
    </div>
    <div id="container">
        <div id="left">
            <div class="boxed">
                <div class="title">
                    <?php include("./includes/cp.php");?>
                </div>
                <?php
                    include("./includes/leftmenu.php");
                ?>
            </div>
        </div>
        <div id="content">
            <div id="topnavcontainer">
                <?php include("./includes/topmenu.php"); ?>
            </div>
            <div id="thecontent">
                <h3><?php echo $pageTitle;?></h3>
                <!-- CONTENT FILTER /-->
                <?php
                    if(!isset($_GET['c'])||$_GET['c']==""){
                        include("./includes/home.php");
                    } else {
                        if(!file_exists("./includes/controller.php")){
                            include("./includes/404.php");
                        } else {
                            //~echo '<img src="./images/icons/$_GET[c].png" width="64"'
                            echo '<img src="./images/icons/wwii.jpg" width="150"'
                                . ' height="50" align="right" style="padding: 20px;margin-top: -60px" />';
                            //~echo "$_GET[c]";
                            include("./includes/controller.php");
                        }
                    }
                ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
ob_end_flush();
?>
