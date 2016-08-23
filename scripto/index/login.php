<?php
$titleArray = array(__('Transcriptions at Princeton University Library'), __('Login'));
$title = implode(' | ', $titleArray);
$head = array('title' => html_escape($title));
echo head($head);
?>
<?php if (!is_admin_theme()): ?>
<h1><?php echo $head['title']; ?></h1>
<?php endif; ?>
<div id="primary">
    <?php echo flash(); ?>

    <div id="scripto-login" class="scripto">
        <!-- navigation -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="<?php echo html_escape(url('scripto/index/login')); ?>"><?php echo __('Log in'); ?></a></li>
<!--
    mediawiki doesn't offer account creation through their API. You must link directly to the page in your installation
    /my_mediawiki_install_directory/index.php5?title=Special:UserLogin&type=signup
-->
<!--
	    
            <li><a href="http://diywiki.princeton.edu/index.php?title=Special:UserLogin&type=signup" target="_blank"><?php echo __('Create an account'); ?></a></li>
       
            <li><a href="<?php echo html_escape(url('scripto/recent-changes')); ?>"><?php echo __('Recent changes'); ?></a></li>
        -->
           <li><a href="http://diywiki.princeton.edu/index.php/Special:PasswordReset" target="_blank"><?php echo __('Reset Your Password'); ?></a></li>
        </ul>
        <p><?php echo __(
            'Enrolled students in HIS 374: History of the American West Log in using username and password you created to access your account.'
        ); ?></p>

        <!-- login -->
        <form action="<?php echo html_escape(url('scripto/index/login')); ?>" method="post">
            <div class="field">
                <label for="scripto_mediawiki_username"><?php echo __('Username'); ?></label>
                    <div class="inputs">
                    <?php echo $this->formText('scripto_mediawiki_username', null, array('size' => 18)); ?>
                </div>
            </div>
            <div class="field">
                <label for="scripto_mediawiki_password"><?php echo __('Password'); ?></label>
                    <div class="inputs">
                    <?php echo $this->formPassword('scripto_mediawiki_password', null, array('size' => 18)); ?>
                </div>
            </div>
            <?php echo $this->formHidden('scripto_redirect_url', $this->redirectUrl); ?>
            <?php echo $this->formSubmit('scripto_mediawiki_login', __('Login'), array('class' => 'btn btn-primary', 'style' => 'display:inline; float:none;')); ?>
        </form>
    </div><!-- #scripto-login -->
</div>
<?php echo foot(); ?>
