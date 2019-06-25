<!--<script>
    $(document).ready(function () {

    $("#formId").form.submit(function(e){
        e.preventDefault();
    });
    });
</script>-->
     

<?php
session_start();
//var_dump($_SESSION);die;


    //include the file that loads the PhpSpreadsheet classes
    require 'spreadsheet/vendor/autoload.php';

    //create directly an object instance of the IOFactory class, and load the xlsx file
    /*$fxls ='excel-file_1.xlsx';*/
    /*$fxls = "email subscription-2.xlsx";*/
    $fxls = "tank_status_green_and_nongreen.xlsx";
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fxls);


    //read excel data and store it into an array
    /*$xls_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);*/
    $basin = array();
    $tehsil = array();
    $selTel = array();
    $teh = array();
    $final = array();
    $xls_data = $spreadsheet->getActiveSheet()->toArray(null);

    for($i= 0; $i < sizeof($xls_data); $i++)
    {
        array_push($basin, $xls_data[$i][26]);
        array_push($tehsil, $xls_data[$i][7]);

    }

    $basin_unq = array_values(array_unique($basin, SORT_REGULAR));
//     echo'<pre>';var_dump($xls_data[0]);
    
//    $tehsil_unq = array_values(array_unique($tehsil, SORT_REGULAR));



?>

<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <style>
            .table td
            {
                border: 2px #999999;
                padding:0px;
            }
            .table
            {
                padding:0px;
            }
            .table
            {
                border-collapse: collapse;
            }
            select
            {
                font: inherit;
            }
            table.table-bordered
            {
                border:3px solid black;
                margin-top:20px;
            }
            table.table-bordered > thead > tr > th
            {
                border:3px solid black;
            }
            table.table-bordered > tbody > tr > td
            {
                border:3px solid black;
            }
            .table-bordered>tbody>tr>th, 
            {
                border:3px solid black;
            }
        </style>
    </head>
    <body>
        <div class = conntainer>
            <div class = row style = "background-color:black">
                <center>
                    <h2><b><font color = white>OIIPCRA Project</font></b></h2>
                </center>
            </div>
            <br>
            <div class = row>
                <div class = col-lg-4>
                </div>
                <div class = col-lg-2>
                    <input class = 'form-control' list = 'tank_names' id = 'name_of_tank' name = 'name_of_tank' placeholder = 'Type a tank Name'>
                    <datalist id = tank_names>
                        <?php
                        $dis_block = array();
                        foreach($xls_data as $row)
                        {
                            array_push($dis_block, $row[13]."_".$row[0]);
                        }
                        $dis_block_unq = array_unique($dis_block);
                        
