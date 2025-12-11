<form role="form" class="form-horizontal" style="padding: 18px 0px 5px 0px" method="post" action="{{ URL('user-settings/changesession') }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label class="col-xs-6 col-md-6 col-lg-6 control-label">Current Session :</label>
        <div class="col-xs-6 col-md-6 col-lg-6">
        <select class="form-control" name="current_session">
            @foreach(App\Model\AcademicSession::UserAllowSession()->get() AS $session)
                <option value="{{ $session->id }}">{{ $session->title }}</option>
            @endforeach
        </select>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[name="current_session"]').change(function(){
            $(this).parents('form').submit();
        }).val({{ Auth::user()->academic_session }});
    });
</script>