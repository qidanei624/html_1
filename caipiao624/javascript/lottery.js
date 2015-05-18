//全选/取消
function checkAll(o, checkBoxName)
{
	var oc = document.getElementsByName(checkBoxName);
	for(var i=0; i<oc.length; i++) {
		if(o == 'all'){
			oc[i].checked=true;	
		}else if(o == 'none'){
			oc[i].checked=false;	
		}
	}
	//checkDeleteStatus(checkBoxName)
}

function doAction(a,id,v)
{		
	if(a == 'delete'){
		if(confirm('请确认是否删除！')){
			document.manage_threed.submit();
		}		
	}

	if (a == 'approved')
	{
		document.getElementById(id).value = 'approved';
		if (confirm('请确认是否审核通过？'))
		{
			document.manage_threed.submit();
		}
	}

	if (a == 'auto')
	{
		document.getElementById(id).value = 'auto';
		document.manage_threed.action = ""; // 特殊情况, 自动生成按钮
		document.manage_threed.submit();
	}

	if (a == 'add')
	{
		document.getElementById(id).value = 'add';
		document.manage_threed.submit();
	}

	if (a == 'edit')
	{
		document.getElementById(id).value = 'edit';
		document.manage_threed.submit();
	}
}

//获取所有被选中项的ID组成字符串
function getCheckedIds(checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	var CheckedIds = "";
	for(var i=0; i<oc.length; i++) {
		if(oc[i].checked){
			if(CheckedIds==''){
				CheckedIds = oc[i].value;	
			}else{
				CheckedIds +=","+oc[i].value;
			}
			
		}
	}
	return ;
}

