<?php
$titleArray = array(__('Princeton'), __('Transcribe Page'));
$head = array('title' => html_escape(implode(' | ', $titleArray)));
echo head($head);
if (get_option('scripto_image_viewer') == 'openseadragon') {
    // echo js_tag('ol');
    echo js_tag('openseadragon.min');
    // jQuery is enabled by default in Omeka and in most themes.
    // echo js_tag('jquery', 'javascripts/vendor');
}
?>
<script type="text/javascript">
jQuery(document).ready(function() {

    // Handle edit transcription page.
    //jQuery("#wrap").attr('class', 'container-fluid');

    jQuery('#scripto-transcription-page-edit').click(function() {
        jQuery('#scripto-transcription-page-edit').
            prop('disabled', true).
            text('<?php echo __('Editing transcription...'); ?>');
        jQuery.post(
            <?php echo js_escape(url('scripto/index/page-action')); ?>,
            {
                page_action: 'edit',
                page: 'transcription',
                item_id: <?php echo js_escape($this->doc->getId()); ?>,
                file_id: <?php echo js_escape($this->doc->getPageId()); ?>,
                wikitext: jQuery('#scripto-transcription-page-wikitext').val()
            },
            function(data) {
                jQuery('#scripto-transcription-page-edit').
                    prop('disabled', false).
                    text('<?php echo __('Save transcription'); ?>');
                jQuery('#scripto-transcription-page-html').html(data);
            }
        );
    });


    <?php if ($this->scripto->isLoggedIn()): ?>

    // Handle default un/watch page.
    <?php if ($this->doc->isWatchedPage()): ?>
    jQuery('#scripto-page-watch').
        data('watch', true).
        text('<?php echo __('Unwatch page'); ?>').
        css('float', 'none');
    <?php else: ?>
    jQuery('#scripto-page-watch').
        data('watch', false).
        text('<?php echo __('Watch page'); ?>').
        css('float', 'none');
    <?php endif; ?>

    // Handle un/watch page.
    jQuery('#scripto-page-watch').click(function() {
        if (!jQuery(this).data('watch')) {
            jQuery(this).prop('disabled', true).text('<?php echo __('Watching page...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'watch',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>
                },
                function(data) {
                    jQuery('#scripto-page-watch').
                        data('watch', true).
                        prop('disabled', false).
                        text('<?php echo __('Unwatch page'); ?>');
                }
            );
        } else {
            jQuery(this).prop('disabled', true).text('<?php echo __('Unwatching page...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'unwatch',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>
                },
                function(data) {
                    jQuery('#scripto-page-watch').
                        data('watch', false).
                        prop('disabled', false).
                        text('<?php echo __('Watch page'); ?>');
                }
            );
        }
    });

    <?php endif; // end isLoggedIn() ?>

    <?php if ($this->scripto->canProtect()): ?>

    // Handle default un/protect transcription page.
    <?php if ($this->doc->isProtectedTranscriptionPage()): ?>
    jQuery('#scripto-transcription-page-protect').
        data('protect', true).
        text('<?php echo __('Unprotect page'); ?>').
        css('float', 'none');
    <?php else: ?>
    jQuery('#scripto-transcription-page-protect').
        data('protect', false).
        text('<?php echo __('Protect page'); ?>').
        css('float', 'none');
    <?php endif; ?>

    // Handle un/protect transcription page.
    jQuery('#scripto-transcription-page-protect').click(function() {
        if (!jQuery(this).data('protect')) {
            jQuery(this).prop('disabled', true).text('<?php echo __('Protecting...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'protect',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>,
                    wikitext: jQuery('#scripto-transcription-page-wikitext').val()
                },
                function(data) {
                    jQuery('#scripto-transcription-page-protect').
                        data('protect', true).
                        prop('disabled', false).
                        text('<?php echo __('Unprotect page'); ?>');
                }
            );
        } else {
            jQuery(this).prop('disabled', true).text('<?php echo __('Unprotecting page...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'unprotect',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>
                },
                function(data) {
                    jQuery('#scripto-transcription-page-protect').
                        data('protect', false).
                        prop('disabled', false).
                        text('<?php echo __('Protect page'); ?>');
                }
            );
        }
    });


    <?php endif; // end canProtect() ?>

});
</script>

<?php
    $page_id = $this->doc->getId();
    set_current_record('item',get_record_by_id('item', $page_id));
    $collection = get_collection_for_item();
    $collection_link = link_to_collection_for_item();
?>

<?php $base_Dir = basename(getcwd()); ?>

<?php if (!is_admin_theme()): ?>

