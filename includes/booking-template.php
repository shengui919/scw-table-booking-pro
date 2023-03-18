
<input type="hidden" value="<?php echo esc_attr(SCWATBWSR_URL) ?>" class="scwatbwsr_url">
<input type="hidden" value="" id="time_hidden" />
<input type="hidden" value="" id="no_people_hidden" />
<input type="hidden" value="0" class="scwatbwsr_total_value" />
<input type="hidden" value="0" class="scwatbwsr_form_room_input" />
<input type="hidden" value="0" class="scwatbwsr_form_tabel_input" />
    <div class="maintabs" >
        <div class="maintabs-sub">
            <div class="topsection" id="topsectionid">
                <div class="tabcontent">
                    <div onclick="mymainfirsttab()" id="flifirsttabone">
                        <div class="flextable" >
                            <div class="flextable-toptext flxcolors">1.</div>
                            <div class="flextable-bottomtext flxcolors">Find a Table</div>
                        </div>
                    </div>

                    <div id="flifirsttab">
                    <div class="flextabledf" onclick="mymainfirsttab()" >
                        <div class="flextable-toptext flxcolors">&#10003;
                            </div>
                        <div class="flextable-bottomtext flxcolors">Find a Table</div>
                    </div>
                </div>
                    <div class="flextable2" id="bgtabcol">
                        <div class="flextable-toptext">2.</div>
                        <div class="flextable-bottomtext">Your Details</div>
                    </div>
                </div>
                <div  id="sectioncon">
                <div class="selectcontent">
                    <div class="select1">
                        <div class="peopleset people_number_selected"></div>
                        <svg width="8" height="6" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="ntd"><path d="M4 3.414L6.475.94a.5.5 0 0 1 .707 0l.707.707a.5.5 0 0 1 0 .708L5.061 5.182l-.707.707a.5.5 0 0 1-.708 0L.111 2.354a.5.5 0 0 1 0-.708L.818.94a.5.5 0 0 1 .707 0L4 3.414z" fill="#333" fill-rule="evenodd"></path></svg>
                        <select name="peopleset" class="people_number bornone">
                            <?php for($i=1;$i<30;$i++)
                            {
                              echo "<option value='$i'>$i people</option>";
                            } 
                            ?>
                        </select>
                    </div>
                    <div class="select2">
                        <div class="peopleset booking_date_selected">Aug 6</div>
                        <svg width="8" height="6" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="ntd"><path d="M4 3.414L6.475.94a.5.5 0 0 1 .707 0l.707.707a.5.5 0 0 1 0 .708L5.061 5.182l-.707.707a.5.5 0 0 1-.708 0L.111 2.354a.5.5 0 0 1 0-.708L.818.94a.5.5 0 0 1 .707 0L4 3.414z" fill="#333" fill-rule="evenodd"></path></svg>
                        <select class="bornone booking_date">
                            <?php 
                            $bookingdays = bookingDates();
                            foreach($bookingdays as $k=>$val)
                            {
                             echo "<option value='$k'>$val</option>";
                            } 
                            ?>
                            
                        </select>
                    </div>
                    <div class="select3">
                        <div class="peopleset booking_time_selected">2.00 am</div>
                        <svg width="8" height="6" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="ntd"><path d="M4 3.414L6.475.94a.5.5 0 0 1 .707 0l.707.707a.5.5 0 0 1 0 .708L5.061 5.182l-.707.707a.5.5 0 0 1-.708 0L.111 2.354a.5.5 0 0 1 0-.708L.818.94a.5.5 0 0 1 .707 0L4 3.414z" fill="#333" fill-rule="evenodd"></path></svg>
                        <select class="bornone booking_time">
                            <?php 
                            $times = bookingTimes(@$options_rest['booking_time']?$options_rest['booking_time']:30);
                            foreach($times as $k=>$val)
                            {
                             echo "<option value='$val'>$val</option>";
                            } 
                            ?>
                        </select>
                    </div>
                    <button  class="findtable">Find a table</button>
                </div>
                <p class="Selected_Time selected_date_time_text"><p>
                <div class="timebtn">
                 
                </div>
            </div>
                <div class="secondtab" id="secondidtabs">
                   <form method="POST" action="#" id="scw-booking-form">
                    <div class="ability">Due to limited availability, we can hold this table for you for <span>4:42 minutes</span> </div>
                    <div class="forminput">
                        <div class="forminputmain">
                        <div class="forminput1">
                            <div class="firstnameinput pad20 ">
                                <input type="text" required name="scwatbwsr_form_name_input" placeholder="First name" class="firstname scwatbwsr_form_name_input">
                            </div>
                            <div class="lastnameinput pad20 ">
                                <input type="text" required name="scwatbwsr_form_name_last_input" placeholder="Last name" class="lastname scwatbwsr_form_name_last_input">
                            </div>
                            <div class="lastnameinput pad20 ">
                                
                                <input type="text" required name="scwatbwsr_form_phone_input" placeholder="Phone number" class="phone scwatbwsr_form_phone_input">
                            </div>
                            <div class="emailinput pad20 ">
                                <input type="email" required name="scwatbwsr_form_email_input" placeholder="email" class="email scwatbwsr_form_email_input">
                            </div>
                            <div class="spacialinput pad20 ">
                                <input type="text" required name="scwatbwsr_form_address_input" placeholder="address" class="special scwatbwsr_form_address_input">
                            </div>
                            <div class="spacialinput pad20 ">
                                <input type="text" name="scwatbwsr_form_note_input" placeholder="special request" class="special scwatbwsr_form_note_input">
                            </div>
                        </div>
                        <div class="checkboxsection">
                            <input type="checkbox">
                            <div class="checkbox-text">Yes, I want to get text updates and reminders about my reservations.*</div>
                        </div>
                        <div class="checkboxsection">
                            <input type="checkbox">
                            <div class="checkbox-text">Sign me up to receive dining offers and news from this restaurant by email.</div>
                        </div>
                        <div class="checkboxsection">
                            <input type="checkbox">
                            <div class="checkbox-text">Sign me up to receive dining offers and news from <?=$options_rest['restaurant_name']?> by email.</div>
                        </div>
                        </div>
                        <div class="forminput2">
                            <div class="cafetext"><?=$options_rest['restaurant_name']?></div>
                            <div class="cafetextsub">
                                    <svg style="width:26px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                    focusable="false">
                                    <g fill="none" fill-rule="evenodd">
                                        <path 
                                            d="M13,11 L14.5,11 C14.7761424,11 15,11.2238576 15,11.5 L15,12.5 C15,12.7761424 14.7761424,13 14.5,13 L12.5,13 L11.5,13 C11.2238576,13 11,12.7761424 11,12.5 L11,7.5 C11,7.22385763 11.2238576,7 11.5,7 L12.5,7 C12.7761424,7 13,7.22385763 13,7.5 L13,11 Z M12,21 C7.02943725,21 3,16.9705627 3,12 C3,7.02943725 7.02943725,3 12,3 C16.9705627,3 21,7.02943725 21,12 C21,16.9705627 16.9705627,21 12,21 Z M12,19 C15.8659932,19 19,15.8659932 19,12 C19,8.13400675 15.8659932,5 12,5 C8.13400675,5 5,8.13400675 5,12 C5,15.8659932 8.13400675,19 12,19 Z"
                                            fill="#2D333F"></path>
                                    </g>
                                </svg>
                               <span id="span_booking_date"> Tuesday, March 14 </span></div>
                            <div class="cafetextsub">
                                    <svg style="width:26px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                    focusable="false">
                                    <g fill="none" fill-rule="evenodd">
                                        <path 
                                            d="M13,11 L14.5,11 C14.7761424,11 15,11.2238576 15,11.5 L15,12.5 C15,12.7761424 14.7761424,13 14.5,13 L12.5,13 L11.5,13 C11.2238576,13 11,12.7761424 11,12.5 L11,7.5 C11,7.22385763 11.2238576,7 11.5,7 L12.5,7 C12.7761424,7 13,7.22385763 13,7.5 L13,11 Z M12,21 C7.02943725,21 3,16.9705627 3,12 C3,7.02943725 7.02943725,3 12,3 C16.9705627,3 21,7.02943725 21,12 C21,16.9705627 16.9705627,21 12,21 Z M12,19 C15.8659932,19 19,15.8659932 19,12 C19,8.13400675 15.8659932,5 12,5 C8.13400675,5 5,8.13400675 5,12 C5,15.8659932 8.13400675,19 12,19 Z"
                                            fill="#2D333F"></path>
                                    </g>
                                </svg>
                                <span id="span_booking_time">7:15 pm</span></div>
                                <div class="cafetextsub">
                                    <svg style="width:26px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                    focusable="false">
                                    <g fill="none" fill-rule="evenodd">
                                        <path 
                                            d="M13,11 L14.5,11 C14.7761424,11 15,11.2238576 15,11.5 L15,12.5 C15,12.7761424 14.7761424,13 14.5,13 L12.5,13 L11.5,13 C11.2238576,13 11,12.7761424 11,12.5 L11,7.5 C11,7.22385763 11.2238576,7 11.5,7 L12.5,7 C12.7761424,7 13,7.22385763 13,7.5 L13,11 Z M12,21 C7.02943725,21 3,16.9705627 3,12 C3,7.02943725 7.02943725,3 12,3 C16.9705627,3 21,7.02943725 21,12 C21,16.9705627 16.9705627,21 12,21 Z M12,19 C15.8659932,19 19,15.8659932 19,12 C19,8.13400675 15.8659932,5 12,5 C8.13400675,5 5,8.13400675 5,12 C5,15.8659932 8.13400675,19 12,19 Z"
                                            fill="#2D333F"></path>
                                    </g>
                                </svg>
                                <span id="span_people">2</span> People (Standard seating)</div>
                                <div class="cafetextsub">
                                    <svg style="width:26px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                    focusable="false">
                                    <g fill="none" fill-rule="evenodd">
                                        <path 
                                            d="M13,11 L14.5,11 C14.7761424,11 15,11.2238576 15,11.5 L15,12.5 C15,12.7761424 14.7761424,13 14.5,13 L12.5,13 L11.5,13 C11.2238576,13 11,12.7761424 11,12.5 L11,7.5 C11,7.22385763 11.2238576,7 11.5,7 L12.5,7 C12.7761424,7 13,7.22385763 13,7.5 L13,11 Z M12,21 C7.02943725,21 3,16.9705627 3,12 C3,7.02943725 7.02943725,3 12,3 C16.9705627,3 21,7.02943725 21,12 C21,16.9705627 16.9705627,21 12,21 Z M12,19 C15.8659932,19 19,15.8659932 19,12 C19,8.13400675 15.8659932,5 12,5 C8.13400675,5 5,8.13400675 5,12 C5,15.8659932 8.13400675,19 12,19 Z"
                                            fill="#2D333F"></path>
                                    </g>
                                </svg>
                                <!--1201 Collier Rd NW Atlanta, GA, 30318-2301--> 
                                <?=@$options_rest['restaurant_address']?>  
                                </div>
                                <div class="whatdo">What to know before you go </div>
                                <div class="importanttext">
                                    <div class="important-bold">Important dining information</div>
                                    <p class="important-boldtext">We have a 10 minute grace period. Please call us if you are running later than 10 minutes after your reservation time. </p>
                                </div>
                                <p class="important-boldtext">We may contact you about this
