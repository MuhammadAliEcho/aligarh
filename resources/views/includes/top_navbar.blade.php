<div class="row border-bottom">
<nav class="navbar navbar-static-top ng-scope" role="navigation" style="margin-bottom: 0">
<div class="navbar-header">
    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " id="nav_collapse" href="#"><i class="fa fa-bars"></i> </a>
<!--     <div role="search" style="padding: 15px 0px 0px 0px; float: left">
        <h3>Current Session: 2017-2018</h3>
    </div> -->
</div>
    <ul class="nav navbar-top-links navbar-right">
        <li>
            <span class="m-r-sm text-muted welcome-message">Welcome to Aligarh Management System.</span>
        </li>

        <li>
            <a href="{{ URL('logout') }}">
                <i class="fa fa-sign-out"></i> Log Out
            </a>
        </li>

    </ul>

</nav>
<!-- <script type="text/javascript">
/*  $(document).ready(function(){

    $("#nav_collapse").click(function(){

        $.post('{{ URL('user-settings/skincfg') }}', { _token: "{{ csrf_token() }}", nav_collapse: "mini-navbar" })
        .done(function(data) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 8000
                };
                toastr.success(data.toastrmsg.msg, data.toastrmsg.title);
          }
        )
        .fail(function () {
            alert("Fail");
        });

    });

  });*/
</script> -->
</div>
