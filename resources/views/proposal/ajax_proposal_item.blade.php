<section class="proposal-items">
</section>

<script type="text/javascript">
    $(document).ready(function() {
        var items_url = "/proposal";
        $.ajax({
                url : items_url
            }).done(function (data) {
                $('.proposal-items').html(data);
            }).fail(function () {
                alert('Proposal could not be loaded.');
            });


        $(function() {
            $('body').on('click', '.pagination a', function(e) {
                e.preventDefault();
                $('#load a').css('color', '#dfecf6');
                $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');
                var url = $(this).attr('href');
                getItems(url);
                // window.history.pushState("", "", url);
            });
            function getItems(url) {
                $.ajax({
                    url : url,
                    type: "POST",
                }).done(function (data) {
                    $('.proposal-items').html(data);
                }).fail(function () {
                    alert('Proposal could not be loaded.');
                });
            }
        });

    });
</script>
