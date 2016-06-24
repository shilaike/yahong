<?php if (!defined('THINK_PATH')) exit();?>
<body>
<table id="dg2"></table>
<div id="toolbar1">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">新增</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">编辑</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">删除</a>
    <form id="fmSearch2" method="post" novalidate style="display:inline-block;margin-left:50px;">
       <!-- <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon icon-all" plain="true" onclick="showAll()">全部</a>
        <input id='searchBox2' class="easyui-searchbox" data-options="height:30,menu:'#searchboxSelect2',searcher:doSearch2"  />
        <div id="searchboxSelect2">
            <div data-options="name:'customname'">来往单位</div>
            <div data-options="name:'productkindname'">商品分类</div>
        </div>-->
        <div id="searchboxSelect2">
        </div>
            <label>地区</label>
            <input type="text" id="sarea" name="areaname" style="width: 128px"
                   class="easyui-validatebox"  />
            <label>来往单位</label>
            <input type="text" id="scustomid"  class="textbox-prompt" style="width: 128px"
               class="easyui-validatebox"  />
        <label>类别</label>
        <input type="text" id="skindid" name="kindname" class="textbox-prompt" style="width: 128px"
               class="easyui-validatebox"  />
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon icon-all" plain="true" onclick="showAll()">全部</a>
       <!-- <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchUser()">搜索</a>-->
    </form>
</div>
<div id="dlg" class="easyui-dialog" title="添加折扣" style="width:500px;height:200px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons1"  modal="true">
    <form id="addfm" method="post" novalidate>
        <div class="fitem">
            <label>客户名称</label>
            <input name="customid"  id="cg" style="width:300px;" data-options="required:true" />
        </div>
        <div class="fitem">
            <label>商品类别</label>
            <select id="cc" class="easyui-combotree"  data-options="required:true" url="<?php echo U('Product/productList');?>" style="width:300px;" name="kindid"/>
        </div>
        <div class="fitem">
            <label>折&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;扣</label>
            <input name="discount"   class="easyui-textbox" data-options="required:true" style="width:300px;">
        </div>

    </form>
</div>
<div id="editdlg" class="easyui-dialog" title="编辑折扣" style="width:400px;padding:10px 20px"
     closed="true" buttons="#dlgEdit-buttons2"  modal="true">
    <form id="editfm" method="post" novalidate>
        <div class="fitem">
            <label>客户名称</label>
            <input name='customid' id="cusid" type="hidden"  />
            <input name="customname"  style="width:300px;" id="cnameid" class="easyui-textbox" disabled="disabled" required="true" />
        </div>
        <div class="fitem">
            <label>商品类别</label>
            <input name='productkindid' id="cproductkindid" type="hidden" />
            <input name="productkindname"  style="width:300px;" id="ckindid" class="easyui-textbox" disabled="disabled" required="true"/>
        </div>
        <div class="fitem">
            <label>折&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;扣</label>
            <input name="discount" id="cdiscountid"  class="easyui-textbox" style="width:300px;"  required="true"  >
        </div>

    </form>
