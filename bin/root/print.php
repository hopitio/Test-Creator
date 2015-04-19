<?php
require __DIR__ . '/tcpdf/zreport.php';
date_default_timezone_set('asia/saigon');

$pdf = new ZREPORT;
$file = __DIR__ . '/upload/' . $_POST['file'];
$ext = $_POST['ext'];

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
$objPHPExcel = $objReader->load($file);
$worksheetList = $objReader->listWorksheetNames($file);
$question_qty = $_POST['question_qty'];

ob_start();
?>

<?php $question_index = 0; ?>
<?php foreach ($worksheetList as $sheet): ?>
    <?php
    $sheet_data = $objPHPExcel->setActiveSheetIndexByName($sheet)->toArray(NULL, TRUE, TRUE, TRUE);
    shuffle($sheet_data);
    $question_per_sheet = (int) $question_qty[$sheet];
    ?>
    <?php for ($i = 0; $i < $question_per_sheet; $i++): ?>
        <?php
        if (!isset($sheet_data[$i]))
        {
            continue;
        }
        $question = $sheet_data[$i]['A'];
        if (!$question)
        {
            continue;
        }
        $question_index++;
        ?>
        Câu <?php echo $question_index ?>: <?php echo $question ?><br>
        <?php echo str_repeat('<img src="images/line.png" /><br>', 5) ?>
    <?php endfor; ?>
<?php endforeach; ?>



<?php
$html = ob_get_clean();

$pdf->writeHTML($html, true, 0, true, 0);
// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('print.pdf', 'I');


