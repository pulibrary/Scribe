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
    if(strpos($this->file['original_filename'],"/full/full/0/default.jpg")){
      $iiif_id = str_replace("/full/full/0/default.jpg", "", $this->file['original_filename']);
    }else{
      $iiif_id = str_replace("/full/90,/0/default.jpg", "", $this->file['original_filename']);
    }

?>

<?php $base_Dir = basename(getcwd()); ?>

<?php if (!is_admin_theme()): ?>

<?php endif; ?>
<div id="transcription-col" class="left-col">
  <ul class="breadcrumb">

      <li><a href="<?php echo url('collections'); ?>"><span class="glyphicon glyphicon-home"></span> <?php echo $collection_link ?></a></li>

      <li><a href="<?php echo url(array('controller' => 'items', 'action' => 'show', 'id' => $this->doc->getId()), 'id'); ?>"><?php echo $this->doc->getTitle(); ?></a></li>

  </ul>
  <div id="scripto-transcribe" class="scripto">
      <h3 style="display:none;"><span class="fa fa-book fa-lg"></span> <?php if ($this->doc->getTitle()): ?><?php echo $this->doc->getTitle(); ?><?php else: ?><?php echo __('Untitled Document'); ?><?php endif; ?></h3>
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
                <!--alert
              <div class="alert alert-info">
                  <strong>This item is editable!</strong>
              </div> alert-info-->
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
          <p><?php echo __('<a class="btn btn-primary btn-lg" href="' . WEB_ROOT . '/scripto/login">Log in to Transcribe</a>'); ?></p>
	  <?php if($this->doc->getTranscriptionStatus() == 'Not Started'): ?>
	  <h3>Transcription Not Started</h3>
          <?php else: ?>
          <h3>Current Transcription</h3>
	  <p><?php echo $this->doc->getTranscriptionPageWikitext(); ?></p>
     	  <?php endif; ?>
     <?php endif; ?>


      </div><!-- #scripto-transcription -->

      <?php
        $md = json_decode($file["metadata"]);
      ?>
      <!-- pagination -->
      <ul class="pagination">
      <li><?php if (isset($this->paginationUrls['previous'])): ?>
        <a class="btn btn-xs" href="<?php echo html_escape($this->paginationUrls['previous']); ?>">&#171; <?php echo __('previous page'); ?></a>
      <?php else: ?><a class="btn btn-xs disabled"> &#171; <?php echo __('previous page'); ?></a><?php endif; ?>
        </li>
      <li><?php if (isset($this->paginationUrls['next'])): ?>
        <a class="btn btn-xs" href="<?php echo html_escape($this->paginationUrls['next']); ?>"><?php echo __('next page'); ?> &#187;</a>
      <?php else: ?><a class="btn btn-xs disabled"><?php echo __('next page'); ?> &#187; </a><?php endif; ?>
        </li>
      </ul>

  </div><!-- #scripto-transcribe -->

  <div class="credit">For use by members of Fall 2016 Course - HIS 374: History of the American West</div>

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
                <ul><li>
        <strong>TRANSCRIBE WHAT YOU SEE</strong>: As much as possible, transcribe the text as it appears on the page, including original spellings, abbreviations, symbols, etc.
        </li>
        <li>
        <strong>FOLLOW LINE BREAKS</strong>: Later, when the transcription is indexed to the image, this will allow keyword searches to go to the specific line of text.
        </li>
        <li>
          <strong>USE BRACKETS</strong>: Brackets are used for the following:
          <ul>
            <li>COMMENTS AND NOTES: Use [Note: text] when adding descriptive notes or illustrations and non-narrative text. Ex. [Note: drawing of a campsite] or [Note: accounting table] or [Note: blank page] or [Note: scribbling].</li>
            <li>CROSSED-OUT WORDS: Use [--text--] for text that has been crossed-out. Ex. [--John Smith--].</li>
            <li>ILLEGIBLE WORDS: Use [Illegible] for words you cannot decipher.</li>
            <li>BEST GUESS: Use [?text?] if you are unsure of the transcription. Ex. [?John Smith?].</li>
            <li>VERTICAL TEXT: Use [/text/] for text that is written vertically on the page.  Ex. [/John Smith went on/].</li>
            <li>OTHER: Use brackets for any other instance where you are adding an explanation or comment to the transcription that is not a part of the original text.</li>
          </ul>
        <li>
        <strong>CONSIDER THE CONTEXT</strong>: When transcribing a difficult word or passage, consider the subject of the manuscript as well as the preceding words and passages for hints at the likely transcription. Compare other words and letters to help decipher difficult handwriting. Be aware of contemporary abbreviations and spellings. A few common nineteenth-century abbreviations and spellings include (provided by DIYHistory Iowa):
        <blockquote>inst. = a date in this month (e.g. the 15th inst.); ult. = a date in the previous month (5th ult.); &amp;c = et cetera;  ware = were; thare = there; verry = very; evry = every; evning = evening; perhapse = perhaps; attacted = attacked; fiew = few; greaddeal or great eal or gread eal = great deal; fs = ss (e.g. mifses = misses); do = ditto. Capt. = Captain; Lieut. or Lt. = Lieutenant; Maj. = Major; Col. = Colonel; Regt. = Regiment; Brig. = Brigade.</blockquote>
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
    jQuery(document).ready(function() {
      jQuery.get( "<?php echo $iiif_id; ?>/info.json", function( data ) {
        var viewer = OpenSeadragon({
            id: "zoom",
            prefixUrl: "<?php echo WEB_ROOT; ?>/themes/Princeton/images/osd/",
            showNavigator:  false,
            // Show rotation buttons
            showRotationControl: true,
            // Enable touch rotation on tactile devices
            gestureSettingsTouch: {
                pinchRotate: true
            },
            tileSources: [{
                  "@context": "http://iiif.io/api/image/2/context.json",
                  "@id": "<?php echo $iiif_id; ?>",
                  "height": data.height,
                  "width": data.width,
                  "profile": [ "http://iiif.io/api/image/2/level2.json" ],
                  "protocol": "http://iiif.io/api/image",
                  "tiles": data.tiles
            }]
        });
      });
    });
</script>

<?php echo foot(); ?>
