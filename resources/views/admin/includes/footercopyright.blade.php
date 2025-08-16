<div class="footer">
    <div class="pull-right">
        <strong>All rights reserved</strong>
    </div>
    <div class="pull-left">
        <strong>Copyright</strong> HASHMANAGEMENT © {{ Carbon\Carbon::now()->year }}
    </div>
</div>


<style>
    html, body {
        height: 100%;
    }

    #wrapper {
        min-height: 100%;
        position: relative;
        padding-bottom: 60px; /* height of the footer */
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 60px;
        background-color: #f5f5f5;
        padding: 15px;
        text-align: center;
        border-top: 1px solid #e7eaec;
    }
</style>