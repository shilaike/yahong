<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class UnitsController extends Controller {

    public function unitsList(){
        $query =I('q');
        $map=array();
        $ck=A('CheckInput');
        !empty($query)?$map['u_Name']=array('like','%'.$query.'%'):'';
        $map['s_SonAll']=0;
        $page=$ck->in('当前页','page','intval','1',0,0);
        $rows=$ck->in('每页记录数','rows','intval','',0,0);
        $count=M()->table('Units')->count();
        $info=M()->table('Units')->where($map)->page($page.','.$rows)->field('s_ID,u_Name')->select();
        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }

        echo $this->ajaxReturn($data);
    }
    public function areaList(){
        $list =M()->table('Area')->where(array('s_Layer'=>array('NEQ',0)))->field('s_id,u_Name')->select();
        echo $this->ajaxReturn($list);
    }
    public function getByArea(){
        $id = I('id');
        if(!empty($id)){
            $Area = M()->table('Area')->where(array('s_ID'=>$id))->field('s_fullid,s_layer,u_Name')->find();
            $list=array();
            if(!empty($Area)&&($Area['s_layer']==1))
            {
                $childerArea =M()->table('Area')->where(array('s_ParentID'=>$Area['s_fullid']))->field('s_id')->select();
                $temp =array();
                foreach($childerArea as $val)
                {
                    $temp[]=$val['s_id'];
                }
                $map['Area_ID'] =array('in',$temp);
                $list =M()->table('Units')->where($map)->field('s_id,s_fullid,u_Name')->select();
                echo $this->ajaxReturn($list);
            }
            {
                $list =M()->table('Units')->where(array('Area_ID'=>$id))->field('s_id,s_fullid,u_Name')->select();
                echo $this->ajaxReturn($list);
            }
        }else
        {
            $list =M()->table('Units')->field('s_id,s_fullid,u_Name')->select();
            echo $this->ajaxReturn($list);
        }


    }

}