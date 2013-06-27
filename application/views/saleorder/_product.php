<div class="minbox">
          <div class="part_search">
           <div class="navbar">
              <div class="navbar-inner">
               <form class="navbar-form" method="post">
                    <font class="myfont" > 菜单编号：</font> <input type="text" class=" span3" name="id" value="">
             &nbsp; &nbsp; <font class="myfont" >菜单名：</font> <input type="text" class="span3" name="menu" value="">
                     <font class="myfont" >菜单类型：</font>  <select id="" name="pid" onchange="" ondblclick="" class="span3" ><option value="1">顶级菜单</option><option value="2">用户管理</option><option value="6">菜单管理</option><option value="12">系统工具</option><option value="13">日志管理</option><option value="17">开发菜单</option><option value="43">权限管理</option></select>
                    <button type="submit" class="btn">&nbsp;&nbsp;搜&nbsp;&nbsp;索&nbsp;&nbsp;</button> 
               </form>
              </div>
            </div>
          </div>
            
        	<table class="table table-bordered table-striped    table-hover">
            	<thead>
                	<tr class="info">
                    	<th  class="table-textcenter">编号</th>
                        <th  class="table-textcenter">菜单名称</th>
                        <th  class="table-textcenter">链接</th>
                        <th  class="table-textcenter">节点</th>
                        <th  class="table-textcenter">备注</th>
                        <th  class="table-textcenter">上级节点</th>
                        <th  class="table-textcenter">类型</th>
                        <th  class="table-textcenter">排序</th>
                        <th  class="table-textcenter">状态</th>
                        <th  class="table-textcenter">操作</th>
                  </tr>
                </thead>
                <tbody>
                 <tr>
                		
                        <td class="table-textcenter">1</td>
                        <td class="table-textcenter">顶级菜单</td>
                        <td class="table-textcenter">#</td>
                        <td class="table-textcenter"></td>
                        <td class="table-textcenter"></td>
                        <td class="table-textcenter">1</td>
                        <td class="table-textcenter">0</td>
                        <td class="table-textcenter">0</td>
                        
                        <td class="table-textcenter">
                                                         <i class="icon-remove"></i>                        </td>
                        <td class="table_config table-textcenter doconfirm">
                                                        <a  href="#" url="/model/index.php/Menu/menuForbind/id/1/status/1" >启用</a>                            <a data-toggle="modal" href="/model/index.php/Menu/menuEdit/id/1" >修改</a>
                            <a href='#' url="/model/index.php/Menu/menuDelete/id/1">删除</a>                        </td>
                	</tr><tr>
                		
                        <td class="table-textcenter">2</td>
                        <td class="table-textcenter">用户管理</td>
                        <td class="table-textcenter">#</td>
                        <td class="table-textcenter"></td>
                        <td class="table-textcenter"></td>
                        <td class="table-textcenter">1</td>
                        <td class="table-textcenter">1</td>
                        <td class="table-textcenter">0</td>
                        
                        <td class="table-textcenter">
                            <i class=" icon-ok"></i>                                                    </td>
                        <td class="table_config table-textcenter doconfirm">
                          <a  href="#" url="/model/index.php/Menu/menuForbind/id/2/status/0" >禁用</a>
                                                      <a data-toggle="modal" href="/model/index.php/Menu/menuEdit/id/2" >修改</a>
                            <a href='#' url="/model/index.php/Menu/menuDelete/id/2">删除</a>                        </td>
                	</tr>            
                </tbody>
            </table> 
</div>