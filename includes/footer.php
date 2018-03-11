        <br><br><br>
        <footer class="footer" style="position: fixed; bottom: 0;">
            <div class="container">
                <p class="text-muted" style="margin-top: 3px;">Desenvolvido por FRDEV - Soluções Tecnológicas © - <?= date('Y'); ?></p>
            </div>
        </footer>
    </body>
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.mask.js"></script>
    <script type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="js/bootbox.min.js"></script>
    <script type="text/javascript">
        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
        $(function(){
            $.blockUI();
            setTimeout(function(){
                $.unblockUI();
            }, 550);
        });
    </script>
</html>