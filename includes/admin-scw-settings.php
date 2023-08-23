<?php
$getTypesSql = $wpdb->prepare("SELECT * from {$typesTB} where roomid=%d", 0);
$types = $wpdb->get_results($getTypesSql);
$nowtime = date("Y-m-d H:i:s");
$wpdb->query(
    $wpdb->prepare(
        "UPDATE $tableSchedules SET status=%d  WHERE schedule <= %s",
        0,
        $nowtime
    )
);
$getScheSql = $wpdb->prepare(
    "SELECT * from {$tableSchedules} where roomid=%d and status=%d",
    0,
    1
);
$schedules = $wpdb->get_results($getScheSql);
$getdailiesSql = $wpdb->prepare(
    "SELECT * from {$tableDailySchedules} where roomid=%d",
    0
);
$dailies = $wpdb->get_results($getdailiesSql);
if (isset($dailies[0]->daily)) {
    $dailies = explode(",", $dailies[0]->daily);
} else {
    $dailies = [];
}

$getTimesSql = $wpdb->prepare(
    "SELECT * from {$tableDailyTimes} where roomid=%d",
    0
);
$times = $wpdb->get_results($getTimesSql);
?>
<div class="wrap">
    <div class="scwatbwsr_content">
        <div><?= settings_errors() ?></div>
    </div>
    <div class="scwatbwsr_content mb-3">
        <?php adminMenuPage(); ?>
    </div>
    <div class="rooms_area scwatbwsr_content pd-10">

        <div class="scwatbwsr_content">
            <input type="hidden" value="<?php echo esc_attr(
                get_option("date_format")
            ); ?>" class="scw_date_format">

            <div class="scwatbwsr_rooms">

                <div class="scwatbwsr_room">

                    <input class="scwatbwsr_room_id" value="0" type="hidden">

                    <div class="scwatbwsr_room_content">

                        <div class="disfleitem">

						<div class="scwatbwsr_room_content_tabs tabmaxwidth">

                            <input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab1" type="radio"
                                name="scwatbwsr_tabs1">
                            <label class="scwatbwsr_room_content_tabs_label <?= getActiveClass(
                                1,
                                "scwatbwsr_tab1"
                            ) ?>"
                                for="scwatbwsr_tab1"><i
                                    class="fa fa-cog"></i><span><?php echo esc_html__(
                                        "Restaurant Setting",
                                        "scwatbwsr-translate"
                                    ); ?></span></label>

                            <input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab2" type="radio"
                                name="scwatbwsr_tabs2">
                            <label class="scwatbwsr_room_content_tabs_label <?= getActiveClass(
                                2,
                                "scwatbwsr_tab2"
                            ) ?>"
                                for="scwatbwsr_tab2"><i
                                    class="fa fa-codepen"></i><span><?php echo esc_html__(
                                        "Room Mapping",
                                        "scwatbwsr-translate"
                                    ); ?></span></label>

                            <input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab3" type="radio"
                                name="scwatbwsr_tabs3">
                            <label class="scwatbwsr_room_content_tabs_label <?= getActiveClass(
                                3,
                                "scwatbwsr_tab3"
                            ) ?>"
                                for="scwatbwsr_tab3"><i
                                    class="fa fa-calendar"></i><span><?php echo esc_html__(
                                        "Schedules",
                                        "scwatbwsr-translate"
                                    ); ?></span></label>


                            <input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab4" type="radio"
                                name="scwatbwsr_tabs4">
                            <label class="scwatbwsr_room_content_tabs_label <?= getActiveClass(
                                4,
                                "scwatbwsr_tab4"
                            ) ?>"
                                for="scwatbwsr_tab4"><i
                                    class="fa fa-calendar"></i><span><?php echo esc_html__(
                                        "Table Settings",
                                        "scwatbwsr-translate"
                                    ); ?></span></label>

                            <input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab5" type="radio"
                                name="scwatbwsr_tabs5">
                            <label class="scwatbwsr_room_content_tabs_label <?= getActiveClass(
                                5,
                                "scwatbwsr_tab5"
                            ) ?>"
                                for="scwatbwsr_tab5"><i
                                    class="fa fa-usd"></i><span><?php echo esc_html__(
                                        "Ippayware",
                                        "scwatbwsr-translate"
                                    ); ?></span></label>

                            <input class="scwatbwsr_room_content_tabs_input" id="scwatbwsr_tab6" type="radio"
                                name="scwatbwsr_tabs6">
                            <label class="scwatbwsr_room_content_tabs_label <?= getActiveClass(
                                6,
                                "scwatbwsr_tab6"
                            ) ?>"
                                for="scwatbwsr_tab6"><i
                                    class="fa fa-th"></i><span><?php echo esc_html__(
                                        "Twilio",
                                        "scwatbwsr-translate"
                                    ); ?></span></label>

								</div>
