<div class="row">

<?php foreach ($widgets as $key => $widget) { ?>
    <div class="<?php echo $widget['class'] ?>">
        <?php echo $widget['content'] ?>
    </div>
<?php } ?>

</div>