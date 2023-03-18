<?php

$selectedDate=date("Y-m-d");
if(isset($_GET['selectedDate']))
{
    $selectedDate=date("Y-m-d",strtotime($_GET['selectedDate']));
}
if(isset($_GET['startDate']))
{
    $selectedDate=date("Y-m-d",strtotime($_GET['startDate']));
}
$nextDay=date("Y-m-d",strtotime($selectedDate." +1 day"));
$prevDay=date("Y-m-d",strtotime($selectedDate." -1 day"));
$page = get_option('scw-settings');
if(isset($_GET['startTime']))
{
    $startTime=$_GET['startTime'];
}
else 
{
    $startTime="05:00 PM";
}
if(isset($_GET['endTime']))
{
    $endTime=$_GET['endTime'];
}
else 
{
    
    $endTime="11:00 PM";
}
$newBookingUrl = esc_url_raw(add_query_arg('utm_nooverride', '1', get_permalink($page['scw-booking-page'])));

$roomLists = getAllroomsLiveView();

$tableLists = getAllTableLiveView();

$rest_settings = get_option('scwatbwsr_settings_rest');

$allBookings = findBooking($selectedDate,$startTime,$endTime)

?>

    <div class="maintabs">
        <div class="maintabs-sub">
            <div class="leftside">
                <div class="reservationsec">
                    <div class="reservationtext">RESERVATION</div>
                    <div class="waitingtext">WAITING</div>
                </div>
                <div class="searchguest">
                    <input type="text" placeholder="Search Guest">
                </div>
                <div class="seated">
                    <div class="seatedtext">SEATED</div>
                    <div class="seatedtextnum">         
                        <i class="fa fa-user" aria-hidden="true"></i>
                        18</div>
                </div>
                <?php foreach($allBookings as $b) {?>
                <div class="maintopsec">
                    <div class="leftfirst">
                        <div class="timeinner">6:00 </div>
                        <div class="pm">PM</div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1">John Deo</div>
                        <div  class="leftsecont2">045345345345</div>
                        <div  class="leftsecont3">3 guest / main room</div>
                    </div>
                    <div>
                        <div class="sec12">T1</div>
                    </div>
                </div>
                <?php  } ?>
                <!--
                <div class="maintopsec">
                    <div class="leftfirst">
                        <div>6:00 </div>
                        <div>PM</div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1">John Deo</div>
                        <div  class="leftsecont2">045345345345</div>
                        <div  class="leftsecont3">3 guest / main room</div>
                    </div>
                    <div class="tablend">
                        <div class="sec12">T2</div>
                        <div class="staricon">
                            <i class="fa fa-star" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="maintopsec">
                    <div class="leftfirst">
                        <div>6:00 </div>
                        <div>PM</div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1">John Deo</div>
                        <div  class="leftsecont2">045345345345</div>
                        <div  class="leftsecont3">3 guest / main room</div>
                    </div>
                    <div>
                        <div class="sec12">T1</div>
                    </div>
                </div>
                <div class="maintopsec">
                    <div class="leftfirst">
                        <div>6:00 </div>
                        <div>PM</div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1">John Deo</div>
                        <div  class="leftsecont2">045345345345</div>
                        <div  class="leftsecont3">3 guest / main room</div>
                    </div>
                    <div class="tablend">
                        <div class="sec12">T4</div>
                        <div class="stariconleaves">
                            <i class="fa fa-pagelines leaves" ></i>
                            <i class="fa fa-star leavestar" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="upcomingall">
                    <div class="seatedtext">UPCOMING</div>
                    <div class="seatedtextnum">         
                        <i class="fa fa-user" aria-hidden="true"></i>
                        7</div>
                </div>
                <div class="maintopsecup">
                    <div class="leftfirst">
                        <div class="timeinner">6:00 </div>
                        <div class="pm">PM</div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1">John Deo</div>
                        <div  class="leftsecont2">045345345345</div>
                        <div  class="leftsecont3">3 guest / main room</div>
                    </div>
                    <div>
                        <div class="sec12t4">T4</div>
                    </div>
                </div>
                <div class="maintopsec">
                    <div class="leftfirst">
                        <div>6:00 </div>
                        <div>PM</div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1">John Deo</div>
                        <div  class="leftsecont2">045345345345</div>
                        <div  class="leftsecont3">3 guest / main room</div>
                    </div>
                    <div class="tablend">
                        <div class="sec12t4">T4</div>
                    </div>
                </div>
                -->
            </div>
            <div class="mainside">
                <div class="main">
                    <div class="topheader">
                        <div class="topheader-first">
                            
                            <div class="topheader-select">
                              <div id="time-range">
                                <p>Time Range: <span class="slider-time"><?=$startTime?></span> - <span class="slider-time2"><?=$endTime?></span>

                                </p>
                                <div class="sliders_step1">
                                    <div id="slider-range"></div>
                                </div>
                              </div>
                            </div> 
                            <div class="arrowforward">
                                
                                <span onclick="openDate('Prev')" class="arrowleft"> < </span>
                                    <?php echo date("l d, M Y",strtotime($selectedDate));
                                    echo "<input type='hidden' id='selectedDatePrev' value='".date("Y-m-d",strtotime($prevDay))."' />";
                                    echo "<input type='hidden' id='selectedDateNext' value='".date("Y-m-d",strtotime($nextDay))."' />";
                                    echo "<input type='hidden' id='selectedDateCurrent' value='".date("Y-m-d",strtotime($selectedDate))."' />";
                                    ?>
                                <span onclick="openDate('Next')"  class="arrowright"> >  </span>
                            </div>
                        </div>
                        <div class="topheader-second">

                            <div class="icon1">
                               
                                <div class="dropdown">
                              <button class="dropbtn weeks-option__item"> <i class="fa fa-calendar" aria-hidden="true"></i></button>
                                  <div class="dropdown-content">
                                      <a href="javascript:reportsFilter('today',1)" class="weeks-option__item today">Today</a>
                                      <a href="javascript:reportsFilter('week',1)" class="weeks-option__item active week">Week</a>
                                      <a href="javascript:reportsFilter('month',1)" class="weeks-option__item month">Month</a>
                                      <a href="javascript:reportsFilter('yesterday',1)" class="weeks-option__item yesterday">Yesterday</a>
                                      <a href="javascript:reportsFilter('last_week',1)" class="weeks-option__item last_week">Last Week</a>
                                      <a href="javascript:reportsFilter('last_month',1)" class="weeks-option__item last_month">Last Month</a>
                                      <a href="javascript:openCustomDate(2,1)" class="weeks-option__item 2">Choose Year</a>
                                      <a href="javascript:openCustomDate(1,1)" class="weeks-option__item 1">Choose Date</a>
                           
                                  </div>
                            </div>
                            </div>
                            
                            <div class="icon2">
                                <a href="admin.php?page=scwatbwsr-table-bookings&type=list">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="icon3">
                            <a href="admin.php?page=scwatbwsr-payment-settings">
                           
                                <i class="fa fa-cog" aria-hidden="true"></i>
                                </a>
                            </div>
                            <a href="<?=$newBookingUrl?>" class="resbutton">
                                <div class="icon4">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </div>
                                <div>New Reservation</div>
                            </a>
                        </div>
                    </div>
                    <div class="fulltablemain">
                        <div class="allhead-content">
                            <div class="fulltablecontent">
                                <?php foreach($roomLists as $r){?>
                                <div class="mainroomfull">
                                    <div class="mainroom-text">
                                        <?=$r->roomname?>
                                    </div>
                                    <div class="mainroom-subtext">8/<?=$r->count?></div>
                                </div>
                                <?php  } ?>
                                
                                
                            </div>
                            <div class="fulltablecontentright">
                                <div class="clockavg">
                                    <div >
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    </div>
                                    <div class="avgmintext">
                                        <div class="avg-texta">Avg</div>
                                        <div  class="avg-subtexta"><?=$rest_settings['booking_time']?> min</div>
                                    </div>
                                </div>
                                <div class="clockavg marlef20">
                                    <div >
                                        <i class="fa fa-pie-chart" aria-hidden="true"></i>
                                    </div>
                                    <div class="avgmintext">
                                        <div class="avg-texta">Current Capacity</div>
                                        <div  class="avg-subtexta">60% Full</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tablesize">
                            <?php foreach ($roomLists as $room) { ?>
                            <div class="tablewidth" style="<?="top:".$room->rtop."px;left:".$room->rleft."px;"?>">
                                <?php 
                                $tableLists = getAllTableLiveViewByRoom($room->id);
                                foreach($tableLists as $t){
                                    $tableBookings= findBookingByTable($t->id,$selectedDate,$startTime,$endTime)
                                    ?>
                                <div style="<?="top:".$t->ttop."px;left:".$t->tleft."px;"?>" class="tablet4 <?=findTableClass($t->seats)?> borderright green">
                                   <?php 
                                   for($i=1;$i<=$t->seats;$i++){
                                    ?>
                                    <div class="tablebox-top<?=$i?>"><?=$i?>S</div>
                                    <?php } ?>
                                    <div class="t1_text"><?=$t->label?></div>
                                    <div>
                                        <div class="username">John Deo</div>
                                        <div class="userroll green-color">Vacant</div>
                                    </div>
                                </div>
                                <?php } ?>
                             </div>
                            <?php  } ?>
                           
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
