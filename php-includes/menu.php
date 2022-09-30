<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main.css">
    <title></title>
</head>

<body>
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Chuyển đổi</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">FairyFirst</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">

            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-message">
                    <li><a href="#"><i class="fa fa-user fa-fw"></i> Hồ sơ </a>
                    </li>
                    <li><a href="#"><i class="fa fa-gear fa-fw"></i> Cài đặt</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Đăng xuất</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="home.php"><i class="fa fa-dashboard fa-fw"></i>Trang chủ</a>
                    </li>
                    <li>
                        <a href="pin-request.php"><i class="fa fa-adjust fa-fw"></i> Pin Yêu Cầu</a>
                    </li>
                    <li>
                        <a href="pin.php"><i class="fa fa-adjust fa-fw"></i>Pin có sẵn</a>
                    </li>
                    <li>
                        <a href="join.php"><i class="fa fa-adjust fa-fw"></i>Đăng kí người mới</a>
                    </li>
                    <li>
                        <a href="tree.php"><i class="fa fa-adjust fa-hub"></i>Phân Nhánh</a>
                    </li>
                    <li>
                        <a href="payment-received-history.php"><i class="fa fa-adjust fa-hub"></i>Lịch sử thanh toán</a>
                    </li>

                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
</body>

</html>