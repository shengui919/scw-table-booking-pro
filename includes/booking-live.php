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

$allBookings = findBooking($selectedDate,$startTime,$endTime);
$seated = array_filter($allBookings,function($b){
    return ($b->booking_status=="progress");
});
$seatedCounts=0;
if($seated)
$seatedCounts=count($seated);
$statusArr=['trash', 'pending', 'confirmed', 'closed','progress'];
$bookingFreeTable=[];

$findBookingPending=array_filter($allBookings,function($b){
    return ($b->booking_status=="pending");
});
if(!$findBookingPending) $bookingsPending=[];
?>

    <div class="maintabs">
        <div class="maintabs-sub">
            <div class="leftside">
                <div class="reservationsec">
                    <div  data-status="reservation" class="reservationtext">RESERVATION</div>
                    <div data-status="waiting" class="waitingtext">WAITING</div>
                    <div data-status="all" class="alltext">ALL</div>
                </div>
                <div class="searchguest">
                    <input id="myInput" type="text" placeholder="Search Guest">
                </div>
                
                <div class="seated">
                    <div class="seatedtext">SEATED</div>
                    <div class="seatedtextnum">         
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <?=$seatedCounts?></div>
                </div>
                <?php foreach($allBookings as $b) {?>
                <div id="<?=$b->id?>" class="<?=$b->id?> filterrow maintopsec booking-<?=$b->booking_status?>">
                    <div class="leftfirst">
                        <div class="timeinner"><?=date("h:i",strtotime($b->schedule))?> </div>
                        <div class="pm"><?=date("A",strtotime($b->schedule))?></div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1"><?=$b->name?></div>
                        <div  class="leftsecont2"><?=$b->phone?></div>
                        <div  class="leftsecont3"><?=$b->no_seats?> guest / <?=$b->seats?$b->roomname:"<span data-id='$b->id' class='sec12-open'>Select Table</span>"?></div>
                    </div>
                    <div>
                        <div data-id='<?=$b->id?>' class="sec12 live-<?=$b->booking_status?$b->booking_status:'open'?> sec12-<?=$b->tid?$b->tid:'open'?>">T<?=$b->tid?></div>
                    </div>
                </div>
                <?php  } ?>
 
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
                              <button class="dropbtn dropfont weeks-option__item"> <i class="fa fa-calendar" aria-hidden="true"></i></button>
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
                            <a  href="<?=$newBookingUrl?>" class="resbutton">
                                <div class="icon4">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </div>
                                <div class="whitelab">New Reservation</div>
                            </a>
                        </div>
                    </div>
                    <div class="fulltablemain">
                        <div class="allhead-content">
                            <div class="fulltablecontent">
                                <?php 
                                $totalTable=0;
                                foreach($roomLists as $r){
                                    $totalTable =(int) $totalTable+$r->count;
                                    $findBookedTable=array_filter($allBookings,function($b)use($r){
                                        return ($b->roomid==$r->id);
                                    });
                                    $bookedCounts=0;
                                    if($findBookedTable)
                                    {
                                        $bookedCounts=(int) $bookedCounts + count($findBookedTable);
                                    }
                                    ?>
                                <div class="mainroomfull">
                                    <div class="mainroom-text">
                                        <?=$r->roomname?>
                                    </div>
                                    <div class="mainroom-subtext"><?=count($findBookedTable)?>/<?=$r->count?></div>
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
                                        <div  class="avg-subtexta"><?=$totalTable*10?>% Full</div>
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
                                    $tableBookings= findBookingTable($allBookings,$t->id);
                                    if($tableBookings)
                                    $tableBookings =$tableBookings[count($tableBookings)-1];
                                    ?>
                                <div  id="<?=$t->id?>:<?=$room->id?>" style="<?="top:".$t->ttop."px;left:".$t->tleft."px;"?>" class="tablet4 <?=findTableClass($t->seats)?> borderright live-<?=$tableBookings?$tableBookings->booking_status:'open'?>">
                                   <?php 
                                   for($i=1;$i<=$t->seats;$i++){
                                    ?>
                                    <div class="tablebox-top<?=$i?>"><?=$i?></div>
                                    <?php } ?>
                                    <div class="t1_text"><?=$t->label?></div>
                                    <?php if($tableBookings){?>
                                       
                                    <div class="live-<?=$tableBookings->booking_status?>-label">
                                        <div class="username"><?=$tableBookings->name?></div>
                                        <div class="userroll green-color"><?=$tableBookings->booking_status?></div>
                                        <div class="live-filter">
                                        <div class="dropdown">
                                        <button class="dropbtn weeks-option__item"> <i class="fa fa-filter" aria-hidden="true"></i></button>
                                         <div class="dropdown-content">
                                            <?php foreach($statusArr as $s){?>
                                            <a  href="javascript:bookingChangeStatus(<?=$tableBookings->id?>,'<?=$s?>')" class="weeks-option__item <?php echo $s; if($s==$tableBookings->booking_status)echo " st-active";?>"><?=$s?></a>
                                            <?php } ?>
                                         </div>
                                        </div>
                                        </div>
                                    </div>
                                    <?php } else { 
                                        $bookingFreeTable[]=(Object) array("seats"=>$t->id,"roomid"=>$room->id,"roomname"=>$room->roomname,"label"=>$t->label,"price"=>$t->price?$t->price:'0');
                                        ?>
                                        <div class="live--label">
                                        <div class="username"></div>
                                        <div class="userroll green-color">Available</div>
                                    </div>
                                    <?php } ?>
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
<script>
    <?php if(count($bookingFreeTable)>0){?>
    var avaRoomLists=<?php echo  json_encode($bookingFreeTable);?>
    <?php } else { ?>
        var avaRoomLists=[];
    <?php } ?>
</script>
<template id="my-template">
  <swal-title>
    Assign table to booking?
  </swal-title>
  <swal-html>
  <div class="othertext">Rooms</div>
  <?php if(count($bookingFreeTable)>0){
    foreach($bookingFreeTable as $t){
    ?>
    <div data-room="<?=$t->roomname?>" class="roomava-<?=$t->roomid?> standard-outdoor roomselect-6">
                    <div class="stand"><?=$t->roomname?>-<?=$t->label?></div>
                    <button id="price-find-<?=$t->seats?>" data-price="<?=$t->price?>" class="stand-btn" onclick="mysecondtab(<?=$t->seats?>,<?=$t->roomid?>)">Select  <span class="pricetrue">$<?=$t->price?></span></button>
     </div>
    <?php } } else { ?>
        <div class="timebtn" style="opacity: 1; display: block; min-height: 100px; height: 400px;"><button style="width:100%"><span><i class="fa fa-remove time-3-icon"></i></span> All Tables is Booked! </button></div>
    <?php } ?>
  </swal-html>
  
  <swal-button type="cancel">
    Cancel
  </swal-button>
  
</template>
<template id="booking-template">
  <swal-title>
    Assign  booking to table?
  </swal-title>
  <swal-html id="pending-booking">
  <?php foreach($findBookingPending as $b) {?>
                <div id="<?=$b->id?>" class="<?=$b->id?> maintopsec">
                    <div class="leftfirst">
                        <div class="timeinner"><?=date("h:i",strtotime($b->schedule))?> </div>
                        <div class="pm"><?=date("A",strtotime($b->schedule))?></div>
                    </div>
                    <div class="leftsecont"> 
                        <div class="leftsecont1"><?=$b->name?></div>
                        <div  class="leftsecont2"><?=$b->phone?></div>
                        <div  class="leftsecont3"><?=$b->no_seats?> guest</div>
                    </div>
                    <div>
                    <button   class="stand-btn" onclick="setBookingIdforTable(<?=$b->id?>)">Select</button>
                    </div>
                </div>
                <?php  } ?>
  
  </swal-html>
  
  <swal-button type="cancel">
    Cancel
  </swal-button>
  
</template>