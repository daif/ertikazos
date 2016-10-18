<div class="panel panel-default">
    <div class="panel-body">

        <?php echo make_form_open() ?>
        <?php foreach ($this->user->forms('edit_account') as $key => $input) { ?>
            <div class="form-group">
                <?php echo make_label($input) ?>
                <?php echo make_input($input, $row) ?>
            </div>
        <?php } ?>
        <?php echo make_form_submit() ?>
        <?php echo make_form_close() ?>

    </div>
</div>

@section('javascript')
<script type="text/javascript">
    function template_user_avatar (state) {
        if (!state.id) { return state.text; }
        return $('<span><img src="' + $base_url + 'assets/avatars/' + state.element.value.toLowerCase() + '" style="width: 28px;" /> ' + state.text + '</span>');
    }
</script>
@endsection