reservation, so please ensure your email
and phone number are up to date. </p>
                                <div class="importanttext">
                                    <div class="important-bold">A note from the restaurant</div>
                                    <p class="important-boldtext">Thank you for choosing <?=$options_rest['restaurant_name']?>. Should
your plans change, please let us know. We
look forward to serving you.. </p>
                                </div>
                        </div>
                    </div>
                        <button type="submit" class="confirmbtn">Confirm reservation</button>
                        <div class="privacytext">*Standard text message rates may apply. You can opt out of receiving text messages at any time. By selecting "Confirm reservation" you are agreeing to the terms and conditions of the <span>OpenTable User Agreement</span> and <span>Privacy Policy.</span> </div>
                        <div class="privacytext">Certain U.S. consumers may have additional data rights, which can be exercised by clicking <span>Do Not Sell or Share My Personal Information. </span></div>
                        </form>
                    </div>
            </div>
            <div  id="myPopup" >
                <div class="topbacksection">
                <div class="closebtn" onclick="mybackfn()">
                    <div class="closearrow"> <span>
                            < </span>

                    </div>
                    <div class="closeback">Back</div>
                </div>
                <div class="seat-text">Select a seating type</div>
                <div class="seat-textsub">The following option are available for a reservation on <span id="booking_time_span"></span>.
                </div>
                <div class="othertext">Rooms</div>
                <?php $roomLists = getAllroomsLiveView();
                foreach($roomLists as $room){
                     $tableLists = getAllTableLiveViewByRoom($room->id);
                    foreach($tableLists as $t){
                        $price=getTablePrices($t->id);
                ?>
                <div data-room="<?=$room->roomname?>" class="roomava-<?=$room->id?> standard-outdoor roomselect-<?=$room->id?>">
                    <div class="stand"><?=$room->roomname."-".$t->label?></div>
                    <button id="price-find-<?=$t->id?>" data-price="<?=$price?$price->price:"0.00"?>" class="stand-btn" onclick="mysecondtab(<?=$t->id?>,<?=$room->id?>)">Select  <span class="pricetrue"><?=$price?"$".$price->price:"Free"?></span></button>
                </div>
                <?php } } ?>
                <div class="standard-outdoor br-btm" style="display:none">
                    
                </div>
                <div id="nodata-table">
               
                
                </div>
                <div id="booking-continue">
                <button onclick="mysecondtab(0)" class="confirmbtn">Continue Booking</button>
                </div>
                </div>
            </div>
        </div>
    </div>
