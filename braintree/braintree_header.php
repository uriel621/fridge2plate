<header class="col-xs-12 main">
    <div class="col-xs-12 col-sm-6 logo">
        <a href="../index.php"><img src="../images/fridge2plate.png"></a>
    </div>

    <div class="col-xs-12 col-sm-6 bt-badge">
        <a href="https://www.braintreegateway.com/merchants/9pbmh9skjwrmy4t8/verified" target="_blank">
            <img id="bt-img" src="https://s3.amazonaws.com/braintree-badges/braintree-badge-wide-light.png"/>
        </a>
    </div>

    <div class="notice-wrapper">
        <?php if (isset($_SESSION["errors"])) : ?>
            <div class="show notice error notice-error">
                <span class="notice-message">
                    <?php
                    echo($_SESSION["errors"]);
                    unset($_SESSION["errors"]);
                    ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
</header>
