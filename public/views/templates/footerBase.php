<?php
/**
 * @var array $scripts
 */
?>
<footer id="footer" class="page-footer gradient-45deg-red-pink">
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <p class="white-text rem20">Réservé</p>
                <p class="grey-text text-lighten-4">Espace réservé aux administrateurs du site.</p>
            </div>
        </div>
    </div>
</footer>

<script src="<?= PROJECT_LINK; ?>/public/assets/import/jQuery/jquery-3.3.1.min.js"></script>
<script src="<?= PROJECT_LINK; ?>/public/assets/import/Materialize/js/materialize.min.js"></script>
<script src="<?= PROJECT_LINK; ?>/public/assets/import/SweetAlert/sweetalert.min.js"></script>
<script src="<?= PROJECT_LINK; ?>/public/assets/js/core.js"></script>
<?php foreach($scripts as $script) { ?>
    <script src="<?= PROJECT_LINK; ?>/public/assets/<?= $script; ?>"></script>
<?php } ?>
</body>
</html>