//                        echo '<pre>';var_dump($bas_dis_unq);
                        foreach($dis_block_unq as $opt)
                        {
                            
                        ?>
                        <option id = '<?php echo $opt?>' value = "<?php echo explode("_", $opt)[1]."_".explode("_", $opt)[2];?>"><?php echo explode("_", $opt)[1]."_".explode("_", $opt)[2];?></option>
                        <?php
                        }
                        ?>                        
                    </datalist>                    
                </div>
                <div class = col-lg-2>
                    <button class = "btn btn-primary" onclick = showTankInfo()>Show Tank Info</button>
                </div>
                <div class = col-lg-4>
                </div>
            </div>
            <div class = row>
                <div class = "col-lg-3">
                    <select class = form-control id = basin name = basin onchange = showBasinSummary()>                       
                        <?php
                        for($i= 0; $i < count($basin_unq); $i++)
                        {
                        ?>
                        <option value = <?php echo $basin_unq[$i]?>><?php echo $basin_unq[$i]?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class = "col-lg-3 hidden" id=district>
                    <select class = form-control id = district_sel name = district onchange = showDistrictSummary()>
                        <?php
                        $bas_dis = array();
                        foreach($xls_data as $row)
                        {
                            array_push($bas_dis, $row[26]."_".$row[12]);
                        }
                        $bas_dis_unq = array_unique($bas_dis);
                        
//                        echo '<pre>';var_dump($bas_dis_unq);
                        foreach($bas_dis_unq as $opt)
                        {
                        ?>
                        <option class = "hidden" id = '<?php echo $opt?>' value = <?php echo explode("_", $opt)[1];?>><?php echo explode("_", $opt)[1];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class = "col-lg-3 hidden" id = "block">
                     <select class = form-control id = block_sel name = block_sel onchange = showBlockSummary()>
                        <?php
                        $dis_block = array();
                        foreach($xls_data as $row)
                        {
                            array_push($dis_block, $row[12]."_".$row[13]);
                        }
                        $dis_block_unq = array_unique($dis_block);
                        
//                        echo '<pre>';var_dump($bas_dis_unq);
                        foreach($dis_block_unq as $opt)
                        {
                            
                        ?>
                        <option class = "hidden" id = '<?php echo $opt?>' value = "<?php echo explode("_", $opt)[1];?>"><?php echo explode("_", $opt)[1];?></option>
                        <?php
                        }
                        ?>
                    </select>
                    
                </div>
                <div class = "col-lg-3 hidden" id = "name">
                    <select class = form-control id = name_sel name = name_sel onchange = showNameSummary()>
                        
                        <?php
                        $dis_block = array();
                        foreach($xls_data as $row)
                        {
                            array_push($dis_block, $row[13]."_".$row[0]);
                        }
                        $dis_block_unq = array_unique($dis_block);
                        
//                        echo '<pre>';var_dump($bas_dis_unq);
                        foreach($dis_block_unq as $opt)
                        {
                            
                        ?>
                        <option class = "hidden" id = '<?php echo $opt?>' value = "<?php echo explode("_", $opt)[1]."_".explode("_", $opt)[2];?>"><?php echo explode("_", $opt)[1]."_".explode("_", $opt)[2];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class = row>
                <br>
            </div>
        </div>
        <div class = "hidden container" id = "table_summary">
            <div class = "basin_summarries" id = basin_summary>
                <?php
                $basin_summary = array($basin_unq);
                $summary_arr = array();
                foreach($basin_unq as $b_s)
                {
                            
                    $dw_green = 0;
                    $dw_non_green = 0;
                    $res_green = 0;
                    $res_non_green = 0;
                    
                    foreach($xls_data as $row)
                    {
                                              
                        if($b_s == $row[26] && $row[18] == "D/W" && $row[34] == "Green")
                        {
                            $dw_green-=-1;
                        }
                        else if($b_s == $row[26] && $row[18] == "D/W" && $row[34] == "Red")
                        {
                            $dw_non_green-=-1;
                        }
                        else if($b_s == $row[26] && $row[18] == "Res" && $row[34] == "Green")
                        {
                            $res_green-=-1;
                        }
                        else if($b_s == $row[26] && $row[18] == "Res" && $row[34] == "Red")
                        {
                            $res_non_green-=-1;
                        }
                    }
                    $summ_arr = array($b_s, $dw_green, $dw_non_green, $res_green, $res_non_green);
                    $summary_arr[$b_s] = $summ_arr;                    
                }
                
                foreach($summary_arr as $sum_arr)
                {
                ?>
                <center>
                <div class = "hidden container" id = "<?php echo $sum_arr[0];?>">
                    <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Project Type</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>D/W</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Res</b></h3></center></div>           <div class = col-md-3 style="border: thin solid black"><center><h3><b>Total</b></h3></center></div>                           
                    </div>
                    <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Green</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[1];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[2];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[2] + $sum_arr[1];?></h3></center></div>                           
                    </div>
                    <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Non Green</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[3];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4] + $sum_arr[3];?></h3></center></div> 
                    </div>
                    <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Total</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[3] + $sum_arr[1];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4] + $sum_arr[2];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4] + $sum_arr[2] + $sum_arr[3] + $sum_arr[1] ;?></h3></center></div>                           
                    </div>                    
                </div>
                </center>
                <?php
                }
                ?>
                
            </div>
            <div class = "district_summaries" id = district_summary>
                <?php
                $basin_summary = array($basin_unq);
                $district = array();
                $dis_sum_arr = array();
                foreach($xls_data as $row)
                {
                    array_push($district, $row[12]);
                }
                $district_unq = array_unique($district);
                
                foreach($district_unq as $dis_unq)
                {
                    $dw_green = 0;
                    $dw_non_green = 0;
                    $res_green = 0;
                    $res_non_green = 0;
                    foreach($xls_data as $row)
                    {
                                              
                        if($dis_unq == $row[12] && $row[18] == "D/W" && $row[34] == "Green")
                        {
                            $dw_green-=-1;
                        }
                        else if($dis_unq == $row[12] && $row[18] == "D/W" && $row[34] == "Red")
                        {
                            $dw_non_green-=-1;
                        }
                        else if($dis_unq == $row[12] && $row[18] == "Res" && $row[34] == "Green")
                        {
                            $res_green-=-1;
                        }
                        else if($dis_unq == $row[12] && $row[18] == "Res" && $row[34] == "Red")
                        {
                            $res_non_green-=-1;
                        }
                    }
                    $di_su_ar = array($dis_unq, $dw_green, $dw_non_green, $res_green, $res_non_green);
                    $dis_sum_arr[$dis_unq] = $di_su_ar;                  
                }
