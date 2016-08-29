        </article>

        <footer id="footer">
<!--
            <div class="footer">
                <a href="<?php echo WEB_ROOT; ?>"><img src="<?php echo img('pul_logo.png'); ?>"  alt="Princeton University Library: Transcribing the West" title="Princeton University Library: Transcribing the West"></a>
            </div>
-->
            <div id="footer-text">
                <?php echo get_theme_option('Footer Text'); ?>
                <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
                    <p><?php echo $copyright; ?></p>
                <?php endif; ?>
                <div class="pu"><a href="http://www.princeton.edu" class="pu-logo"><img src="/themes/Princeton/images/pu_logo_trans.png" alt="Princeton University" class="university-logo"></a></div><p><?php echo __('&copy; 2016 The Trustees of Princeton University. All rights reserved.'); ?></p>
            </div>

            <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>

        </footer>

    </div><!-- end wrap -->
</body>
</html>
