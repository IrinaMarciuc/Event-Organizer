@if (session('success'))
    <?php
    echo '<script type="text/javascript">', "$.bootstrapGrowl('" . session('success') . "',  { type: 'success', offset: {from: 'top', amount: 100} });", '</script>';
    ?>
@endif

@if (session('error'))
    <?php
    echo '<script type="text/javascript">', "$.bootstrapGrowl('" . session('error') . "',  { type: 'danger', offset: {from: 'top', amount: 100} });", '</script>';
    ?>
@endif
