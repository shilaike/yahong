<?php if (!defined('THINK_PATH')) exit();?>
<body>
<table id="dg1"></table>

<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="saveDelivery()">保存</a>
    <form id="fmSearch" method="post" novalidate style="display:inline-block;margin-left:50px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon icon-all" plain="true" onclick="showAll()">全部</a>
        <input id='searchBox' class="easyui-searchbox" data-options="height:30,menu:'#searchboxSelect',searcher:doSearch"  style="width:300px"/>
        <div id="searchboxSelect">
            <div data-options="name:'uname'">往来单位</div>
        </div>

    </form>
</div>

<script type="text/javascript">


    //搜索
    function doSearch(value,name){

            var searchName=$('#searchBox').searchbox('getName');
            var searchValue=$('#searchBox').searchbox('getValue');
            switch(searchName){
                case 'uname':
                    $('#dg1').datagrid({ queryParams:{uname:searchValue}});
                    break;
                case 'deliveryid':
                    $('#dg1').datagrid({ queryParams:{deliveryid:searchValue}});
                    break;
            }

    }

    //显示全部数据
    function showAll(){
        $('#dg1').datagrid({ queryParams:''});
    }
    $('#dg1').datagrid({
        url:"<?php echo U('Delivery/deliveryMainList');?>",//加载的URL
        isField:"id",
        pagination:true,//显示分页
        pageSize:5,//分页大小
        pageList:[5,10,15,20],//每页的个数
        fit:true,//自动补全
        fitColumns:true,
        rownumbers:true,
        singleSelect:true,
        iconCls:"icon-save",//图标
        toolbar:'#toolbar',
        columns:[[
            { field:'billdate', width:50,sortable:'true',title:'单据日期'},
            { field:'billtime', width:50,sortable:'true',title:'时间'},
            { field:'billsn', width:50,sortable:'true',title:'单据编号'},
            { field:'abst', width:50,sortable:'true',title:'摘要'},
            { field:'unitName', width:50,sortable:'true',title:'往来单位'},
            { field:'abst2', width:50,sortable:'true',title:'经手人'},
        ]],
        view: detailview,
        detailFormatter:function(index,row){
            return '<div class="ddv" style="padding:5px 0"></div>';
        },
        onExpandRow: function(index,row){
            var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
            ddv.panel({
                border:true,
                cache:false,
                href:"<?php echo U('Delivery/deliveryDetail');?>"+'?id='+row.autoid+'&unitid='+row.unit_id,
                onLoad:function(){
                    $('#dg1').datagrid('fixDetailRowHeight',index);
                }
            });
            $('#dg1').datagrid('fixDetailRowHeight',index);
        }
    });

    function saveDelivery(){
        var row = $('#dg1').datagrid('getSelected');
        if (row){
            $.messager.confirm('保存提示','是否保存!',function(r){
                if (r){
                    var durl='<?php echo U("Delivery/deliverySave");?>';
                    $.post(durl,{id:row.autoid,unitid:row.unit_id},function(result){
                        if (result){
                            $('#dg1').datagrid('reload');    // reload the user data
                        } else {
                            $.messager.alert('错误提示','系统出错','error');
                        }
                    },'json');
                }
            });
        }else
        {
            $.messager.confirm('保存提示','请选中一行!');
        }
    }

</script>
<style type="text/css">
    #fm{
        margin:0;
        padding:10px;
    }
</style>

</body>