//                var_dump($dis_sum_arr);
                
                foreach($dis_sum_arr as $sum_arr)
                {
                ?>
                <div class = "hidden container" id = "<?php echo $sum_arr[0];?>">
                   <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Project Type</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>D/W</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Res</b></h3></center></div>           <div class = col-md-3 style="border: thin solid black"><center><h3><b>Total</b></h3></center></div>                           
                    </div>
                    <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Green</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[1];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[2];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[2] + $sum_arr[1];?></h3></center></div>                           
                    </div>
                    <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Non Green</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[3];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4] + $sum_arr[3];?></h3></center></div> 
                    </div>
                    <div class = row style="border: thin solid black">
                        <div class = col-md-3 style="border: thin solid black"><center><h3><b>Total</b></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[3] + $sum_arr[1];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4] + $sum_arr[2];?></h3></center></div>
                        <div class = col-md-3 style="border: thin solid black"><center><h3><?php echo $sum_arr[4] + $sum_arr[2] + $sum_arr[3] + $sum_arr[1] ;?></h3></center></div>                           
                    </div>
                </div>
                
                <?php
                }
                
                ?>
            </div>
            <div class = "block_summaries" id = block_summary>
                
            </div>
            <div class = "name_summaries container hidden" id = name_summary style="margin-left:-150px;padding-left:0px;">    
                <div class = "row" align="left">
                    <div class = col-sm-2>
                         <?php
                       foreach($xls_data as $row)
                       {?>
                       <table class = "table hidden" id = '<?php echo $row[0];?>' style="float:left; padding:0px; margin:0px;">
                           <tr>
                               <td>S.No</td>
                               <td><?php echo $row[3];?></td>
                           </tr>
                           <tr>
                               <td>Name</td>
                               <td><?php echo $row[0];?></td>
                           </tr>
                           <tr>
                               <td>Project ID</td>
                               <td><?php echo $row[4];?></td>
                           </tr>
                           <tr>
                               <td>Basin</td>
                               <td><?php echo $row[26];?></td>
                           </tr>
                           <tr>
                               <td>District</td>
                               <td><?php echo $row[12];?></td>
                           </tr>
                           <tr>                           
                               <td>Block</td>
                               <td><?php echo $row[13];?></td>
                           </tr>
                           <tr>
                               <td>Gram Panchayat</td>
                               <td><?php echo $row[14];?></td>
                           </tr>
                           <tr>
                               <td>Name MIP</td>
                               <td><?php echo $row[15];?></td>
                           </tr>
                           <tr>
                               <td>Category</td>
                               <td><?php echo $row[17];?></td>         
                           </tr>
                           <tr>
                               <td>Designated CCA K</td>
                               <td><?php echo $row[22];?></td>
                           </tr>
                           <tr>
                               <td>Designated CCA R</td>
                               <td><?php echo $row[23];?></td>
                           </tr>
                           <tr>
                               <td>Certified Ayacut K</td>
                               <td><?php echo $row[24];?></td>
                           </tr>
                           <tr>
                               <td>Certified Ayacut R</td>
                               <td><?php echo $row[25];?></td>
                           </tr>
                           <tr>
                               <td>MI Division</td>
                               <td><?php echo $row[27];?></td>
                           </tr>
                           <tr>
                               <td>WSA Ha</td>
                               <td><?php echo $row[29];?></td>
                           </tr>
                           <tr>
                               <td>Height of Dam</td>
                               <td><?php echo $row[30];?></td>
                           </tr>
                           <tr>
                               <td>Length of Dam</td>
                               <td><?php echo $row[31];?></td>
                           </tr>
                           <tr>
                               <td>Status</td>
                               <td><?php echo $row[34];?></td>
                           </tr>                       
                       </table>
                       <?php
                       }
                       ?>
                    </div>
                
                <div class = col-sm-1></div>
                <div class = col-sm-9>
                    
                  <div id="myCarousel" class="carousel slide container" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                      <li data-target="#myCarousel" data-slide-to="1"></li>
                      <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                      <div class="item active">
                          <center>
                            <img src="" alt="tank_info_1"  id = tank_img width = "750" height="750">
                          </center>
                      </div>

                      <div class="item">
                          <center>
                            <img src="" alt="tank_info_2" id = location_img width = "750" height="750">
                          </center>
                      </div>
                      <div class="item">
                          <center>
                            <img src="" alt="tank_info_3" id = stream_img width = "750" height="750">
                          </center>
                      </div>

