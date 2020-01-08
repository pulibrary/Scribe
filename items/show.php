<?php
echo head(array(
    'title' => metadata('item', array('Dublin Core', 'Title')),
    'bodyclass' => 'items show',
));

$collection = get_collection_for_item();
?>
<div id="primary">
    <ul class="breadcrumb">
      <li><?php echo link_to_home_page(); ?></li>
      <li><?php echo link_to_collection_for_item($collection->name, array('id' => 'item-collection-link',)); ?></li>
      <li class="active"><?php echo metadata('item', array('Dublin Core', 'Title')); ?></li>
    </ul>

    <h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?> <br/><small><?php echo metadata('item', array('Dublin Core', 'Date')); ?></small></h1>
    <p><?php echo metadata('item', array('Dublin Core', 'Description')); ?></p>
    <!-- The following returns all of the files associated with an item. -->
    <div id="itemfiles" class="element">

        <div class="element-text">
            <?php
            require_once PLUGIN_DIR . '/Scripto/libraries/Scripto.php';
           // require_once APP_DIR . '/helpers/Media.php';
            $scripto = ScriptoPlugin::getScripto();
            // $helper = new Omeka_View_Helper_Media;
            $files =  return_files_for_item(array()); ?>
            <ul class="thumbnails">
            <?php foreach ($files as $file) :
                $status = $scripto->getPageTranscriptionStatus($file->id);

                switch ($status) {
                case 'Completed':
                    $label = "label-important";
                    break;
                case 'Needs Review':
                    $label = "label-warning";
                    break;
                case 'Not Started':
                    $label = "label-success";
                    break;
                default:
                    $label = "label-important";
                    break;
                }

                 $fileTitle = metadata($file, array('Dublin Core', 'Title'));
                 // Using monospaced font to make this work
                 if (strlen($fileTitle) <= 18) {
                    $fileTitle .= '<br /><br /><br />';
                 } elseif (strlen($fileTitle) <= 36 ) {
                     $fileTitle .= '<br /><br />';
                 }
                 //$weight = metadata($file, array('Scripto', 'Weight'));

                 // we need to url_encode the figgy id in case it was converted to slashes by the import
                $from_figgy = strpos($file->original_filename, 'figgy_prod');
                $filename_tmp = "";
                if ($from_figgy !== false) {
                    $filename_tmp = str_replace("https://iiif.princeton.edu/loris/figgy_prod/", "", $file->original_filename);
                    $filename_tmp = str_replace("intermediate_file.jp2/full/1000,/0/default.jpg", "", $filename_tmp);
                    $filename_tmp = urlencode($filename_tmp);
                    $filename_tmp = "https://iiif.princeton.edu/loris/figgy_prod/" . $filename_tmp . "intermediate_file.jp2/full/200,/0/default.jpg";
                } else {
                    $filename_tmp = str_replace("/full/full/0/default.jpg", "/full/200,/0/default.jpg", $file->original_filename);
                }

             ?>
                <li class="span2">
                    <div class="thumbnail">
                        <a class="thumb" href="<?php echo url(array('action' => 'transcribe', 'item-id' => $file->item_id, 'file-id' => $file->id), 'scripto_action_item_file'); ?>">
                            <img src="<?php echo $filename_tmp; ?>" alt="image <?php echo $file->order; ?>" title="image <?php echo $file->order; ?>">
                        </a>
                        <h4><?php echo "image " . $file->order; ?></h4>
                        <span class="label <?php echo $label; ?>"><?php echo $status; ?></span>
                     </div>
                 </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php echo foot(); ?>
