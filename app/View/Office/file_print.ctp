<style>
.modal-dialog{margin: 200px auto;}  
.btnstyle {background:#286090;color:#fff;width:100px;height:35px;}
</style>
    
<!-- 模态框（Modal） -->

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times; </button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php  echo $filearr['uname'].' - '.$filearr['name'].' - 附件'; ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php 
                $fileurlArr = explode('|',$filearr['url']);
                $add_dirstr = empty($filearr['type']) ? '' : $filearr['type'];
                foreach($fileurlArr as $filev){
                echo "<a href='/files/$add_dirstr$filev' target='$filev'>".$filev.'</a> <br/>';
                } 
                ?>                                           
            </div>
            <div class="modal-footer">
                <button type="button " class='btnstyle' style='border:1px solid #204d74;border-radius: 4px; ' data-dismiss="modal">关闭</button>
            </div>

                             