<?php endif; ?>
<div id="transcription-col" class="left-col">
  <ul class="breadcrumb">
      <li><a href="<?php echo WEB_ROOT; ?>"><span class="glyphicon glyphicon-home"/> Home</a></li>

      <li><a href="<?php echo url('collections'); ?>"><?php echo $collection_link ?></a></li>

      <li><a href="<?php echo url(array('controller' => 'items', 'action' => 'show', 'id' => $this->doc->getId()), 'id'); ?>"><?php echo $this->doc->getTitle(); ?></a></li>

  </ul>
  <div id="scripto-transcribe" class="scripto">

      <h3><span class="fa fa-book fa-lg"></span> <?php if ($this->doc->getTitle()): ?><?php echo $this->doc->getTitle(); ?><?php else: ?><?php echo __('Untitled Document'); ?><?php endif; ?></h3>

      <h4 style="margin:15px"><span class="fa fa-file-text fa-lg"></span> <?php echo $this->doc->getPageName(); ?><br/><small style="margin-left:2.5em">image <?php echo html_escape($this->paginationUrls['current_page_number']); ?> of <?php echo html_escape($this->paginationUrls['number_of_pages']); ?></small></h4>

      <!-- transcription -->
      <div id="scripto-transcription">
      <?php if ($this->doc->canEditTranscriptionPage()): ?>
          <div id="scripto-transcription-edit">
          <?php if ($this->doc->isProtectedTranscriptionPage()): ?>
              <div class="alert alert-error">
                  <strong>This transcription is complete!</strong>
              </div><!--alert alert-error-->
              <div id="scripto-transcription-page-html">
                  <?php echo $this->transcriptionPageHtml; ?>
              </div>
              <?php else: ?>
              <div class="alert alert-info">
                  <strong>This item is editable!</strong>
              </div><!--alert alert-info-->
              <div><?php echo $this->formTextarea('scripto-transcription-page-wikitext', $this->doc->getTranscriptionPageWikitext(), array('cols' => '76', 'rows' => '16')); ?></div>
              <?php endif; ?>
              <p class="help-block">
                <a data-toggle="modal" data-target="#tipsModal" href="#">Transcription Guidelines</a>
                <!--<a href="http://www.mediawiki.org/wiki/Help:Formatting" target="_blank"><?php echo __('wiki formatting help'); ?></a>--></p>
              <div>
                  <?php echo $this->formButton('scripto-transcription-page-edit', __('Save transcription'), array('class' => 'btn btn-primary btn-lg', 'style' => 'margin-top:10px; display:inline; float:none;')); ?>
              </div>

          </div><!-- #scripto-transcription-edit -->
      <?php else: ?>
          <p><?php echo __('You don\'t have permission to transcribe this page.'); ?></p>
      <?php endif; ?>


      </div><!-- #scripto-transcription -->

      <?php
        $md = json_decode($file["metadata"]);
      ?>
      <!-- pagination -->
      <ul class="pagination">
      <li><?php if (isset($this->paginationUrls['previous'])): ?>
        <a class="btn btn-xs" href="<?php echo html_escape($this->paginationUrls['previous']); ?>">&#171; <?php echo __('previous page'); ?></a>
        <?php else: ?>&#171; <?php echo __('previous page'); ?><?php endif; ?>
        </li>
      <li><?php if (isset($this->paginationUrls['next'])): ?>
        <a class="btn btn-xs" href="<?php echo html_escape($this->paginationUrls['next']); ?>"><?php echo __('next page'); ?> &#187;</a>
        <?php else: ?><?php echo __('next page'); ?> &#187;<?php endif; ?>
        </li>
      </ul>

  </div><!-- #scripto-transcribe -->
</div>
<div id="zoom">
</div>


<!-- Modal -->
<div class="modal fade" id="tipsModal" tabindex="-1" role="dialog" aria-labelledby="tipsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="tipsModalLabel">Transcription Guidelines</h4>
      </div>
      <div class="modal-body">
        <ul>
          <li>Don’t worry about formatting.
            </li>
          <li> Transcribe words as they are spelled or abbreviated.
            Resist the temptation to correct what you see in the document.</li>
          <li> Do not transcribe text that has been crossed out. </li>
          <li>Do not transcribe hyphens or spaces in words that occur at line breaks. </li>
          <li>Indicate if you can’t decipher a word. If you are unsure of a word or phrase, please use [illegible], or your best guess followed by a question mark within brackets [Chattanooga?], or even [town?] or [name?].<br>
            If you see the term [illegible] in a transcription, please try to decipher and transcribe the word.</li>
          <li>Transcribe simple forms. Please try to transcribe all elements of the document, including typewritten text that may appear in a table, form, etc. Don’t worry about formatting the transcription. If a page is entirely typewritten, do not transcribe it.</li>
          <li>Consider the context. If you’re having trouble with a word or passage, read “around” it and think about what a likely word would be, or look for other letters and spellings in the document that are similar.</li>
          <li>Consult the Iowa Digital Library record. When you are viewing a particular document in DIYHistory you will see a link above the page image that says “view in Iowa Digital Library.” This takes you another view of the digitized document as well as to useful information such as names of people, places, subjects, and events, and links to full finding aids. </li>
          <li>Be aware of contemporary spelling and abbreviations. Common eighteenth and nineteenth-century abbreviations and their full spellings include: inst. = a date in this month (e.g. the 15th inst.); ult. = a date in the previous month (5th ult.); &amp;c = et cetera; Common “misspellings” and writing conventions: ware = were; thare = there; verry = very; evry = every; evning = evening; perhapse = perhaps; attacted = attacked; fiew = few; greaddeal or great eal or gread eal = great deal; fs = ss (e.g. mifses = misses); do = ditto.
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="openseadragon1" style="width: 800px; height: 600px;"></div>
<script type="text/javascript">
    var viewer = OpenSeadragon({
        id: "zoom",
        prefixUrl: "<?php echo WEB_ROOT; ?>/themes/Princeton/images/osd/",
        showNavigator:  false,
        tileSources: [{"profile": "http://library.stanford.edu/iiif/image-api/1.1/compliance.html#level2", "scale_factors": [1, 2, 4, 8, 16], "tile_height": 256, "height": <?php echo $md->video->resolution_y ?>, "width": <?php echo $md->video->resolution_x ?>, "tile_width": 256, "qualities": ["native", "bitonal", "grey", "color"], "formats": ["jpg", "png", "gif"], "@context": "http://library.stanford.edu/iiif/image-api/1.1/context.json", "@id": "<?php echo str_replace("/full/full/0/native.jpg", "", $this->file['original_filename']); ?>"}]
    });
    // It would be nice to use <?php echo metadata($file, array('Dublin Core', 'Identifier')) ?> above instead of a string hack.  Also, it would be nice to hit the info.json for height and width data.
</script>

<?php echo foot(); ?>
