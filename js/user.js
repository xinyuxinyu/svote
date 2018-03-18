$(document).ready(function(){
  var nmax  = $('#maxvotenum').html();
  if(nmax == 0 ){
    $('#info').hide();
  }
  $("input[id^='voteds-vote-']").iCheck({
    checkboxClass: 'icheckbox_square-blue',
    increaseArea: '20%'
  });

  function getmid(str){
    var idstr = $(str).attr('id');

    var reg = /-\d+-/;
    var idmid = reg.exec(idstr)[0];

    var reg = /\d+/;
    var mid = reg.exec(idmid)[0];

    return mid;
  }

  function getvid(str){
    var idstr = $(str).attr('id');

    var reg = /-\d+$/;
    var idvid = reg.exec(idstr)[0];

    var reg = /\d+/;
    var vid = reg.exec(idvid)[0];
  
    return vid;    
  }

  function getproj(){
    var projname = $('#show-proj-name').html();
    return projname;
  }

    $("input[id^='voteds-vote-']").on('ifChanged', function(){
    var id = $(this).attr('id');
    var proj = getproj();
    var val = 1 - $(this).val();
    $(this).val(val);

    var number  = $('#number').html();
    var voterid = $('#voterid').html();
    var majorid = getmid(this);
    var votedid = getvid(this);

    var pass = {};
    pass.proj    = proj;
    pass.number  = number;
    pass.voterid = voterid;
    pass.majorid = majorid;
    pass.votedid = votedid;
    pass.value   = val;

    $.post("uservote.php", pass, function(data){
      if(data == -1){
        alert('管理员可能开启了新的投票，请刷新页面后再试！');
        return;
      }

      var str = '';
      if(val == 1){
        str = 'OK';
      }

      $('#'+id+'-stat').html(str);

      var chknum = getchecked();
      if(data != chknum){
        location.reload();
      }

      setmajorstatus();
    });
  });

  function getchecked(){
    var ret = 0;
        var getinput = $("input[id^='voteds-vote-']");
        for(var i = 0; i < getinput.length; i++){
            if(getinput[i].value > 0){
        ret++;
            }
        }
    return ret;
  }

  function getmajorvoted(){
    var ret = 0;
    var maxmajornum = $('#maxmajornum').html();
    for(var i = 1; i <= maxmajornum; i++){
      var id = $('#majorid-'+i).html();
      var getinput = $("input[id^='voteds-vote-"+id+"-']");

      var flag = 0;
      var nmin = $('#nmin-'+i).html();
      for(var j = 0; j < getinput.length; j++){
         flag = flag + parseInt(getinput[j].value);
      }

      if(flag >= nmin){
        ret++;
      }
    }
    return ret;
  }

  function getunvoted2(){
    var ret = 0;
    var maxmajornum = $('#maxmajornum').html();
    for(var i = 1; i <= maxmajornum; i++){
      var id = $('#majorid-'+i).html();
      var getinput = $("input[id^='voteds-vote-"+id+"-']");

      var flag = 0;
      var nmin = $('#nmin-'+i).html();
      for(var j = 0; j < getinput.length; j++){
         flag = flag + parseInt(getinput[j].value);
      }

      if(flag != nmin){
        ret=i;
      }
    }
    return ret;
  }


  function getunvotedmajor(){
    var ret = 0;
    var maxmajornum = $('#maxmajornum').html();
    for(var i = 1; i <= maxmajornum; i++){
      var id = $('#majorid-'+i).html();
          var getinput = $("input[id^='voteds-vote-"+id+"-']");

      var flag = 0;
      var nmin = $('#nmin-'+i).html();
          for(var j = 0; j < getinput.length; j++){
              flag = flag + parseInt(getinput[j].value);
          }

      if(flag < nmin){
        return i;
      }
    return ret;
  }}

  function setmajorstatus(){
    var maxmajornum = $('#maxmajornum').html();
    var maxvotenum  = $('#maxvotenum').html();
    var majorvoted  = getmajorvoted();
    var unvote2 = getunvoted2();
    var totvotenum  = getchecked();

    $('#currentmajornum').html(majorvoted);
    $('#totvotenum').html(totvotenum);

    $('#currentmajornum').removeClass();
    $('#totvotenum').removeClass();
    $('#submit-status').removeClass();

    if (maxmajornum == majorvoted && maxvotenum == 0) {
      if (unvote2 == 0) {
        $('#currentmajornum').addClass('finish');
        $('#totvotenum').addClass('finish');

        $('#submit-status').html('可提交');
        $('#submit-status').addClass('finish');
      }else{
        $('#currentmajornum').addClass('progress');
        $('#totvotenum').addClass('progress');

        $('#submit-status').html('不可提交');
        $('#submit-status').addClass('progress');
      }
    } else {
      if (maxmajornum == majorvoted && totvotenum <= maxvotenum) {
        $('#currentmajornum').addClass('finish');
        $('#totvotenum').addClass('finish');

        $('#submit-status').html('可提交');
        $('#submit-status').addClass('finish');
      }else{
        $('#currentmajornum').addClass('progress');
        $('#totvotenum').addClass('progress');

        $('#submit-status').html('不可提交');
        $('#submit-status').addClass('progress');
      }
    }
  }

    function checkstat(){
        var getinput = $("input[id^='voteds-vote-']");
        for(var i = 0; i < getinput.length; i++){
            if(getinput[i].value == 1){
                $('#'+getinput[i].id+'-stat').html('OK');
            }
        }
    setmajorstatus();
    }
    checkstat();

  $('#submit').click(function(){
    var maxmajornum = $('#maxmajornum').html();
    var maxvotenum  = $('#maxvotenum').html();
    var majorvoted  = getmajorvoted();
    var unvote2  = getunvoted2();
    var totvotenum  = getchecked();    

    if(maxvotenum == 0){
      if(unvote2 > 0){
        var majorname = $('#majorname-'+unvote2).html();
        var nmin = $('#nmin-'+unvote2).html();
        alert("请在学科 “"+majorname+"” 选择 "+nmin+" 个候选人");
        return;
      }
    } else {
      if(majorvoted < maxmajornum){
        var unvotedid = getunvotedmajor();
        var majorname = $('#majorname-'+unvotedid).html();
        alert("'已投学科数'应该='总学科数'，学科：'"+majorname+"'没有选择足够的候选人。");
        return;
      }

      if(totvotenum > maxvotenum){
        alert("'总投票数'应该<='票数上限'，请筛减票数。");
        return;
      }
    }

    if(confirm('已完成投票，确定提交吗？提交之后你将无法修改和查看选票。')){
      if(confirm('再次确认是否提交选票？')){
        var proj = getproj();
        var number  = $('#number').html();
        var voterid = $('#voterid').html();

        var pass = {};
        pass.proj = proj;
        pass.number = number;
        pass.voterid = voterid;
        $.post("submit.php", pass, function(){
          location.reload();
        });
      }
    }
  });

  $.fn.editable.defaults.mode        = 'inline';
  $.fn.editable.defaults.showbuttons = false;
  $.fn.editable.defaults.emptytext   = '';
  $.fn.editable.defaults.onblur      = 'submit';

  $('#beizhuuser').editable({
    type: 'textarea',
    clear: false,
    toggle: "cannotopen"
  });
});
