<div class="aboutus">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
            <p><img class="qrcode" src="{{ asset('static/img/qrcode.jpg') }}" alt=""></p>
            <p><i class="fa fa-fw fa-weixin" aria-hidden="true"></i> {{trans('footer.line_1')}}</p>
        </div>
    </div>
</div>
<div class="copyright text-center">
    <p>© XJTU ANA <font id="time"></font>. All rights reserved.</p>
</div>

<script type="text/javascript">
window.onload=writeTime();
function writeTime(){
    var date=new Date;
    var year=date.getFullYear(); 
    document.getElementById('time').innerHTML=year;   
}
</script>