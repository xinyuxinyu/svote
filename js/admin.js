$(document).ready(function(){
	$("input[name='proj-switch']").bootstrapSwitch();
	
	function display(input){
		alert(JSON.stringify(input));
	}
	
	$("#new-proj-btn").click(function(){
		projname = $("#new-proj-text").val();
		if(projname == ''){
			return;
		}

	    var reg = /\s/;
		if(reg.test(projname)){
			alert("输入名称不能含有空格!")
			return;
		}

		var pass = {};
		pass.projname = projname;
		$.post("newproj.php", pass, function(data, status){
			if(data == -1){
				alert(projname + " 已存在!");
			}else{
				location.reload();
			}
		});
	});

	$("ul#proj-list li a").click(function(){
		projname = $(this).html();
		var pass = {};
		pass.name  = 'current';
		pass.value = projname;
		$.post("setstack.php", pass, function(data, status){
			location.reload();
		});
	});

	$("#modify-passwd").click(function(data){
		var idmdl = "#modify-mdl";
		$(idmdl).modal();

		var title = $(this).attr('data-title');
        $(idmdl).find('.modal-title').html(title);

		$(idmdl).find('.modal-footer #modify-confirm').on('click', function(){
			var pwd = $('#new-pwd-inp').val();
			var cfm = $('#cfm-pwd-inp').val();

			if(pwd != cfm){
                alert('两次密码不一致，请重新输入!');				
			}else{
				pwd = md5(pwd);
				cfm = md5(cfm);

				$('#old-pwd-div').html(pwd);
				var pass = {};
				pass.password = pwd;
				$.post('modpwd.php', pass, function(data){
					$(idmdl).modal('hide');
				});
			}
	 	});		

		$.post("getpwd.php", {}, function(data){
	        midl = '</td>';
	        tail = '</tr>';
	
	        var body = '<table class="table table-striped">';
	        body += '<tr id="old-pwd-tr"><td>' + '<div id="old-pwd-div" style="display:none">' + data + '</div>'
			  		+ '旧密码' + midl + '<td><input type="password" id="old-pwd-inp">'
					+ '<button id="old-pwd-btn" class="btn btn-success btn-primary margin-left">确认</button></td>'  + tail;
	        body += '<tr id="new-pwd-tr" style="display:none"><td>' 
					+ '新密码' + midl + '<td><input type="password" id="new-pwd-inp"></td>' + tail; 
	        body += '<tr id="cfm-pwd-tr" style="display:none"><td>'
					+ '请确认' + midl + '<td><input type="password" id="cfm-pwd-inp"></td>' + tail; 
	        body += '</table>';
	
			$(idmdl).find('.modal-body div').html(body);
			$(idmdl).find('.modal-footer #modify-confirm').attr('disabled', 'true');

			$('#old-pwd-btn').click(function(data){
				var old_exist = $('#old-pwd-div').html();
				var old_input = $('#old-pwd-inp').val();
				var old_input = md5(old_input);
				if(old_exist == old_input){
					$('#old-pwd-tr').hide();
					$('#new-pwd-tr').show();
					$('#cfm-pwd-tr').show();
					$(idmdl).find('.modal-footer #modify-confirm').removeAttr('disabled');

					$('#cfm-pwd-inp').focus(function(){
			            var pwd = $('#new-pwd-inp').val();

						if(pwd == ''){
			                alert('请先输入新密码!');
			                $('#new-pwd-inp').focus();
			            }else{
							if(md5(pwd) == old_input){
								alert('与旧密码一致，无需设置!');
				                $('#new-pwd-inp').val('');
				                $('#new-pwd-inp').focus();								
							}
						}
			        });

			        $('#cfm-pwd-inp').blur(function(){
			            var pwd = $('#new-pwd-inp').val();
 			            var cfm = $(this).val();

						if(pwd != cfm && pwd != '' && cfm != ''){
  			                alert('两次密码不一致，请重新输入!');
            			}
        			});										
				}else{
					alert('密码输入错误!请重新输入...');
					$('#old-pwd-inp').val('');					
					$('#old-pwd-inp').focus();
				}
			});
		});
	});

	function getproj(){
		var projname = $('#show-proj-name').html();
		return projname;
	}

	$('#proj-switch').change(function(){
		var projname = getproj();
		var pass = {}
		pass.name  = 'inuse';
		pass.value = projname;
		$.post("setstack.php", pass, function(data, status){
			location.reload();			
		});
	});

	$('#delete-form').bind('submit', function(){
		var pass = {};
		pass.projname = $('#show-proj-name').html();
		$.ajax({
				url: this.action,
			   type: this.method,
			   data: pass,
	        success: function(data){location.reload();}
		});
		return false;
	});

	$.fn.editable.defaults.mode        = 'inline';
	$.fn.editable.defaults.showbuttons = false;
	$.fn.editable.defaults.emptytext   = '';
	$.fn.editable.defaults.onblur      = 'submit';

	$('td[id^=zige-]').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
		success: function(response, newValue){
			var proj = $('#show-proj-name').html();

			var pass = {};
			pass.proj = proj;
	
			var id = $(this).attr('id');
			var reg = /vote./;
			var name = reg.exec(id) + 'zige';
			pass.name = name;
			pass.value = newValue;

			$.post("setprojstack.php", pass, function(data){
				location.reload();
			});
		}
	});	

	$('#maxvotenum').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
    	validate: function(value) {
			if(!intcheck(value)){
					return '推荐人数只能输入正整数！';
			}
    	},
		success: function(response, newValue){
			var proj = $('#show-proj-name').html();

			var pass = {};
			pass.proj = proj;
	
			var id = $(this).attr('id');
			pass.name = id;
			pass.value = newValue;

			$.post("setprojstack.php", pass, function(data){
				location.reload();
			});
		}
	});

	function getid(str){
		var idstr = $(str).attr('id');
		var reg = /\d+/;

		var id = reg.exec(idstr)[0];
		return id;
	}

	function getprojidopt(str){
		var proj = getproj();
		var idstr = $(str).attr('id');

		var id = getid(str);

		var reg = /-[a-z]+-/;
		var optstr = reg.exec(idstr);

		var reg = /[a-z]+/;
		var opt = reg.exec(optstr)[0];

		var ret = {}
		ret.proj = proj;
		ret.id   = id;
		ret.opt  = opt;

		return ret;
	}

	function intcheck(value){
		var reg = /^\s*\d+\s*$/;
		if(!reg.exec(value)){
			return false;
		}
		return true;
	}

	$('td[id^=major-]').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
    	validate: function(value) {
			var pass = getprojidopt(this);
			if(pass.opt == 'num'){
				if(!intcheck(value)){
					return '推荐人数只能输入正整数！';
				}
			}
    	},
		success: function(response, newValue){
			var pass = getprojidopt(this);
			pass.value = newValue;

			$.post("setprojmajor.php", pass, function(data){
				location.reload();
			});
		}
	});

	$('button[id^=majorop-]').click(function(){
		var id = getid(this);
		var major = $('#major-major-'+id).html();

		if(confirm('确认删除专业：'+major+'？删除之后相应的候选人也将被删除')){
			var proj = getproj();

			var pass = {};
			pass.proj = proj;
			pass.id   = id;

			$.post("delprojmajor.php", pass, function(data){
				location.reload();
			});
		}
	});

	$('#major-add-button').click(function(){
		$('#major-add-tr').hide();
		$('#major-new-line').show();
	});

	$('#major-add-canceal').click(function(){
		$('#major-add-tr').show();
		$('#major-new-line').hide();
	});

	$('td[id^=majoradd-]').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
    	validate: function(value) {
			var pass = getprojidopt(this);
			if(pass.opt == 'num'){
				if(!intcheck(value)){
					return '推荐人数只能输入正整数！';
				}
			}
    	}
	});

	$('button[id^=majorok-]').click(function(){
		var proj = getproj();
		var id = getid(this);

		var major = $('#majoradd-major-'+id).html();
		if(major == ''){
			alert('请输入专业名称！');
			return;
		}

		var num = $('#majoradd-num-'+id).html();
		if(num == ''){
			alert('请输入推荐人数！');
			return;
		}
		num = parseInt(num);

		var pass = {};
		pass.proj  = proj;
		pass.major = major;
		pass.num   = num;

		$.post("instprojmajor.php", pass, function(){
			location.reload();
		});
	});

	$('button[id^=voteds-add-button-]').click(function(){
		var id = getid(this);

		$('#voteds-add-tr-'+id).hide();
		$('#voteds-new-line-'+id).show();
	});

	$('button[id^=voteds-add-canceal-]').click(function(){
		var id = getid(this);

		$('#voteds-add-tr-'+id).show();
		$('#voteds-new-line-'+id).hide();
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

	function votedsgetprojidopt(str){
		var proj = getproj();
		var idstr = $(str).attr('id');

		var mid = getmid(str);
		var vid = getvid(str);

		var reg = /-[a-z]+-/;
		var optstr = reg.exec(idstr);

		var reg = /[a-z]+/;
		var opt = reg.exec(optstr)[0];

		var ret = {}
		ret.proj = proj;
		ret.mid  = mid;
		ret.vid  = vid;
		ret.opt  = opt;

		return ret;
	}

	$('td[id^=votedsadd-]').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
    	validate: function(value) {
			var pass = votedsgetprojidopt(this);
			if(pass.opt == 'year'){
				if(!intcheck(value)){
					return '推荐人数只能输入正整数！';
				}
			}
    	}
	});

	$('button[id^=votedsok-]').click(function(){
		var proj = getproj();
		var mid  = getmid(this);
		var vid  = getvid(this);

		var name = $('#votedsadd-name-'+mid+'-'+vid).html();
		if(name == ''){
			alert('请输入研究员姓名！');
			return;
		}

		var zige = $('#votedsadd-zige-'+mid+'-'+vid).html();
		if(zige == ''){
			alert('请输入导师资格！');
			return;
		}

		var year = $('#votedsadd-year-'+mid+'-'+vid).html();
		if(year == ''){
			alert('请输入年龄！');
			return;
		}
		year = parseInt(year);

		var date = $('#votedsadd-date-'+mid+'-'+vid).html();
		if(date == ''){
			alert('请输入出生日期');
			return;
		}

		var note = $('#votedsadd-note-'+mid+'-'+vid).html();

		var pass  = {};
		pass.proj = proj;
		pass.mid  = mid;
		pass.name = name;
		pass.zige = zige;
		pass.year = year;
		pass.date = date;
		pass.note = note;

		$.post("instprojvoted.php", pass, function(){
			location.reload();
		});		
	});

	$('td[id^=voteds-]').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
    	validate: function(value) {
			var pass = votedsgetprojidopt(this);
			if(pass.opt == 'year'){
				if(!intcheck(value)){
					return '年龄只能输入正整数！';
				}
			}
    	},
		success: function(response, newValue){
			var pass = votedsgetprojidopt(this);
			pass.value = newValue;

			$.post("setprojvoted.php", pass, function(data){
				location.reload();
			});
		}
	});

	$('button[id^=votedsop-]').click(function(){
		var mid = getmid(this);
		var vid = getvid(this);
		var name = $('#voteds-name-'+mid+'-'+vid).html();

		if(confirm('确认删除候选人：'+name+'？')){
			var vid = getvid(this);
			var proj = getproj();

			var pass = {};
			pass.proj = proj;
			pass.vid   = vid;

			$.post("delprojvoted.php", pass, function(data){
				location.reload();
			});
		}
	});

	$("form[id^='import-form-voteds']").bind('submit', function(e){
		e.preventDefault();

		var id = getid(this);

		var formData = new FormData($(this)[0]); 
		var proj     = getproj();
		formData.append('proj', proj);
		formData.append('mid',  id);

		$.ajax({
			url: this.action,
			type: this.method,
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			enctype: 'multipart/form-data',
			processData: false,
	        success: function(data){
				if(data != ''){
					cont = decodeURIComponent(data);
                    alert(cont);
				}
				location.reload();
			}
		});
	});

	$("button[id^=import-confirm-voteds-]").click(function(){
		var id = getid(this);

		var filename = $('#import-file-voteds-'+id).val();
		if(filename == ''){
			alert('数据文件尚未输入!');
			return;
		}

		$(this).attr('disabled', 'true');

		var cont = '请稍等，文件：`' + filename + "'正在上传...";

		$('#voteds-display-'+id).html(cont);
		$('#voteds-display-'+id).show();

	    setTimeout(function(){$('#import-form-voteds-'+id).submit()}, 1000);
	});

	$('button[id^=votedsclean-]').click(function(){
		var mid  = getid(this);
		var major = $('#majorname-'+mid).html();

		if(confirm('确认清空专业：'+major+'？清空之后该专业所有候选人都将被删除')){
			var proj = getproj();

			var pass  = {};
			pass.proj = proj;
			pass.mid  = mid;

			$.post("cleanprojvoted.php", pass, function(data){
				location.reload();
			});
		}
	});

	$('#all-import-form-voteds').bind('submit', function(e){
		e.preventDefault();

		var formData = new FormData($(this)[0]); 
		var proj     = getproj();
		formData.append('proj', proj);

		$.ajax({
			url: this.action,
			type: this.method,
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			enctype: 'multipart/form-data',
			processData: false,
	        success: function(data){
				if(data != ''){
					cont = decodeURIComponent(data);
                    alert(cont);
				}
				location.reload();
			}
		});
	});

	$('#all-import-confirm-voteds').click(function(){
		var filename = $('#all-import-file-voteds').val();
		if(filename == ''){
			alert('数据文件尚未输入!');
			return;
		}

		$(this).attr('disabled', 'true');

		var cont = '请稍等，文件：`' + filename + "'正在上传...";

		$('#all-voteds-display').html(cont);
		$('#all-voteds-display').show();

	    setTimeout(function(){$('#all-import-form-voteds').submit()}, 1000);
	});

	$('#voters-add-button').click(function(){
		$('#voters-add-tr').hide();
		$('#voters-new-line').show();
	});

	$('#voters-add-canceal').click(function(){
		$('#voters-add-tr').show();
		$('#voters-new-line').hide();
	});

	$('td[id^=votersadd-]').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
		validate: function(value){
			var pass = getprojidopt(this);
			if(pass.opt == 'account'){
				var accounts = $('td[id^=voters-account-]');
				for(var i = 0; i < accounts.length; i++){
					var tmp = accounts[i];
					var tmp = tmp.id;
					var cont = $('#'+tmp).html();
					if(value == cont){
						var count = $('#voterscount-'+getid('#'+tmp)).html();
						return '输入帐号与序号'+count+'重复';
					}
				}
				return;
			}
		}
	});

	$('td[id^=voters-]').editable({
		tpl: '<input type="text" width="100%">',
		clear: false,
		validate: function(value){
			var pass = getprojidopt(this);
			if(pass.opt == 'account'){
				var accounts = $('td[id^=voters-account-]');
				for(var i = 0; i < accounts.length; i++){
					var tmp = accounts[i];
					var tmp = tmp.id;
					var cont = $('#'+tmp).html();
					if(value == cont){
						var count = $('#voterscount-'+getid('#'+tmp)).html();
						return '输入帐号与序号'+count+'重复';
					}
				}
				return;
			}
		},
		success: function(response, newValue){
			var pass = getprojidopt(this);

			if(pass.opt == 'password' && pass.opt != ''){
				pass.value = md5(newValue);
			}else{
				pass.value = newValue;
			}

			$.post("setprojvoter.php", pass, function(data){
				location.reload();
			});
		}
	});

	$('button[id^=votersok-]').click(function(){
		var proj = getproj();
		var id   = getid(this);

		var name = $('#votersadd-name-'+id).html();
		if(name == ''){
			alert('请输入研究员姓名！');
			return;
		}

		var account = $('#votersadd-account-'+id).html();
		if(account == ''){
			alert('请输入帐号！');
			return;
		}

		var password = $('#votersadd-password-'+id).html();
		if(password != ''){
			password = md5(password);
		}
		var note = $('#votersadd-note-'+id).html();

		var pass  = {};
		pass.proj     = proj;
		pass.name     = name;
		pass.account  = account;
		pass.password = password;
		pass.note     = note;

		$.post("instprojvoter.php", pass, function(){
			location.reload();
		});		
	});

	$('button[id^=votersop-]').click(function(){
		var id = getid(this);
		var name = $('#voters-name-'+id).html();

		if(confirm('确认删除投票人：'+name+'？')){
			var proj = getproj();

			var pass = {};
			pass.proj = proj;
			pass.id   = id;

			$.post("delprojvoter.php", pass, function(data){
				location.reload();
			});
		}
	});

	$('#votersclean').click(function(){
		if(confirm('确认清空？清空之后该专业所有投票人都将被删除')){
			var proj = getproj();

			var pass  = {};
			pass.proj = proj;

			$.post("cleanprojvoter.php", pass, function(data){
				location.reload();
			});
		}
	});

	$('button[id^=voters-status-]').click(function(){
		var id = getid(this);
		var proj = getproj();

		var pass = {};
		pass.id = id;
		pass.proj = proj;

		$.post("cleanprojvoterpwd.php", pass, function(data){
				location.reload();
		});
	});

	$('#import-confirm-voters').click(function(){
		var filename = $('#import-file-voters').val();
		if(filename == ''){
			alert('数据文件尚未输入!');
			return;
		}

		$(this).attr('disabled', 'true');

		var cont = '请稍等，文件：`' + filename + "'正在上传...";

		$('#voters-display').html(cont);
		$('#voters-display').show();

	    setTimeout(function(){$('#import-form-voters').submit()}, 1000);
	});

	$('#import-form-voters').bind('submit', function(e){
		e.preventDefault();

		var formData = new FormData($(this)[0]); 
		var proj     = getproj();
		formData.append('proj', proj);

		$.ajax({
			url: this.action,
			type: this.method,
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			enctype: 'multipart/form-data',
			processData: false,
	        success: function(data){
				if(data != ''){
					cont = decodeURIComponent(data);
                    alert(cont);
				}
				location.reload();
			}
		});
	});

	$('#start-new-vote').click(function(){
		if(confirm('你确定开始新的投票？')){
			if(confirm('再次确认该操作不是误操作！')){
				var proj = getproj();
				var pass = {};
				pass.proj = proj;

				$.post("startnewvote.php", pass, function(data){
					location.reload();
				});
			}
		}
	});

	$('#vote-result').editable({
		tpl: '<input type="text">',
		clear: false,
		success: function(response, newValue){
			$('#statist-vote').attr('href', 'statistic.php?id='+newValue);
		}
	});

	$('#beizhu').editable({
		type: 'textarea',
		clear: false,
		success: function(response, newValue){
			var proj = getproj();
			var pass = {};

			pass.proj = proj;
			pass.name = 'note';
			pass.value = newValue;

			$.post("setprojnote.php", pass, function(data){
				location.reload();
			});
		}
	});
});
