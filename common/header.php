<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <!-- Stylesheets -->
    <!-- Le styles -->
    <?php
    queue_css_url('http://fonts.googleapis.com/css?family=Oxygen');
    queue_css_url('http://fonts.googleapis.com/css?family=Inconsolata');
    queue_css_file(array(
        'bootstrap',
        'font-awesome',
        'style',
        'ol',
    ));
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php
    // jQuery is enabled by default in Omeka and in most themes.
    // queue_js_url('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
    // queue_js_url('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
    queue_js_file(array(
        // 'bootstrap.min',
        'jquery.bxSlider.min',
    ));
    echo head_js();
    ?>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

<?php
    echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass));
    require_once getcwd().'/plugins/Scripto/libraries/Scripto.php';
    $scripto = ScriptoPlugin::getScripto();
?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo WEB_ROOT; ?>"><img src="<?php echo img('pul_logo.png'); ?>" alt="Princeton University Library: Transcribing the West" title="Princeton University Library: Transcribing the West" border="0"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo WEB_ROOT; ?>/items/browse">Browse Items</a></li>
                <li><a href="<?php echo WEB_ROOT; ?>/collections/browse">Browse Collections</a></li>
                <?php if ($scripto->isLoggedIn()): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $scripto->getUserName(); ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="<?php echo WEB_ROOT; ?>/scripto">Your Contributions</a></li>
                      <li><a href="<?php echo WEB_ROOT; ?>/scripto/watchlist">Your Watchlist</a></li>
                      <li><a href="<?php echo WEB_ROOT; ?>/scripto/recent-changes">Recent Changes</a></li>
                      <li><a href="<?php echo WEB_ROOT; ?>/scripto/logout">Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>

                <li>
                <a href="<?php echo WEB_ROOT; ?>/scripto/login"><strong>Sign in or register</strong></a>
                </li>

                <?php endif; ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

    <div id="wrap" class="container-fluid">

        <article>

            <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
