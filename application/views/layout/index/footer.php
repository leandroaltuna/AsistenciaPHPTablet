            </div>
        </div>
        <script type="text/javascript" src="<?php echo BASE_URL; ?>public/app/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo BASE_URL; ?>public/app/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo BASE_URL; ?>public/app/js/jquery.validate.min.js"></script>
        <script type="text/javascript">
            var CI = <?php echo json_encode($this->CI); ?>;
            $.extend(jQuery.validator.messages, {
                required: "Campo obligatorio",
                // remote: "Please fix this field.",
                email: "Ingrese un email válido",
                // url: "Please enter a valid URL.",
                date: "Ingrese una fecha válida",
                // dateISO: "Please enter a valid date (ISO).",
                number: "Solo se permiten números",
                digits: "Solo se permiten números",
                range: jQuery.validator.format("Por favor ingrese un valor  entre {0} y {1}."),
                // creditcard: "Please enter a valid credit card number.",
                // equalTo: "Please enter the same value again.",
                // accept: "Please enter a value with a valid extension.",
                maxlength: jQuery.validator.format("Por favor ingrese a lo más {0} caracteres."),
                minlength: jQuery.validator.format("Por favor ingrese por lo menos {0} carateres."),
                rangelength: jQuery.validator.format("Por favor ingrese un valor entre {0} y {1} caracteres de largo."),
                max: jQuery.validator.format("Por favor ingrese un valor menor o igual a {0}."),
                min: jQuery.validator.format("Por favor ingrese un valor mayor o igual a {0}.")
            });

        </script>
        <?php if (isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
            <?php foreach ($_layoutParams['js'] as $layout): ?>
<script src="<?php echo $layout; ?>" type="text/javascript"></script>
            <?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>