<div class="scwatbwsr_room_content_tabs padequal">
                            <section id="scwatbwsr_content1"
                                class="tab-content <?= getActiveClass(
                                    1,
                                    "scwatbwsr_tab1"
                                ) ?>">
                                <div class="scwatbwsr_content mb-3">
                                    <div class="content">
                                        <form action='options.php' method='post'>
                                            <?php
                                            settings_fields(
                                                "pluginSCWTBWSRRest"
                                            );

                                            $options = get_option(
                                                "scwatbwsr_settings_rest"
                                            );
                                            if (!$options) {
                                                $options = [];
                                            }

                                            $settings_fields = [
                                                "enabled" => [
                                                    "title" => __(
                                                        "Enable/Disable",
                                                        "woocommerce"
                                                    ),
                                                    "type" => "checkbox",
                                                    "label" => __(
                                                        "Enable Reservations Charge",
                                                        "woocommerce"
                                                    ),
                                                    "default" => "no",
                                                ],
                                            ];
                                            ?>
                                            <div class="scwatbwsr_content">
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Restaurant Name",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="text" value="<?= @$options[
                                                            "restaurant_name"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_rest[restaurant_name]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo __(
                                                            "Restaurant Name",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?= $settings_fields[
                                                            "enabled"
                                                        ]["title"] ?></p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <!-- <input type="checkbox"> -->
                                                        <label class="sap-admin-switch">
                                                            <input name="scwatbwsr_settings_rest[enabled_payment]"
                                                                <?php if (
                                                                    @$options[
                                                                        "enabled_payment"
                                                                    ] == "on"
                                                                ) {
                                                                    echo "checked='true'";
                                                                } ?>
                                                                type="checkbox" class="sap-admin-option-toggle">
                                                            <span class="sap-admin-switch-slider round"></span>
                                                        </label>
                                                        <p><?= $settings_fields[
                                                            "enabled"
                                                        ]["label"] ?></p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Enable Offline Payment",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <!-- <input type="checkbox"> -->
                                                        <label class="sap-admin-switch">
                                                            <input name="scwatbwsr_settings_rest[offline_payment]"
                                                                <?php if (
                                                                    @$options[
                                                                        "offline_payment"
                                                                    ] == "on"
                                                                ) {
                                                                    echo "checked='true'";
                                                                } ?>
                                                                type="checkbox" class="sap-admin-option-toggle">
                                                            <span class="sap-admin-switch-slider round"></span>
                                                        </label>
                                                        <p><?php echo __(
                                                            "Customer can pay offline  in restaurants",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Online Payment Paritial %",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="number" min="1" max="99"
                                                            value="<?= @$options[
                                                                "pay_later"
                                                            ] ?>"
                                                            name="scwatbwsr_settings_rest[pay_later]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo __(
                                                            "OnlinePayment paritial %,user can pay offline or online in restaurants",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Table Choose by customer",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <div class="form-radio-scw-per">
                                                            <input type="radio" id="reservation"
                                                                <?php if (
                                                                    @$options[
                                                                        "customer_table"
                                                                    ] == "yes"
                                                                ) {
                                                                    echo "checked='true'";
                                                                } ?>
                                                                name="scwatbwsr_settings_rest[customer_table]"
                                                                value="yes">
                                                            <label for="html">Yes</label>
                                                        </div>
                                                        <div class="form-radio-scw-per">
                                                            <input type="radio" id="guest"
                                                                <?php if (
                                                                    @$options[
                                                                        "customer_table"
                                                                    ] == "no"
                                                                ) {
                                                                    echo "checked='true'";
                                                                } ?>
                                                                name="scwatbwsr_settings_rest[customer_table]"
                                                                value="no">
                                                            <label for="css">No</label>
                                                        </div>
                                                        <p><?php echo __(
                                                            "Rooms choose by, per customer or Manager",
                                                            "scwatbwsr-translate"
                                                        ); ?>?
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Booking Time",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="text" value="<?= @$options[
                                                            "booking_time"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_rest[booking_time]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo esc_html__(
                                                            "Booking Time (in minutes - the time customers will stay)",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo esc_html__(
                                                            "Restaurant hall Size",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="text"
                                                            value="<?= @$options[
                                                                "restaurant_size_width"
                                                            ] ?>"
                                                            name="scwatbwsr_settings_rest[restaurant_size_width]"
                                                            class="require-deposit-scw">
                                                        <input type="text"
                                                            value="<?= @$options[
                                                                "restaurant_size_height"
                                                            ] ?>"
                                                            name="scwatbwsr_settings_rest[restaurant_size_height]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo esc_html__(
                                                            "Booking Time (in minutes - the time customers will stay)",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo esc_html__(
                                                            "Table Booked Color",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="color" value="<?= @$options[
                                                            "table_booked_color"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_rest[table_booked_color]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo esc_html__(
                                                            "Table booked color will show on the live page",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo esc_html__(
                                                            "Table Booked Seat Color",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="color" value="<?= @$options[
                                                            "seat_booked_color"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_rest[seat_booked_color]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo esc_html__(
                                                            "Table  booked  seat color will show on the live page",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Background Color",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                    <div class="general-setting-right">

                                                        <input name="scwatbwsr_settings_rest[background_color]"
                                                            type="color" id="scwatbwsr_roombg_con_color"
                                                            class="require-deposit-scw "
                                                            value="<?php echo esc_attr(
                                                                @$options[
                                                                    "background_color"
                                                                ]
                                                            ); ?>"
                                                            type="text">
                                                        <p><?php echo __(
                                                            "Restaurant Background Color",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Or Background Image",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">

                                                        <input name="scwatbwsr_settings_rest[background_image]"
                                                            disabled="true" type="text"
                                                            value="<?php echo esc_attr(
                                                                @$options[
                                                                    "background_color"
                                                                ]
                                                            ); ?>"
                                                            type="text" class="require-deposit-scw">
                                                        <span
                                                            class="scwatbwsr_roombg_con_upload scwatbwsr_media_upload"><?php echo esc_html__(
                                                                "Upload Image",
                                                                "scwatbwsr-translate"
                                                            ); ?></span>
                                                        <p><?php echo __(
                                                            "Restaurant Background Image",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>


                                            </div>
                                            <?php submit_button(); ?>
                                        </form>
                                    </div>
                                </div>



                            </section>

                            <section id="scwatbwsr_content2"
                                class="tab-content <?= getActiveClass(
                                    2,
                                    "scwatbwsr_tab2"
                                ) ?>">
                                <div class="tablesize">
                                    <?php
                                    $roomLists = getAllroomsLiveView();
                                    foreach ($roomLists as $room) { ?>
                                    <div class="leaderboard tablesize-drag" data-id="<?= $room->id ?>"
                                        style="<?= "top:" .
                                            $room->rtop .
                                            "px;left:" .
                                            $room->rleft .
                                            "px;" ?>">
                                        <header>
                                            <h1 class="leaderboard__title"><span
                                                    class="leaderboard__title--top"><?= $room->roomname ?></span><span
                                                    class="leaderboard__title--bottom">Totoal Table
                                                    (<?= $room->count ?>)</span></h1>
                                        </header>
                                        <main class="leaderboard__profiles">
                                            <?php
                                            $tableLists = getAllTableLiveViewByRoom(
                                                $room->id
                                            );
                                            foreach ($tableLists as $t) { ?>
                                            <span class="leaderboard__name"><?= $t->label ?> <span
                                                    class="leaderboard__value"><?= $t->seats ?></span></span>


                                    </div>
                                    <?php }
                                            ?>
                                    </main>
                                    <?php }
                                    ?>

                                </div>
                            </section>

                            <section id="scwatbwsr_content3"
                                class="tab-content <?= getActiveClass(
                                    3,
                                    "scwatbwsr_tab3"
                                ) ?>">
                                <div class="scwatbwsr_schedules_spec">
                                    <h2 class="scwatbwsr_schedules_spec_head">
                                        <?php echo esc_html__(
                                            "Separate Schedules",
                                            "scwatbwsr-translate"
                                        ); ?></h2>
                                    <div class="scwatbwsr_schedules_spec_input_row">
                                        <div class="scwatbwsr_schedules_spec_input">
                                            <label>Start date and time</label>
                                            <input class="scwatbwsr_schedules_spec_add_input" type="text">
                                            <input type="hidden" class="start_time_hidden" />
                                        </div>
                                        <div class="scwatbwsr_schedules_spec_input">
                                            <label>End time</label>
                                            <input class="scwatbwsr_schedules_spec_end_time_input" type="text">
                                        </div>
                                        <div class="scwatbwsr_schedules_spec_add">

                                            <span class="scwatbwsr_schedules_spec_button mb-3"><i class="fa fa-plus"
                                                    aria-hidden="true"></i>
                                                <?php echo esc_html__(
                                                    "ADD",
                                                    "scwatbwsr-translate"
                                                ); ?></span>

                                        </div>
                                    </div>

                                    <h2 class="scwatbwsr_schedules_spec_head">
                                        <?php echo esc_html__(
                                            "List of  Schedules",
                                            "scwatbwsr-translate"
                                        ); ?></h2>

                                    <span class="scwatbwsr_schedules_spec_list">
                                        <?php if ($schedules) {
                                            foreach (
                                                $schedules
                                                as $schedule
                                            ) { ?>
                                        <span class="scwatbwsr_schedules_spec_list_item">
                                            <input type="hidden" value="<?php echo esc_attr(
                                                $schedule->id
                                            ); ?>"
                                                class="scwatbwsr_schedules_spec_list_item_id">
                                            <input type="hidden" class="start_time_hidden_list"
                                                value="<?php echo esc_attr(
                                                    $schedule->start_time
                                                ); ?>" />
                                            <input
                                                class="scwatbwsr_schedules_spec_list_item_schedule scwatbwsr_schedules_spec_list_item_schedule_start"
                                                value="<?php echo esc_attr(
                                                    date(
                                                        "F j, Y H:i",
                                                        strtotime(
                                                            $schedule->schedule
                                                        )
                                                    )
                                                ); ?>"
                                                type="text">
                                            <input
                                                class="scwatbwsr_schedules_spec_list_item_schedule scwatbwsr_schedules_spec_list_item_schedule_end"
                                                value="<?php echo esc_attr(
                                                    $schedule->end_time
                                                ); ?>" type="text">
                                            <span class="scwatbwsr_schedules_spec_list_item_save"><i
                                                    class="fa fa-floppy-o" aria-hidden="true"></i>
                                                <?php echo esc_html__(
                                                    "Save",
                                                    "scwatbwsr-translate"
                                                ); ?></span>
                                            <span class="scwatbwsr_schedules_spec_list_item_delete"><i
                                                    class="fa fa-trash-o" aria-hidden="true"></i>
                                                <?php echo esc_html__(
                                                    "Delete",
                                                    "scwatbwsr-translate"
                                                ); ?></span>
                                        </span>
                                        <?php }
                                        } ?>
                                    </span>
                                    <div class="scwatbwsr_schedules_spec_reload">
                                        <p><span class="scwatbwsr_schedules_spec_add_reload"
                                                data-id="0"><?php echo esc_html__(
                                                    "Refresh Data",
                                                    "scwatbwsr-translate"
                                                ); ?>
                                                <i class="fa fa-refresh" aria-hidden="true"></i></span></p>
                                    </div>
                                </div>
                                <span
                                    class="scwatbwsr_schedules_or"><?php echo esc_html__(
                                        "OR",
                                        "scwatbwsr-translate"
                                    ); ?></span>
                                <span class="scwatbwsr_schedules_right">
                                    <span
                                        class="scwatbwsr_schedules_right_head"><?php echo esc_html__(
                                            "Daily Schedules",
                                            "scwatbwsr-translate"
                                        ); ?></span>

                                    <?php
                                    $weekDays = [
                                        "monday",
                                        "tuesday",
                                        "wednesday",
                                        "thursday",
                                        "friday",
                                        "saturday",
                                        "sunday",
                                    ];
                                    foreach ($weekDays as $wk => $week) { ?>
                                    <div class="scwatbwsr_daily_schedules">
                                        <div class="scwatbwsr_daily_schedules_week">
                                            <input <?php if (
                                                in_array($week, $dailies)
                                            ) {
                                                echo "checked='checked'";
                                            } ?>
                                                value="<?= $week ?>" type="checkbox"
                                                class="scwatbwsr_daily_schedules_<?= $week ?>"
                                                id="scwatbwsr_daily_schedules_<?= $week ?>">
                                            <label
                                                for="scwatbwsr_daily_schedules_<?= $week ?>"><?php echo esc_html__( ucfirst($week), "scwatbwsr-translate" ); ?>
											</label>

                                        </div>
                                        <?php
                                        $timeData = array_filter(
                                            $times,
                                            function ($t) use ($week) {
                                                return $t->week_day == $week;
                                            }
                                        );
                                        $time = array_reverse($timeData);

                                        if (!$time) {
                                            $time = (object) [];
                                            $time->id = 0;
                                            $time->start_time = "09:00";
                                            $time->week_day = $week;
                                            $time->end_time = "15:00";
                                            $time->roomid = 0;
                                        } else {
                                            $time = $time[0];
                                        }
                                        ?>
                                        <div class="scwatbwsr_daily_schedules_times_list_item">
                                            <input class="scwatbwsr_daily_schedules_times_list_item_week" type="hidden"
                                                value="<?php echo esc_attr(
                                                    $time->week_day
                                                ); ?>">
                                            <input class="scwatbwsr_daily_schedules_times_list_item_id" type="hidden"
                                                value="<?php echo esc_attr(
                                                    $time->id
                                                ); ?>">
                                            <input class="scwatbwsr_daily_schedules_times_list_item_input input_start"
                                                id="scwatbwsr_daily_schedules_times_list_item_inpu_<?= $time->id ?>t"
                                                placeholder="daily time"
                                                value="<?php echo esc_attr(
                                                    $time->start_time
                                                ); ?>" type="text">
                                            <input class="scwatbwsr_daily_schedules_times_list_item_input input_end"
                                                id="scwatbwsr_daily_schedules_times_list_item_input_<?= $time->id ?>"
                                                placeholder="daily time" value="<?php echo esc_attr(
                                                    $time->end_time
                                                ); ?>"
                                                type="text">
                                            <span class="scwatbwsr_daily_schedules_times_list_item_button"><i
                                                    class="fa fa-floppy-o" aria-hidden="true"></i>
                                                <?php echo esc_html__(
                                                    "Save",
                                                    "scwatbwsr-translate"
                                                ); ?></span>
                                        </div>
                                    </div>
                                    <?php }
                                    ?>
                                </span>
                            </section>

                            <section id="scwatbwsr_content4"
                                class="tab-content <?= getActiveClass(
                                    5,
                                    "scwatbwsr_tab4"
                                ) ?>">
                                <span class="scwatbwsr_roomtype_add">
                                    <span
                                        class="scwatbwsr_roomtype_add_head"><?php echo esc_html__(
                                            "Add a table setting",
                                            "scwatbwsr-translate"
                                        ); ?></span>
                                    <input class="scwatbwsr_roomtype_add_name" placeholder="Name of type" type="text">
                                    <span
                                        class="scwatbwsr_roomtype_add_table"><?php echo esc_html__(
                                            "Table",
                                            "scwatbwsr-translate"
                                        ); ?></span>
                                    <span class="scwatbwsr_roomtype_add_tbcolor">
                                        <span
                                            class="scwatbwsr_roomtype_add_tbcolor_head"><?php echo esc_html__(
                                                "Background Color",
                                                "scwatbwsr-translate"
                                            ); ?></span>
                                        <input type="color" class="scwatbwsr_roomtype_add_tbcolor_input"
                                            id="scwatbwsr_roomtype_add_tbcolor_input">
                                        <label class="scwatbwsr_roomtype_add_tbcolor_button"
                                            for="scwatbwsr_roomtype_add_tbcolor_input"><?php echo esc_html__(
                                                "Pick Color",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                    </span>
                                    <span class="scwatbwsr_roomtype_add_tbshape">
                                        <span
                                            class="scwatbwsr_roomtype_add_tbshape_head"><?php echo esc_html__(
                                                "Shape",
                                                "scwatbwsr-translate"
                                            ); ?></span>
                                        <span class="scwatbwsr_roomtype_add_tbshape_con">
                                            <label><?php echo esc_html__(
                                                "Rectangular",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="radio" class="scwatbwsr_roomtype_add_tbshape_rec"
                                                name="scwatbwsr_roomtype_add_tbshape" value="rectangular">
                                            <input type="text" class="scwatbwsr_roomtype_add_tbshape_rec_width"
                                                placeholder="Width (px)">
                                            <input type="text" class="scwatbwsr_roomtype_add_tbshape_rec_height"
                                                placeholder="Height (px)">
                                        </span>
                                        <span class="scwatbwsr_roomtype_add_tbshape_con">
                                            <label><?php echo esc_html__(
                                                "Circle",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="radio" class="scwatbwsr_roomtype_add_tbshape_cir"
                                                name="scwatbwsr_roomtype_add_tbshape" value="circle">
                                            <input type="text" class="scwatbwsr_roomtype_add_tbshape_cir_width"
                                                placeholder="Width (diameter-px)">
                                        </span>
                                    </span>
                                    <span
                                        class="scwatbwsr_roomtype_add_seat"><?php echo esc_html__(
                                            "Seat",
                                            "scwatbwsr-translate"
                                        ); ?></span>
                                    <span class="scwatbwsr_roomtype_add_seatcolor">
                                        <span
                                            class="scwatbwsr_roomtype_add_seatcolor_head"><?php echo esc_html__(
                                                "Background Color",
                                                "scwatbwsr-translate"
                                            ); ?></span>
                                        <input type="color" class="scwatbwsr_roomtype_add_seatcolor_input"
                                            id="scwatbwsr_roomtype_add_seatcolor_input">
                                        <label class="scwatbwsr_roomtype_add_seatcolor_button"
                                            for="scwatbwsr_roomtype_add_seatcolor_input"><?php echo esc_html__(
                                                "Pick Color",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                    </span>
                                    <span class="scwatbwsr_roomtype_add_seatshape">
                                        <span
                                            class="scwatbwsr_roomtype_add_seatshape_head"><?php echo esc_html__(
                                                "Shape",
                                                "scwatbwsr-translate"
                                            ); ?></span>
                                        <span class="scwatbwsr_roomtype_add_seatshape_con">
                                            <label><?php echo esc_html__(
                                                "Rectangular",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="radio" class="scwatbwsr_roomtype_add_seatshape_rec"
                                                name="scwatbwsr_roomtype_add_seatshape" value="rectangular">
                                        </span>
                                        <span class="scwatbwsr_roomtype_add_seatshape_con">
                                            <label><?php echo esc_html__(
                                                "Circle",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="radio" class="scwatbwsr_roomtype_add_seatshape_cir"
                                                name="scwatbwsr_roomtype_add_seatshape" value="circle">
                                        </span>
                                    </span>
                                    <input type="text" class="scwatbwsr_roomtype_add_seat_size"
                                        placeholder="Width (px)">
                                    <span class="scwatbwsr_roomtype_add_button"><i class="fa fa-plus"
                                            aria-hidden="true"></i>
                                        <?php echo esc_html__(
                                            "ADD",
                                            "scwatbwsr-translate"
                                        ); ?></span>
                                    <span class="scwatbwsr_roomtype_add_reload"
                                        data-id="0"><?php echo esc_html__(
                                            "Refresh Data",
                                            "scwatbwsr-translate"
                                        ); ?> <i
                                            class="fa fa-refresh" aria-hidden="true"></i></span>
                                </span>
                                <span class="scwatbwsr_roomtype_items">
                                    <span
                                        class="scwatbwsr_roomtype_items_head"><?php echo esc_html__(
                                            "Table Settings",
                                            "scwatbwsr-translate"
                                        ); ?></span>
                                    <?php if ($types) {
                                        foreach ($types as $type) { ?>
                                    <span class="scwatbwsr_roomtype_item">
                                        <input value="<?php echo esc_attr(
                                            $type->id
                                        ); ?>" type="hidden"
                                            class="scwatbwsr_roomtype_item_id">
                                        <span class="scwatbwsr_roomtype_item_name">
                                            <span><?php echo esc_attr(
                                                $type->name
                                            ); ?></span><br>
                                            <span
                                                class="scwatbwsr_roomtype_item_name_shape"><?php echo esc_html__(
                                                    "Table: ",
                                                    "scwatbwsr-translate"
                                                ) .
                                                    esc_attr(
                                                        $type->tbshape
                                                    ); ?></span><br>
                                            <span
                                                class="scwatbwsr_roomtype_item_name_shape"><?php echo esc_html__(
                                                    "Seat: ",
                                                    "scwatbwsr-translate"
                                                ) .
                                                    esc_attr(
                                                        $type->seatshape
                                                    ); ?></span>
                                        </span>
                                        <span class="scwatbwsr_roomtype_item_tbbg">
                                            <label><?php echo esc_html__(
                                                "Table Color",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="color" class="scwatbwsr_roomtype_item_tbbg_input"
                                                value="<?php echo esc_attr(
                                                    $type->tbbg
                                                ); ?>">
                                        </span>
                                        <span
                                            class="scwatbwsr_roomtype_item_tbsize <?php echo esc_attr(
                                                $type->tbshape
                                            ); ?>">
                                            <label><?php echo esc_html__(
                                                "Table Size",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="text" class="scwatbwsr_roomtype_item_tbsize_recwidth"
                                                value="<?php echo esc_attr(
                                                    $type->tbrecwidth
                                                ); ?>">
                                            <input type="text" class="scwatbwsr_roomtype_item_tbsize_recheight"
                                                value="<?php echo esc_attr(
                                                    $type->tbrecheight
                                                ); ?>">
                                            <input type="text" class="scwatbwsr_roomtype_item_tbsize_cirwidth"
                                                value="<?php echo esc_attr(
                                                    $type->tbcirwidth
                                                ); ?>">
                                        </span>
                                        <span class="scwatbwsr_roomtype_item_seatbg">
                                            <label><?php echo esc_html__(
                                                "Seat Color",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="color" class="scwatbwsr_roomtype_item_seatbg_input"
                                                value="<?php echo esc_attr(
                                                    $type->seatbg
                                                ); ?>">
                                        </span>
                                        <span
                                            class="scwatbwsr_roomtype_item_seatsize <?php echo esc_attr(
                                                $type->seatshape
                                            ); ?>">
                                            <label><?php echo esc_html__(
                                                "Seat Size",
                                                "scwatbwsr-translate"
                                            ); ?></label>
                                            <input type="text" class="scwatbwsr_roomtype_item_seatsize_width"
                                                value="<?php echo esc_attr(
                                                    $type->seatwidth
                                                ); ?>">
                                        </span>
                                        <span class="scwatbwsr_roomtype_item_save"><i class="fa fa-floppy-o"
                                                aria-hidden="true"></i>
                                            <?php echo esc_html__(
                                                "Save",
                                                "scwatbwsr-translate"
                                            ); ?></span>
                                        <span class="scwatbwsr_roomtype_item_del"><i class="fa fa-trash-o"
                                                aria-hidden="true"></i>
                                            <?php echo esc_html__(
                                                "Delete",
                                                "scwatbwsr-translate"
                                            ); ?></span>
                                    </span>
                                    <?php }
                                    } ?>
                                </span>
                            </section>
							
                            <?php $settings_fields = [
                                "enabled" => [
                                    "title" => __(
                                        "Enable/Disable",
                                        "woocommerce"
                                    ),
                                    "type" => "checkbox",
                                    "label" => __(
                                        "Enable Reservations Charge",
                                        "woocommerce"
                                    ),
                                    "default" => "no",
                                ],

                                "api_key" => [
                                    "title" => __("API Key", "woocommerce"),
                                    "type" => "text",
                                    "description" => __(
                                        "Please enter your IPPayware Portal API Key; this is needed in order to take payment.",
                                        "woocommerce"
                                    ),
                                    "desc_tip" => true,
                                ],
                                "api_secret" => [
                                    "title" => __("API Secret", "woocommerce"),
                                    "type" => "text",
                                    "description" => __(
                                        "Please enter your IPPayware Portal API Secret Token; this is needed in order to take payment.",
                                        "woocommerce"
                                    ),
                                    "desc_tip" => true,
                                ],
                            ]; ?>

                            <section id="scwatbwsr_content5"
                                class="tab-content <?= getActiveClass(
                                    5,
                                    "scwatbwsr_tab5"
                                ) ?>">

                                <div class="scwatbwsr_content mb-3">

                                    <div class="content">
                                        <form action='options.php' method='post'>
                                            <?php
                                            settings_fields(
                                                "pluginSCWTBWSRPay"
                                            );
                                            $options = get_option(
                                                "scwatbwsr_settings_ippayware"
                                            );
                                            if (!$options) {
                                                $options = [];
                                            }
                                            ?>
                                            <div class="general-setting-scw">
                                                <div class="general-setting-left">
                                                    <p><?= $settings_fields[
                                                        "api_key"
                                                    ]["title"] ?></p>
                                                </div>
                                                <div class="general-setting-right">
                                                    <input type="text" value="<?= @$options[
                                                        "api_key"
                                                    ] ?>"
                                                        name="scwatbwsr_settings_ippayware[api_key]"
                                                        class="require-deposit-scw">
                                                    <p><?= $settings_fields[
                                                        "api_key"
                                                    ]["description"] ?></p>
                                                </div>
                                            </div>
                                            <div class="general-setting-scw">
                                                <div class="general-setting-left">
                                                    <p><?= $settings_fields[
                                                        "api_secret"
                                                    ]["title"] ?></p>
                                                </div>
                                                <div class="general-setting-right">
                                                    <input type="text" value="<?= @$options[
                                                        "api_secret"
                                                    ] ?>"
                                                        name="scwatbwsr_settings_ippayware[api_secret]"
                                                        class="require-deposit-scw">
                                                    <p><?= $settings_fields[
                                                        "api_secret"
                                                    ]["description"] ?></p>
                                                </div>
                                            </div>
                                            <div class="general-setting-scw">
                                                <div class="general-setting-left">
                                                    <p><?php echo __(
                                                        "Ippayware  Comission",
                                                        "scwatbwsr-translate"
                                                    ); ?></p>
                                                </div>
                                                <div class="general-setting-right">
                                                    <input type="text" value="<?= @$options[
                                                        "commission"
                                                    ] ?>"
                                                        name="scwatbwsr_settings_ippayware[commission]"
                                                        class="require-deposit-scw">
                                                    <p>What deposit amount is required (either per reservation or per
                                                        guest, depending on the setting above)? Minimum is $0.50 in most
                                                        currencies.</p>
                                                </div>
                                            </div>

                                            <?php submit_button(); ?>
                                        </form>
                                    </div>
                                </div>

                            </section>

                            <section id="scwatbwsr_content6"
                                class="tab-content <?= getActiveClass(
                                    6,
                                    "scwatbwsr_tab6"
                                ) ?>">
                                <div class="scwatbwsr_content mb-3">
                                    <div class="content">
                                        <form action='options.php' method='post'>
                                            <?php
                                            settings_fields(
                                                "pluginSCWTBWSRTwilio"
                                            );
                                            $options = get_option(
                                                "scwatbwsr_settings_twilio"
                                            );
                                            if (!$options) {
                                                $options = [];
                                            }
                                            ?>
                                            <div class="scwatbwsr_content">
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Twilio SID",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="text" value="<?= @$options[
                                                            "twilio_sid"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_twilio[twilio_sid]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo __(
                                                            "Twilio SID from twilio account",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Twilio Secert Key",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="text" value="<?= @$options[
                                                            "twilio_secert"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_twilio[twilio_secert]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo __(
                                                            "Twilio Secert Key from twilio account",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Account SID",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="text" value="<?= @$options[
                                                            "twilio_account_sid"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_twilio[twilio_account_sid]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo __(
                                                            "Twilio Account SID from twilio account",
                                                            "scwatbwsr-translate"
                                                        ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="general-setting-scw">
                                                    <div class="general-setting-left">
                                                        <p><?php echo __(
                                                            "Twilio Number",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                    <div class="general-setting-right">
                                                        <input type="text" value="<?= @$options[
                                                            "twilio_number"
                                                        ] ?>"
                                                            name="scwatbwsr_settings_twilio[twilio_number]"
                                                            class="require-deposit-scw">
                                                        <p><?php echo __(
                                                            "Send ID Number",
                                                            "scwatbwsr-translate"
                                                        ); ?></p>
                                                    </div>
                                                </div>
                                                <?php submit_button(); ?>
                                        </form>
                                    </div>
                                </div>

                            </section>
													</div>


                        </div>


						<!-- dasddasd -->
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>