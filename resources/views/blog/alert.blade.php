@if($post != null)
    <div id="alert-box" class="col-md-10 col-md-offset-1">
        <div class="alert alert-warning">
            <span class="closebtn">&times;</span>
            <div class="alert-title">
                <i class="start-icon  fa fa-info-circle faa-shake animated"></i>
                {{$post["title"]}}
            </div>
            <div class="alert-content">
                {{$post["content"]}}
            </div>
        </div>
        <script>
            var close = document.getElementsByClassName("closebtn");
            var i;
            for (i = 0; i < close.length; i++) {
            close[i].onclick = function(){
                var div = this.parentElement;
                div.style.opacity = "0";
                setTimeout(function(){ div.style.display = "none"; }, 600);
            }
            }
        </script>
    </div>
@endif
