<?php
date_default_timezone_set('asia/saigon');

if ($_FILES)
{
    $tmp = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $dest = uniqid();
    $ext = substr($name, strrpos($name, '.') + 1);
    if ($ext == 'xls')
    {
        $reader_class = 'Excel5';
    }
    else
    {
        $reader_class = 'Excel2007';
    }
    require __DIR__ . '/PHPExcel/Classes/PHPExcel/IOFactory.php';
    $objReader = PHPExcel_IOFactory::createReader($reader_class);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($tmp);
    $worksheetList = $objReader->listWorksheetNames($tmp);

    $old_files = scandir(__DIR__ . '/upload');
    if ($old_files)
    {
        foreach ($old_files as $to_delete)
        {
            if ($to_delete != '.' && $to_delete != '..' && !is_dir($to_delete))
            {
                unlink(__DIR__ . '/upload/' . $to_delete);
            }
        }
    }
    move_uploaded_file($tmp, __DIR__ . '/upload/' . $dest);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test Creator</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css"/>
        <style>
            body{padding: 20px;display:none;}
            iframe{width: 100%;border: none;background: #eee;min-height: 300px;}
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-sm-3">
                <form method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>B1. Chọn file Excel</legend>
                    </fieldset>
                    <div class="form-group">
                        <?php $disabled = $_FILES ? 'disabled' : '' ?>
                        <input type="file" name="file" id="file" accept=".xls,.xlsx" onchange="this.form.submit()" class="form-control" <?php echo $disabled ?>/>
                        <?php if ($_FILES): ?>
                            Bạn đã chọn <span style="color: royalblue"><?php echo $name ?></span>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="col-sm-3">
                <fieldset>
                    <legend>B2. Tùy chỉnh</legend>
                </fieldset>
                <?php if ($_FILES): ?>
                    <form method="post" target="print" action="print.php">
                        <input type="hidden" name="file" value="<?php echo $dest ?>"/>
                        <input type="hidden" name="ext" value="<?php echo $ext ?>"/>
                        <?php foreach ($worksheetList as $sheet): ?>
                            <?php $uid = 'a' . mt_rand(0, 99999) ?>
                            <div class="form-group">
                                <label for="<?php echo $uid ?>"><?php echo $sheet ?></label>
                                <input type="text" name="question_qty[<?php echo $sheet ?>]" placeholder="số câu" class="form-control" id="<?php echo $uid ?>"/>
                            </div>
                        <?php endforeach; ?>
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="/" class="btn btn-default btn-block"><i class="glyphicon glyphicon-triangle-left"></i>Chọn file khác</a>
                            </div>
                            <div class="col-xs-6">
                                <button type="submit" class="btn btn-default btn-block">In bài test<i class="glyphicon glyphicon-triangle-right"></i></button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <div class="col-sm-6">
                <fieldset>
                    <legend>Kết quả</legend>
                    <iframe id="print" name="print"></iframe>
                </fieldset>
            </div>
        </div>

        <script src="js/jquery-1.11.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
        </script>
        <script>
            $(window).resize(function () {
                $('#print').height($(window).height() - $('#print').offset().top - 40);
            });
            $(function () {
                $('body').fadeIn(function () {
                    $(window).trigger('resize');
                });
            });
        </script>
    </body>
</html>