<!--
                      <div class="item">
                        <img src="" alt="New york" id = stream_img width = "1000" height="1000">
                      </div>
-->
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
                </div>

                </div>
           

            
        </div>
        </div>
        <script>
            function showBasinSummary()
            {
                document.getElementById('name_of_tank').value = "";
                //hiding all the selects
                document.getElementById("district").className = "col-lg-3 hidden";
                document.getElementById("block").className = "col-lg-3 hidden";
                document.getElementById("name").className = "col-lg-3 hidden";
                document.getElementById("myCarousel").className = "carousel slide container hidden"
                document.getElementById('block_summary').innerHTML = "";

                //hiding all the the district options containers
                dist_sel = document.getElementById("district_sel");
                for(i = 0; i < dist_sel.length; i++)
                {
                    val = dist_sel.options[i].value;
                    if(val != "")
                    {
                        document.getElementById(val).className = "hidden container";
                    }
                }
                
                //hiding all the block options containers
                block_sel = document.getElementById('block_sel');
                for(i = 0; i < block_sel.length; i++)
                {
                   val = block_sel.options[i].value;
                   if(document.getElementById(val) != null)
                   {
                       document.getElementById(val).className = "hidden container";
                   }
                }
                
                //hiding all the name options tables
                for(i = 0; i < name_sel.length; i++)
                {
                    value = name_sel.options[i].value;
                    if(value.charAt(value.length - 1) != "_")
                    {
                        document.getElementById(value).className = "table hidden";   
                    }
                    else
                    {
                        value = value.substr(0, value.length - 1);
                        document.getElementById(value).className = "table hidden";
                    }
                    
                }              
                
                //hiding all the basin options containers
                basin_sel = document.getElementById('basin');
                for(i = 0; i < basin_sel.length; i++)
                {
                    val = basin_sel.options[i].value;
                    if(val != "")
                    {
                        document.getElementById(val).className = "hidden container";
                    }
                }
                document.getElementById('table_summary').className = "hidden container";
                ch_basin_val = document.getElementById('basin').value;
                
                document.getElementById('table_summary').className = "unhidden container";
                document.getElementById(ch_basin_val).className = "unhidden container";
                document.getElementById('district').className = "col-lg-3 unhidden";
                district_sel = document.getElementById('district_sel');
                for(i = 0; i < district_sel.length; i++)
                {
                    district_sel.options[i].className = "hidden";
                }
                for(i = 0; i < district_sel.length; i++)
                {
                    id = district_sel.options[i].id;
                    if(id.split('_')[0] == ch_basin_val)
                    {
                        district_sel.options[i].className = "unhidden";
                    }
                }
                district_sel.options[0].className = "unhidden";
                district_sel.selectedIndex = "0";
                document.getElementById('basin_summary').className = "unhidden basin_summaries";
                document.getElementById('table_summary').className = "unhidden container";
            }
            function showDistrictSummary()
            {   
                document.getElementById('name_of_tank').value = "";
                //hidding all the selects and carousel
                document.getElementById("block").className = "col-lg-3 hidden";
                document.getElementById("name").className = "col-lg-3 hidden";
                document.getElementById("myCarousel").className = "carousel slide container hidden"
                document.getElementById('block_summary').innerHTML = "";
                //hiding all the block options containers
                block_sel = document.getElementById('block_sel');
                for(i = 0; i < block_sel.length; i++)
                {
                   val = block_sel.options[i].value;
                   if(document.getElementById(val) != null)
                   {
                       document.getElementById(val).className = "hidden container";
                   }
                }
                
                //hiding all the name options tables
                for(i = 0; i < name_sel.length; i++)
                {
                    value = name_sel.options[i].value;
                    if(value.charAt(value.length - 1) != "_")
                    {
                        document.getElementById(value).className = "table hidden";   
                    }
                    else
                    {
                        value = value.substr(0, value.length - 1);
                        document.getElementById(value).className = "table hidden";
                    }
                    
                }              
                dist_sel = document.getElementById("district_sel");
                for(i = 0; i < dist_sel.length; i++)
                {
                    val = dist_sel.options[i].value;
                    if(val != "")
                    {
                        document.getElementById(val).className = "hidden container";
                    }
                }
//                document.getElementById("table-summary").className = "hidden container";
                ch_district_val = document.getElementById('district_sel').value;
                document.getElementById("district_summary").className = "district_summaries unidden";
                document.getElementById(ch_district_val).className = "unhidden container";
                document.getElementById('basin_summary').className = "hidden basin_summaries";
            
                
                document.getElementById('block').className = "col-lg-3 unhidden";
                block_sel = document.getElementById('block_sel');
                for(i = 0; i < block_sel.length; i++)
                {
                    block_sel.options[i].className = "hidden";
                }
                for(i = 0; i < block_sel.length; i++)
                {
                    id = block_sel.options[i].id;
                    if(id.split('_')[0] == ch_district_val)
                    {
                        block_sel.options[i].className = "unhidden";
                    }
                }
                block_sel.options[0].className = "unhidden";
                block_sel.selectedIndex = "0";
            }
            function showBlockSummary()
            {
                document.getElementById('block_summary').innerHTML = "";
                //hiding all the selects and caousel
                document.getElementById("name").className = "col-lg-3 hidden";
                document.getElementById("myCarousel").className = "carousel slide container hidden"
                //hiding all the name options tables
                document.getElementById("block_summary").className = "block_summaries unhidden";
                for(i = 0; i < name_sel.length; i++)
                {
                    value = name_sel.options[i].value;
                    if(value.charAt(value.length - 1) != "_")
                    {
                        document.getElementById(value).className = "table hidden";   
                    }
                    else
                    {
                        value = value.substr(0, value.length - 1);
                        document.getElementById(value).className = "table hidden";
                    }
                    
                }              
                dist_sel = document.getElementById("district_sel");
                for(i = 0; i < dist_sel.length; i++)
                {
                    val = dist_sel.options[i].value;
                    if(val != "")
                    {
                        document.getElementById(val).className = "hidden container";
                    }
                }
                ch_district_val = document.getElementById('district_sel').value;
                block_sel = document.getElementById('block_sel');
                for(i = 0; i < block_sel.length; i++)
                {
                   val = block_sel.options[i].value;
                   if(document.getElementById(val) != null)
                   {
                       document.getElementById(val).className = "hidden container";
                   }
                }
                ch_block_val = document.getElementById("block_sel").value;
                document.getElementById('block_summary').className = "block_summaries unhidden";
                
                block_spec_tanks = [];
                for(i = 0; i < name_sel.length; i++)
                {
                    id = name_sel.options[i].id;
                    if(id.split('_')[0] === ch_block_val)
                    {
                        block_spec_tanks.push(name_sel.options[i].value);
                    }
                }
                
                tank_tables = [];
                for(i = 0; i < block_spec_tanks.length; i++)
                {
                    document.getElementById(block_spec_tanks[i]).className = "table table-bordered unhidden";
                    tank_tables.push(document.getElementById(block_spec_tanks[i]));                                                        
                }
                carousel_arr = [];
                for(i = 0; i < block_spec_tanks.length; i++)
                {
                    name_code = block_spec_tanks[i].split("_",1)[0];
                    carousel = "<div id="+i+" class='carousel slide container' data-ride='carousel'><ol class='carousel-indicators'><li data-target=#"+i+" data-slide-to='0' class='active'></li><li data-target=#"+i+" data-slide-to='1'></li><li data-target=#"+i+" data-slide-to='2'></li></ol><div class='carousel-inner'><div class='item active'><center><img src=resources/images/OdishaTanks/tank_district/output_"+name_code+".png alt='Los Angeles'  id = tank_img width = '750' height='750'></center></div><div class='item'><center><img src=resources/images/OdishaTanks/tank_location/output_"+name_code+".png alt='Chicago' id = location_img width = '750' height='750'></center></div><div class='item'><center><img src=resources/images/OdishaTanks/tank_streams/output_"+name_code+".png alt='Chicago' id = location_img width = '750' height='750'></center></div></div><a class='left carousel-control' href=#"+i+" data-slide='prev'><span class='glyphicon glyphicon-chevron-left'></span><span class='sr-only'>Previous</span></a><a class='right carousel-control'  href=#"+i+" data-slide='next'><span class='glyphicon glyphicon-chevron-right'></span><span class='sr-only'>Next</span></a></div>";
                    carousel_arr.push(carousel);
                }
                document.getElementById('block_summary')
                for(i = 0; i < block_spec_tanks.length; i++)
                {
                    document.getElementById('block_summary').innerHTML+= "<div class = container><div class=row><center><h2>"+block_spec_tanks[i]+"</h2></center></div><div class = row><div class = col-lg-1></div><div class = col-lg-2>"+tank_tables[i].outerHTML+"</div><div class = col-lg-1></div><div class = col-lg-8>"+carousel_arr[i]+"</div></div><div class = row><center><hr></center></div></div>";
                }
                
                
                document.getElementById('name').className = "col-lg-3 unhidden";
                name_sel = document.getElementById("name_sel");
                for(i = 0; i < name_sel.length; i++)
                {
                    name_sel.options[i].className = 'hidden';        
                }
                for(i = 0; i < name_sel.length; i++)
                {
                    id = name_sel.options[i].id;
                    if(id.split('_')[0] === ch_block_val)
                    {
                        name_sel.options[i].className = "unhidden";
                    }
                }
                name_sel.options[0].className = "unhidden";
                name_sel.selectedIndex = "0"
            }
            function showNameSummary()
            {
                
                document.getElementById('basin_summary').className = "basin_summarries hidden";
                document.getElementById('district_summary').className = "district_summaries hidden";
                document.getElementById('block_summary').className = "block_summaries hidden";
                document.getElementById('name_summary').className = "name_summaries container unhidden";
                document.getElementById('block_summary').innerHTML = "";
                ch_name_val = document.getElementById("name_sel").value;
                
                name_sel = document.getElementById("name_sel");
                for(i = 0; i < name_sel.length; i++)
                {
                    value = name_sel.options[i].value;
                    
                    if(value.charAt(value.length - 1) != "_")
                    {
                        document.getElementById(value).className = "table hidden";   
                    }
                    else
                    {
                        value = value.substr(0, value.length - 1);
                        document.getElementById(value).className = "table hidden";
                    }
                    
                }              
                document.getElementById("myCarousel").className = "carousel slide container unhidden"
                document.getElementById(ch_name_val).className = "table table-bordered unhidden";
                name_code = ch_name_val.split("_",1)[0];
                document.getElementById('tank_img').src = "resources/images/OdishaTanks/tank_district/output_"+name_code+".png"; 
                document.getElementById('location_img').src = "resources/images/OdishaTanks/tank_location/output_"+name_code+".png"; 
                document.getElementById('stream_img').src = "resources/images/OdishaTanks/tank_streams/output_"+name_code+".png";    
            }
            function showTankInfo()
            {
                
                document.getElementById('name_summary').className = "unhidden container";
                document.getElementById('table_summary').className = "name_summaries container unhidden";
                name_sel = document.getElementById("name_sel");
                document.getElementById('block_summary').innerHTML = "";
                //hiding all the name option containers
                for(i = 0; i < name_sel.length; i++)
                {
                    value = name_sel.options[i].value;
                    
                    if(value.charAt(value.length - 1) != "_")
                    {
                        document.getElementById(value).className = "table hidden";   
                    }
                    else
                    {
                        value = value.substr(0, value.length - 1);
                        document.getElementById(value).className = "table hidden";
                    }
                    
                }
                //hiding all the block options containers
                block_sel = document.getElementById('block_sel');
                for(i = 0; i < block_sel.length; i++)
                {
                   val = block_sel.options[i].value;
                   if(document.getElementById(val) != null)
                   {
                       document.getElementById(val).className = "hidden container";
                   }
                }
                //hiding all the basin options containers
                basin_sel = document.getElementById('basin');
                for(i = 0; i < basin_sel.length; i++)
                {
                    val = basin_sel.options[i].value;
                    if(val != "")
                    {
                        document.getElementById(val).className = "hidden container";
                    }
                }
                //hiding all the district option containers
                dist_sel = document.getElementById("district_sel");
                for(i = 0; i < dist_sel.length; i++)
                {
                    val = dist_sel.options[i].value;
                    if(val != "")
                    {
                        document.getElementById(val).className = "hidden container";
                    }
                }
                dist_sel.options[0].className = "unhidden";
                dist_sel.selectedIndex = "0";
                basin_sel.options[0].className = "unhidden";
                basin_sel.selectedIndex = "0";
                block_sel.options[0].className = "unhidden";
                block_sel.selectedIndex = "0";
                tank_name_val = document.getElementById('name_of_tank').value;
                if(tank_name_val === "")
                {
                    alert("Type a tank name");
                }
                document.getElementById(tank_name_val).className = "table table-bordered unhidden";
                name_code = tank_name_val.split("_",1)[0];
                document.getElementById('myCarousel').className = "carousel slide container unhidden";
                document.getElementById('tank_img').src = "resources/images/OdishaTanks/tank_district/output_"+name_code+".png"; 
                document.getElementById('location_img').src = "resources/images/OdishaTanks/tank_location/output_"+name_code+".png";
                document.getElementById('stream_img').src = "resources/images/OdishaTanks/tank_streams/output_"+name_code+".png";
                
            }
        </script>
    </body>
</html>