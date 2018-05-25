@include('webpage.header')

<!-- SECCION: INDEX -->
<section id="index_advertisers" class="advertisers">
    <h2>
        <?php echo Lang::get('webpage.we_connect_brands') ?><br />
        <a href="marketers.php"><?php echo Lang::get('webpage.more_about_services') ?></a>
    </h2>
</section>

<section id="index_publishers" class="advertisers">
    <h2>
        <?php echo Lang::get('webpage.we_power_independent_web') ?><br />
        <a href="publishers.php"><?php echo Lang::get('webpage.become_adtomatik_publisher') ?></a>
    </h2>
</section>

<section id="index_coffee" class="advertisers">
    <h2>
        <?php echo Lang::get('webpage.we_turn_passions') ?><br />
        <a href="publishers.php"><?php echo Lang::get('webpage.become_adtomatik_publisher') ?></a>
    </h2>
</section>
<!-- /SECCION: INDEX -->
@include('webpage.footer')