<div class="row">
    <div class="span3">
        <div class="account-container">
            <div class="account-avatar">
                <img src="../../../upload/headshot/headshot.png" alt="" class="thumbnail" />
            </div> <!-- /account-avatar -->
            <div class="account-details">
                <span class="account-name">张三</span>
                <span class="account-role">库存管理员</span>
                            <span class="account-actions">
                                <a href="javascript:;">账户属性</a> |
                                <a href="javascript:;">编辑设置</a>
                            </span>
            </div> <!-- /account-details -->
        </div> <!-- /account-container -->
        <hr />
        <?php
        $mname = "account";
        include("../../common/leftmenu.php") ?>
        <hr />
        <div class="sidebar-extra">
            <p></p>
        </div> <!-- .sidebar-extra -->
        <br />
    </div> <!-- /span3 -->
    <div class="span9">
        <h1 class="page-title">
            <i class="icon-home"></i>
            系统维护 / 账户管理
        </h1>
        <div class="toolbar">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span5 current-page">
                        <h4>操作</h4>
                        <p></p>
                    </div>
                    <div class="span7 action-buttons">
                        <a href="#"><img alt="" src="../../../public/img/toolbar/Add.png"><br>添加帐户</a>
                        <a href="#"><img alt="" src="../../../public/img/toolbar/Attach.png"><br>Files</a>
                        <a href="#"><img alt="" src="../../../public/img/toolbar/Back.png"><br>Gallery</a>
                        <a href="#"><img alt="" src="../../../public/img/toolbar/Cancel.png"><br>Calendar</a>
                        <a href="#"><img alt="" src="../../../public/img/toolbar/Brush.png"><br>Grid</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <form method="POST" class="form-inline">
                <div class="span5">
                    <label>
                        账户组
                        <select class="span2">
                            <option value="1" selected="selected">组1</option>
                            <option value="2">组2</option>
                            <option value="5">组3</option>
                            <option value="6">组4</option>
                        </select>
                    </label>
                </div>
                <div class="span4">
                    <label class="pull-right">Search: <input type="text"></label>
                </div>
            </form>
        </div>
        <div class="widget widget-table">
            <div class="widget-header">
                <i class="icon-th-list"></i>
                <h3>账户列表</h3>
            </div> <!-- /widget-header -->
            <div class="widget-content">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>相片</th>
                        <th>账户名称</th>
                        <th>真实姓名</th>
                        <th>固定电话</th>
                        <th>移动电话</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td><img src="../../../upload/headshot/headshot.png"  alt="" class="thumbnail smallImg" /></td>
                        <td>Michael</td>
                        <td>Jordan</td>
                        <td>@mjordan</td>
                        <td>Chicago Bulls</td>
                        <td class="action-td">
                            <a href="javascript:;" class="btn btn-small btn-warning">
                                <i class="icon-ok"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-small">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><img src="../../../upload/headshot/headshot.png"  alt="" class="thumbnail smallImg" /></td>
                        <td>Magic</td>
                        <td>Johnson</td>
                        <td>@mjohnson</td>
                        <td>Los Angeles Lakers</td>
                        <td class="action-td">
                            <a href="javascript:;" class="btn btn-small btn-warning">
                                <i class="icon-ok"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-small">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><img src="../../../upload/headshot/headshot.png"  alt="" class="thumbnail smallImg" /></td>
                        <td>Charles</td>
                        <td>Barkley</td>
                        <td>@cbarkley</td>
                        <td>Phoenix Suns</td>
                        <td class="action-td">
                            <a href="javascript:;" class="btn btn-small btn-warning">
                                <i class="icon-ok"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-small">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><img src="../../../upload/headshot/headshot.png"  alt="" class="thumbnail smallImg" /></td>
                        <td>Karl</td>
                        <td>Malone</td>
                        <td>@kmalone</td>
                        <td>Utah Jazz</td>
                        <td class="action-td">
                            <a href="javascript:;" class="btn btn-small btn-warning">
                                <i class="icon-ok"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-small">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><img src="../../../upload/headshot/headshot.png"  alt="" class="thumbnail smallImg" /></td>
                        <td>David</td>
                        <td>Robinson</td>
                        <td>@drobinson</td>
                        <td>San Antonio Spurs</td>
                        <td class="action-td">
                            <a href="javascript:;" class="btn btn-small btn-warning">
                                <i class="icon-ok"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-small">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td><img src="../../../upload/headshot/headshot.png" alt="" class="thumbnail smallImg" /></td>
                        <td>Reggie</td>
                        <td>Miller</td>
                        <td>@rmiller</td>
                        <td>Indiana Pacers</td>
                        <td class="action-td">
                            <a href="javascript:;" class="btn btn-small btn-warning">
                                <i class="icon-ok"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-small">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td><img src="../../../upload/headshot/headshot.png" alt="" class="thumbnail smallImg" /></td>
                        <td>Clyde</td>
                        <td>Drexler</td>
                        <td>@cdrexler</td>
                        <td>Portland Trail Blazers</td>
                        <td class="action-td">
                            <a href="javascript:;" class="btn btn-small btn-warning">
                                <i class="icon-ok"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-small">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td><img src="../../../upload/headshot/headshot.png" style="width:30px;height:30px;" alt="" class="thumbnail" /></td>
                        <td>Hakeem</td>
                        <td>Olajuwon</td>
                        <td>@holajuwon</td>
                        <td>Houston Rockets</td>
                        <td class="action-td">
                            <a class="btn btn-primary btn-mini">编辑</a>
                            <a class="btn btn-danger btn-mini delete" >删除</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div> <!-- /widget-content -->
            <div class="pagination pagination-right">
                <ul>
                    <li><a href="tables.html#">Prev</a></li>
                    <li><a href="tables.html#">1</a></li>
                    <li class="active"><a href="tables.html#">2</a></li>
                    <li><a href="tables.html#">3</a></li>
                    <li><a href="tables.html#">4</a></li>
                    <li><a href="tables.html#">Next</a></li>
                </ul>
            </div>
        </div> <!-- /widget -->
    </div> <!-- /span9 -->
</div> <!-- /row -->



