<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header('Content-Type:text/html; charset=UTF-8');
        echo "错误!没有权限!";
        exit(0);
    }

    include_once("conn.php");
    include_once("header.php");
?>

<title><?=$site?></title>

<script type="text/javascript" src="js/jquery-1.9.0.min.js"   ></script>    
<script type="text/javascript" src="js/bootstrap-3.0.3.min.js"></script>
<script type="text/javascript" src="js/bootstrap-editable.min.js" ></script>    
<script type="text/javascript" src="js/bootstrapSwitch.js"    ></script>    
<script type="text/javascript" src="js/admin.js"              ></script>
<script type="text/javascript" src="js/md5.js"                ></script>

<link href="css/bootstrap-3.0.3.min.css" rel="stylesheet">
<link href="css/bootstrap-editable.css"  rel="stylesheet">    
<link href="css/bootstrapSwitch.css"    rel="stylesheet">        
<link href="css/admin.css"               rel="stylesheet">

</head>

<body>
    <?php include_once("dash-header.php"); ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
      <div class="form-inline">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" id="new-proj-btn" class="btn btn-default">新建</button>
            </span>
            <input  type="text" id="new-proj-text" class="form-control">
          </div>
        </div>
      </div>

        <ul class="nav nav-sidebar"></ul>
      <ul id="proj-list" class="nav nav-sidebar">
        <?php include_once("sider.php") ?>
            </ul>
         </div>

        <div id="proj-show" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

      <?php
      if($current){
        $sql = "SELECT value FROM ${current}_stack WHERE name='voterzige'";
        $res = mysql_query($sql);
        $row = mysql_fetch_row($res);
        $voterzige = $row[0];

        $sql = "SELECT value FROM ${current}_stack WHERE name='votedzige'";
        $res = mysql_query($sql);
        $row = mysql_fetch_row($res);
        $votedzige = $row[0];

        $sql = "SELECT value FROM ${current}_stack WHERE name='maxvotenum'";
        $res = mysql_query($sql);
        $row = mysql_fetch_row($res);
        $maxvotenum = $row[0];

        $sql = "SELECT value FROM ${current}_stack WHERE name='number'";
        $res = mysql_query($sql);
        $row = mysql_fetch_row($res);
        $number = $row[0];
    ?>
      <table width="100%">
        <tr>
          <td rowspan="2" align="left" width="100%">
            <h1 class="page-header" id="show-proj-name"><?=$current?></h1>
          </td>

          <td align="right" class="text-center" style="font-size: 20px;" width="180px">
               第<span style="color: green"><?=$number?></span>次投票
          </td>
          <td align="right" class="text-center" style="font-size: 20px;" id="vote-result" width="90px">
          <?=$number?>
          </td>
        </tr>
          <td class="text-center">
            <button class="btn btn-danger" id="start-new-vote">开始新投票<span class="glyphicon glyphicon-repeat"></span>
            </button>
          </td>
          <td class="text-center">
            <a class="btn btn-info" id="statist-vote" href="statistic.php?id=<?=$number?>" target="_blank">统计投票数<span class="glyphicon glyphicon-signal"></span>
            </a>
          </td>
        <tr>
        </tr>
      </table>
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h4 class="nomargin">投票设置</h4>
        </div>

        <table width="100%" class="table table-bordered">
          <thead>
            <th colspan="100%" class="text-center">参数设置</th>
          </thead>
          <tbody>
            <tr>
              <th width="100px">选举人资格</th>
                  <td id="zige-voter"><?=${voterzige}?></td>
            </tr>
            <tr>
              <th>候选人资格</th>
                  <td id="zige-voted"><?=${votedzige}?></td>
            </tr>
            <tr>
              <th>投票总数</th>
              <td id="maxvotenum"><?=${maxvotenum}?></td>
            </tr>
            <tr></tr>
          </tbody>
        </table>
<?php
        $sql = "SELECT value FROM ${current}_note WHERE name='note'";
        $res = mysql_query($sql);
        $row = mysql_fetch_row($res);
        $note = $row[0];
?>
        <table width="100%" class="table table-bordered">
          <thead>
            <th colspan="100%" class="text-center">备注</th>
          </thead>
          <tbody>
            <tr>
              <td colspan="100%" id="beizhu"><?=$note?></td>
            </tr>
            <tr></tr>
          </tbody>
        </table>
        <table width="100%" class="table table-bordered">
          <thead>
            <th colspan="100%" class="text-center">专业设置</br><span style="color:red; font-size:12px;">注：若“票数上限”设为 0，则投票按固定票数投票，票数为“最少人数”列填入的数值</span></th>
          </thead>
          <thead>
            <th class="text-center" width="60px">序号</th>
            <th class="text-center" >专业名称</th>
            <th class="text-center" width="100px">推荐人数</th>
            <th class="text-center" width="100px">最少人数</th>
            <th class="text-center" width="80px">操作</th>
          </thead>
          <tbody>
<?php
$sql = "SELECT id,major,num,nmin FROM ${current}_majors ORDER BY id";
$res = mysql_query($sql);
$count = 0;
while($row = mysql_fetch_row($res)){
  $id    = $row[0];
  $major = $row[1];
  $num   = $row[2];
  $nmin  = $row[3];
  $count++;
?>
            <tr>
              <td class="text-center"><?=$count?></td>
              <td class="text-center" id="major-major-<?=${id}?>"><?=$major?></td>
              <td class="text-center" id="major-num-<?=${id}?>"><?=$num?></td>
              <td class="text-center" id="major-nmin-<?=${id}?>"><?=$nmin?></td>
              <td class="text-center">
                <button id="majorop-<?=${id}?>" class="btn active btn-xs">
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </td>
            </tr>
<?php
}
$count++;
?>
            <tr id="major-new-line" style="display:none;">
              <td class="text-center"><?=$count?></td>
              <td class="text-center" id="majoradd-major-<?=${count}?>"></td>
              <td class="text-center" id="majoradd-num-<?=${count}?>"></td>
              <td class="text-center">
                <button id="majorok-<?=${count}?>" class="btn active btn-xs btn-primary">
                  <span class="glyphicon glyphicon-ok"></span>
                </button>
                <button id="major-add-canceal" class="btn active btn-xs btn-default">
                  <span class="glyphicon glyphicon-remove"></span>
                </button>
              </td>
            </tr>

            <tr id="major-add-tr">
              <td class="text-center">
                <button id="major-add-button" class="btn active btn-info btn-xs">
                  <span class="glyphicon glyphicon-plus"></span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="panel panel-danger">
        <div class="panel-heading">
          <table>
            <tr><td style="padding-right: 30px;"><h4 class="nomargin">候选人设置</h4></td>
              <td>
                <form id="all-import-form-voteds" action="votedsuploadall.php" method="post" enctype="multipart/form-data" class="form-inline">
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-success btn-xs">
                        <span class="glyphicon glyphicon-search"></span>
                                     <input type="file" name="file" id="all-import-file-voteds" style="display:none;" multiple>
                                </span>
                    </label>
                    <span>
                      <button type="button" class="btn btn-success btn-xs" id="all-import-confirm-voteds" style="margin-left:4px; margin-right:10px;">
                        <span class="glyphicon glyphicon-upload"></span>
                      </button>
                    </span>
                        </div>
                </form>
              </td>
              <td>
                <div id="all-voteds-display" style="display:none"></div>
              </td>
            </tr>
          </table>
        </div>
        <table width="100%" class="table table-bordered vert-align">
          <thead>
             <th class="text-center" width="80px">学科</th>
             <th class="text-center" width="50px">序号</th>
             <th class="text-center" width="160px">姓名</th>
             <th class="text-center" width="160px">组别</th>
             <th class="text-center" width="80px">志愿</th>
             <th class="text-center" width="140px">文章总数</th>
             <th class="text-center">备注</th>
             <th class="text-center" width="80px">操作</th>
          </thead>
          <tbody>
<?php
$sql = "SELECT major,num,id FROM ${current}_majors ORDER BY id";
$res = mysql_query($sql);
while($row = mysql_fetch_row($res)){
  $major = $row[0];
  $num   = $row[1];
  $id    = $row[2];

  $sqlin = "SELECT COUNT(*) FROM ${current}_voteds WHERE majorid=${id}";
  $resin = mysql_query($sqlin);
  $rowin = mysql_fetch_row($resin);
  $rowspan = $rowin[0] + 2;
?>
              <tr>
                 <th rowspan="<?=$rowspan?>" id="majorname-<?=$id?>" class="text-center"><?=$major?></th>
              <td style="display: none;"></td>
              <td style="display: none;"></td>
              <td style="display: none;"></td>
              <td style="display: none;"></td>
              <td style="display: none;"></td>
              <td style="display: none;"></td>
              <td style="display: none;"></td>
            </tr>
<?php
    $sqlin = "SELECT id,name,zige,year,note,date FROM ${current}_voteds WHERE majorid=${id} ORDER BY id";
    $resin = mysql_query($sqlin);
  $count = 0;
    while($rowin = mysql_fetch_row($resin)){
    $idin = $rowin[0];
    $name = $rowin[1];
    $zige = $rowin[2];
      $year = $rowin[3];
      $note = $rowin[4];
      $date = $rowin[5];
    $count++;
?>
            <tr>
              <td class="text-center"><?=$count?></td>
              <td class="text-center" id="voteds-name-<?=$id?>-<?=$idin?>"><?=$name?></td>
              <td class="text-center" id="voteds-zige-<?=$id?>-<?=$idin?>"><?=$zige?></td>
              <td class="text-center" id="voteds-year-<?=$id?>-<?=$idin?>"><?=$year?></td>
              <td class="text-center" id="voteds-date-<?=$id?>-<?=$idin?>"><?=$date?></td>
              <td class="text-center" id="voteds-note-<?=$id?>-<?=$idin?>"><?=$note?></td>
              <td class="text-center">
                <button id="votedsop-<?=$id?>-<?=$idin?>" class="btn active btn-xs">
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </td>
            </tr>
<?php
  }
  $count++;
?>
            <tr id="voteds-new-line-<?=$id?>" style="display:none;">
              <td class="text-center"><?=$count?></td>
              <td class="text-center" id="votedsadd-name-<?=$id?>-<?=$count?>"></td>
              <td class="text-center" id="votedsadd-zige-<?=$id?>-<?=$count?>"></td>
              <td class="text-center" id="votedsadd-year-<?=$id?>-<?=$count?>"></td>
              <td class="text-center" id="votedsadd-date-<?=$id?>-<?=$count?>"></td>
              <td class="text-center" id="votedsadd-note-<?=$id?>-<?=$count?>"></td>
              <td class="text-center">
                <button id="votedsok-<?=$id?>-<?=${count}?>" class="btn active btn-xs btn-primary">
                  <span class="glyphicon glyphicon-ok"></span>
                </button>
                <button id="voteds-add-canceal-<?=$id?>" class="btn active btn-xs btn-default">
                  <span class="glyphicon glyphicon-remove"></span>
                </button>
              </td>
            </tr>

              <tr id="voteds-add-tr-<?=$id?>">
              <td class="text-center">
                <button id="voteds-add-button-<?=$id?>" class="btn active btn-info btn-xs">
                  <span class="glyphicon glyphicon-plus"></span>
                </button>
              </td>
              <td style="border-right: none;">
                <form id="import-form-voteds-<?=$id?>" action="votedsupload.php" method="post" enctype="multipart/form-data" class="form-inline">
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-search"></span>
                                     <input type="file" name="file" id="import-file-voteds-<?=$id?>" style="display:none;" multiple>
                                </span>
                    </label>
                    <span>
                      <button type="button" class="btn btn-primary btn-xs" id="import-confirm-voteds-<?=$id?>" style="margin-left:4px;">
                        <span class="glyphicon glyphicon-upload"></span>
                      </button>
                    </span>
                        </div>
                </form>
              </td>
              <td colspan="4" style="border-left: none;">
                <div id="voteds-display-<?=$id?>" style="display:none"></div>
              </td>
              <td class="text-center">
                <button id="votedsclean-<?=$id?>" class="btn active btn-xs btn-danger">
                  <span class="glyphicon glyphicon-remove-sign"></span>
                </button>
              </td>
            </tr>
<?php
}
?>
          <tbody>
        </table>
      </div>

      <div class="panel panel-success">
        <div class="panel-heading">
          <h4 class="nomargin">投票人设置</h4>
        </div>

        <table width="100%" class="table table-bordered vert-align">
          <thead>
             <th class="text-center" width="50px">序号</th>
             <th class="text-center" width="160px">研究员姓名</th>
             <th class="text-center" width="160px">帐号</th>
             <th class="text-center" width="80px">密码</th>
             <th class="text-center" width="80px">状态</th>
             <th class="text-center">备注</th>
             <th class="text-center" width="80px">操作</th>
          </thead>
          <tbody>
<?php
$sql = "SELECT id,name,account,password,note FROM ${current}_voters ORDER BY id";
$res = mysql_query($sql);
$count = 0;
while($row = mysql_fetch_row($res)){
  $id       = $row[0];
  $name     = $row[1];
  $account  = $row[2];
  $password = $row[3];
  $note     = $row[4];
  $count++;
?>
            <tr>
              <td class="text-center" id="voterscount-<?=${id}?>"><?=$count?></td>
              <td class="text-center" id="voters-name-<?=${id}?>"><?=$name?></td>
              <td class="text-center" id="voters-account-<?=${id}?>"><?=$account?></td>
                <td class="text-center" id="voters-password-<?=${id}?>"></td>
                <td class="text-center">
<?php
  if($password == ''){
    echo '未设置';
  }else{
?>
                <button id="voters-status-<?=${id}?>" class="btn btn-info btn-xs">清空</button>
<?php
  }
?>
              </td>
              <td class="text-center" id="voters-note-<?=${id}?>"><?=$note?></td>
              <td class="text-center">
                <button id="votersop-<?=$id?>" class="btn active btn-xs">
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </td>
            </tr>
<?php
}
$count++;
?>
            <tr id="voters-new-line" style="display:none;">
              <td class="text-center"><?=$count?></td>
              <td class="text-center" id="votersadd-name-<?=${count}?>"></td>
              <td class="text-center" id="votersadd-account-<?=${count}?>"></td>
              <td class="text-center" id="votersadd-password-<?=${count}?>"></td>
              <td class="text-center"></td>
              <td class="text-center" id="votersadd-note-<?=${count}?>"></td>
              <td class="text-center">
                <button id="votersok-<?=${count}?>" class="btn active btn-xs btn-primary">
                  <span class="glyphicon glyphicon-ok"></span>
                </button>
                <button id="voters-add-canceal" class="btn active btn-xs btn-default">
                  <span class="glyphicon glyphicon-remove"></span>
                </button>
              </td>
            </tr>

            <tr id="voters-add-tr">
              <td class="text-center">
                <button id="voters-add-button" class="btn active btn-info btn-xs">
                  <span class="glyphicon glyphicon-plus"></span>
                </button>
              </td>
              <td style="border-right: none;">
                <form id="import-form-voters" action="votersupload.php" method="post" enctype="multipart/form-data" class="form-inline">
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-search"></span>
                                     <input type="file" name="file" id="import-file-voters" style="display:none;" multiple>
                                </span>
                    </label>
                    <span>
                      <button type="button" class="btn btn-primary btn-xs" id="import-confirm-voters" style="margin-left:4px;">
                        <span class="glyphicon glyphicon-upload"></span>
                      </button>
                    </span>
                        </div>
                </form>
              </td>
              <td colspan="4" style="border-left: none;">
                <div id="voters-display" style="display:none"></div>
              </td>
              <td class="text-center">
                <button id="votersclean" class="btn active btn-xs btn-danger">
                  <span class="glyphicon glyphicon-remove-sign"></span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    <?php
      }
    ?>    
      <?php include_once('footer.php'); ?>    
        </div>
      </div>
    </div>
  <?php include("modals.php"); ?>
</body>
</html>
