<?php
if (isset($_GET['tab'])){
  $currentTab = $_GET['tab'];
} else {
  $currentTab = 'settings';
}

if (isset($_POST['save-jvcf7-options'])){
    $actionReturn = jvcf7_save_options();
}
?>

<?php if (isset($actionReturn)):?>
    <div class="updated <?php echo $actionReturn['status']; ?>" id="message"><p><?php echo $actionReturn['body']; ?></p></div>
<?php endif; ?>

<div class="wrap">   
    
    <h1>jQuery Validation For Contact Form 7</h1>

    <nav class="nav-tab-wrapper">
        <?php foreach ($jvcf7_tabs as $tabKey => $tabData) { ?>
            <a href="?page=jvcf7&tab=<?php echo $tabKey; ?>" class="nav-tab <?php echo $currentTab == $tabKey?'nav-tab-active':''; ?>"><?php echo $tabData['name']; ?></a>
        <?php } ?>
    </nav>

    <div class="tab-content">
        <table width="100%">
            <tr>
                <td valign="top">
                <?php include(JVCF7_FILE_PATH.'includes/views/'.$jvcf7_tabs[$currentTab]['path']); ?>
                </td>
                <td width="15" class="dchideMobile">&nbsp;</td>
                <td width="250" class="dchideMobile" valign="top"><?php include(JVCF7_FILE_PATH.'includes/views/jvcf7_sidebar.php'); ?></td>
            </tr>
        </table>
    </div>          
            
</div>