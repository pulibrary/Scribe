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
                <p><?php echo __('Proudly powered by <a href="http://omeka.org">Omeka</a>.'); ?></p>
            </div>

            <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>

        </footer>

    </div><!-- end wrap -->
</body>
</html>
