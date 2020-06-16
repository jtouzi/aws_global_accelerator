<?php
$n = $_GET["n"];
$url = $_GET["url"];

$correctURL = 1;

if (substr($url, -24, 24) != "awsglobalaccelerator.com" && substr($url, -17, 17) != "elb.amazonaws.com") $correctURL = 0;

//IP addresses or DNS of the EC2 instance created
$EC2_SaoPaulo = "";
$EC2_Paris = "";
$EC2_Singapore = "";
$EC2_Ohio = "";

?>
<!DOCTYPE html>
<html>

   <head>
      <title>Test - AWS Global Accelerator</title>
   </head>

<body style="font-size:17px;font-family:'Amazon Ember'">

<table align="center" width="1050" style="margin-top:30px;">
  <tr style="font-size:20px">
    <td align="center" colspan="2" style="color:red;padding:50px">
      Note: This web page is NOT testing performance, it's just to show how AWS Global Accelerator routes requests based on users locations and the Accelerator settings (traffic dials, endpoint weights, failover, etc.). If you want to test performance use real clients, or the Speed Comparison Tool (<a href="https://speedtest.globalaccelerator.aws/#/" target="_blank">https://speedtest.globalaccelerator.aws/#/</a>).
    </td>
  </tr>
  <tr style="font-size:20px">
    <td align="center" colspan="2">
      <form action="" method="get" style="font-size:13px;font-family:'Amazon Ember'">
        Endpoint: <input type="text" name="url" size="40" value="<?php echo $url;?>">&nbsp;&nbsp;&nbsp;
        <select name="n">
        <option value="10">10 requests</option>
        <option value="20">20 requests</option>
        <option value="50">50 requests</option>
        <option value="100">100 requests</option>
      </select>
        <input type="submit">
      </form>
<?php if ($n != "" && $url !="") echo "<p>Endpoint: " .$url. ", ".$n." requests per client</p>"; ?>
    </td>
  </tr>

  <?php
  if ($correctURL != 1) echo "<tr><td align=\"center\" colspan=\"2\">Please enter a valid Global Accelerator (x.awsglobalaccelerator.com) or ALB (x.elb.amazonaws.com) endpoint.</td></tr>";
  else {
  ?>

  <tr style="font-size:25px">
    <td align="left">From Ohio</td>
    <td align="left">From Singapore</td>
  </tr>
  <tr>
    <td align="left" width="50%">
      <iframe src = "http://<?php echo $EC2_Ohio."/ga.php?n=".$n."&url=".$url;?>" style="border:0px;overflow:hidden;height:100px;width:500px;font-family:'Amazon Ember'">
         Sorry your browser does not support inline frames.
      </iframe>
    </td>
    <td align="left" width="50%">
      <iframe src = "http://<?php echo $EC2_Singapore."/ga.php?n=".$n."&url=".$url;?>" style="border:0px;overflow:hidden;height:100px;width:550px;font-family:'Amazon Ember'">
         Sorry your browser does not support inline frames.
      </iframe>
    </td>
  </tr>
  <tr style="font-size:25px">
    <td align="left">From Paris</td>
    <td align="left">From Sao Paulo</td>
  </tr>
  <tr style="font-size:25px;">
    <td align="left" width="50%">
      <iframe src = "http://<?php echo $EC2_Paris."/ga.php?n=".$n."&url=".$url;?>" style="border:0px;overflow:hidden;height:100px;width:500px;font-family:'Amazon Ember';">
         Sorry your browser does not support inline frames.
      </iframe>
    </td>
    <td align="left" width="50%">
      <iframe src = "http://<?php echo $EC2_SaoPaulo."/ga.php?n=".$n."&url=".$url;?>" style="border:0px;overflow:hidden;height:100px;width:500px;font-family:'Amazon Ember'">
             Sorry your browser does not support inline frames.
          </iframe>
    </td>
  </tr>
  <?php
  }
  ?>
 </table>

   </body>

</html>
