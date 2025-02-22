<?php
// Include the header
get_header();
?>

<div class="site-main container uk-margin-large-top uk-margin-large-bottom">

        <div class="uk-card uk-card-default uk-card-hover uk-width-xlarge uk-margin-large-top uk-margin-large-bottom uk-margin-auto uk-card-body">
            <h3 class="uk-card-title uk-text-center" id="sjw-form-title"> Please input your details below to confirm you are able to attend the interview once scheduled. </h3>
            
            <?php echo do_shortcode('[sjw_confirm_form]'); ?>
        </div>
</div>

<?php
// Include the footer
get_footer();