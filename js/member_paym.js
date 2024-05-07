
var url;
var dg;
var getFormName;

//搜尋功能
function doSearch(getUrl,tbid)
{

	$('#'+tbid).datagrid({
		url:getUrl,
		pageSize:50,
		autoLoad: false,
		onBeforeLoad:function(){
			var opts = $(this).datagrid('options');
			return opts.autoLoad;
		}
	});

	$('#'+tbid).datagrid('options').autoLoad = true;

	$('#'+tbid).datagrid('load',{

		salaryyear: $('#salaryyear').val(),
		cid: $('#cid').val(),
	});



}


function addDivDialog(getUrl,tbid,formid){

	$('#dlg').dialog('open').dialog('setTitle','新增員工基本資料');
	url = getUrl;
	getFormName = formid;
}




