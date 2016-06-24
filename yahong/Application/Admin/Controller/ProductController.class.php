<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class ProductController extends Controller {

    public function productList(){
        $node=array();
        $parentid ='00000';
        $node=array_filter($this->getParentList($parentid));
        $this->ajaxReturn($node);
    }

    public function getParentList($parentId){
        $row=M()->table('Product')->where(array('s_ParentID'=>$parentId,'s_SonAll'=>array('NEQ',0)))->field('s_ID,s_FullID,s_ParentID,u_Name')->select();
        if(!empty($row[0])){

            foreach($row as $key=>$val)
            {
                $row[$key]['id']=$val['s_fullid'];
                $row[$key]['text']=$val['u_name'];

                $row[$key]['children'] =$this->getParentList($row[$key]['s_fullid']);
                if(empty($row[$key]['children'])){
                    unset($row[$key]['children']);
                }
                unset($row[$key]['s_id']);
                unset($row[$key]['s_fullid']);
                unset($row[$key]['s_parentid']);
                unset($row[$key]['u_name']);
                unset($row[$key]['row_number']);
            }
        }
        return  $row;
    }
    public function getListByOne(){
        $row=M()->table('Product')->where(array('s_SonAll'=>array('NEQ',0)))->field('s_ID,s_FullID,s_ParentID,u_Name')->select();
        $this->ajaxReturn($row);
    }

}