</div>
<div id="dlg-buttons1">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="addUser()" style="width:90px">添加</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">取消</a>
</div>
<div id="dlgEdit-buttons2">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()" style="width:90px">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#editdlg').dialog('close')" style="width:90px">取消</a>
</div>
<script type="text/javascript">
    function newUser(){
        $('#dlg').dialog('open').dialog('setTitle','添加折扣');
        $('#cg').combogrid({
            panelWidth:300,
            url: "<?php echo U('Units/unitsList');?>",
            idField:'s_id',
            textField:'u_name',
            mode:'remote',
            pagination:true,
            pageSize: 30,
            pageList: [5,10,20,30,50],
            fitColumns:true,
            columns:[[
                {field:'u_name',width:150},
            ]],
            onLoadSuccess: function (row, data){
                $('#cg').tree('collapseAll');
            }
        });
        $("#cc").combotree('tree').tree("collapseAll");
    }
    function addUser(){
        var url = "<?php echo U('Discount/add');?>";

        $('#addfm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result=eval('('+result+')');
                if (!result){
                    $.messager.confirm('错误提示','数据已存在，请勿重复添加',function(r){
                        $('#dlg').dialog('close');
                    });
                }else{
                    $('#dlg').dialog('close');        // close the dialog
                    $('#dg2').datagrid('reload');    // reload the user data
                }
            }
        });
    }
    function editUser(){
        var row = $('#dg2').datagrid('getSelected');
        if(row){
            $('#editdlg').dialog('open').dialog('setTitle','编辑折扣');
            $('#cnameid').textbox('setValue',row.customname);
            $('#ckindid').textbox('setValue',row.productkindname);
            $('#cdiscountid').textbox('setValue',row.discount);
            $('#cusid').val(row.customid);
            $('#cproductkindid').val(row.productkindid);

        }else
        {
            $.messager.confirm('错误提示','请选中一行');
        }

    }
    function saveUser(){
        var url = "<?php echo U('Discount/discountSave');?>";
        $('#editfm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result=eval('('+result+')');
                if (!result){
                    $.messager.confirm('错误提示','系统出错',function(r){
                        $('#editdlg').dialog('close');
                    });
                }else{
                    $('#editdlg').dialog('close');        // close the dialog
                    $('#dg2').datagrid('reload');    // reload the user data
                }
            }
        });
    }
    function destroyUser(){
        var row = $('#dg2').datagrid('getSelected');
        if (row){
            $.messager.confirm('删除提示','真的要删除吗?删除将不能再恢复!',function(r){
                if (r){
                    var durl='<?php echo U("Discount/DiscountDelete");?>';
                    $.post(durl,{customid:row.customid,productkindid:row.productkindid},function(result){
                        if (result){
                            $('#dg2').datagrid('reload');    // reload the user data
                        } else {
                            $.messager.alert('错误提示','系统出错','error');
                        }
                    },'json');
                }
            });
        }
    }
    function doSearch2(value,name){
        if(value==''){
            $.messager.alert('搜索提示','搜索内容不能为空!','error');
        }else{
            var searchName=$('#searchBox2').searchbox('getName');
            var searchValue=$('#searchBox2').searchbox('getValue');
            switch(searchName){
                case 'customname':
                    $('#dg2').datagrid({ queryParams:{customname:searchValue}});
                    break;
                case 'productkindname':
                    $('#dg2').datagrid({ queryParams:{productkindname:searchValue}});
                    break;
            }
        }
    }

    //显示全部数据
    function showAll(){
        $('#dg2').datagrid({ queryParams:''});
        $('#skindid').combobox('setValue', '');
        $('#sarea').combobox('setValue', '');
        $('#scustomid').combobox('setValue', '');
    }
    /*$('#cc').combotree({
        url: "<?php echo U('Product/productList');?>",
    });*/
    $('#dg2').datagrid({
        url:"<?php echo U('Discount/discountList');?>",//加载的URL
        isField:"id",
        pagination:true,//显示分页
        pageSize:50,//分页大小
        pageList:[30,40,50,60],//每页的个数
        fit:true,//自动补全
        fitColumns:true,
        rownumbers:true,
        singleSelect:false,
        iconCls:"icon-save",//图标
        toolbar:'#toolbar1',
        columns:[[
            { field:'customid', width:50,sortable:'true',title:'客户编码',checkbox:true,},
            { field:'customname', width:50,sortable:'true',title:'来往单位'},
            { field:'productkindid', width:50,sortable:'true',title:'商品分类id',hidden:'true'},
            { field:'productkindname', width:50,sortable:'true',title:'商品分类'},
            { field:'discount', width:50,sortable:'true',title:'折扣'},
        ]],


    });

    $('#skindid').combobox({
        url:"<?php echo U('Product/getListByOne');?>",
        valueField:'s_fullid',
        textField:'u_name',
        editable:false,
        onSelect:function(){
            $('#dg2').datagrid({ queryParams:queryParams()});
        }
    });
    $('#scustomid').combobox({
        url:"<?php echo U('Units/getByArea');?>",
        method:'get',
        valueField:'s_id',
        textField:'u_name',
        editable:false,
        onSelect:function(){
            $('#dg2').datagrid({ queryParams:queryParams()});
        }
    });
    $('#sarea').combobox({
        url:"<?php echo U('Units/areaList');?>",
        valueField:'s_id',
        textField:'u_name',
        editable:false,
        onSelect: function(area){
        $('#scustomid').combobox({
            url:"<?php echo U('Units/getByArea');?>"+"?id="+area.s_id,
            method:'get',
            valueField:'s_id',
            textField:'u_name',
            editable:false,
            onSelect:function(){
                $('#dg2').datagrid({ queryParams:queryParams()});
            }
        });
            $('#dg2').datagrid({ queryParams:queryParams()});
        }

    });

    function queryParams()
    {

        var str={};
        str['kindid'] =$('#skindid').combobox("getValues");
        str['areaid'] =$('#sarea').combobox("getValues");
        str['customid'] =$('#scustomid').combobox("getValues");
        return str;
    }

</script>
<style type="text/css">
    #fm{
        margin:0;
        padding:10px;
    }
</style>

</body>