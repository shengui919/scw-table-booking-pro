<style>

.dashboard {
  display: grid;
  grid-template-columns: repeat(3, auto);
  gap: 48px;
  margin: 12px;
}

.dashboard-sidenav {
  display: grid;
  grid-template-rows: repeat(3, auto);
  justify-items: center;
  color: white;
  background: var(--info-color-darker);
  border-radius: 30px;
}

.logo {
  padding: 36px 18px;
  font-size: 18px;
  font-weight: bold;
  color: white;
  text-decoration: none;
}

.nav-icon-list {
  display: flex;
  flex-direction: column;
  padding: 0;
  margin: 0;
  list-style-type: none;
}
.nav-icon-list__item {
  padding: 36px 18px;
}
.nav-icon-list__item:first-child {
  padding-top: 0;
}

.logout {
  padding: 36px 18px;
}

.welcome-banner {
  padding: 18px 36px;
  color: white;
  background: var(--warning-color-darker);
  border-radius: 20px;
}

.section-titles {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 28px 0;
}

.section-title {
  display: grid;
  grid-template-columns: repeat(2, auto);
  align-items: center;
  font-size: 18px;
}

.nav-btns {
  --btn-group-divider-length: 0;
  margin-left: 24px;
}
.nav-btns .btn {
  padding: 9px 0;
  line-height: 1;
}
.nav-btns .btn:first-child {
  padding-left: 15px;
  padding-right: 3px;
}
.nav-btns .btn:last-child {
  padding-left: 3px;
  padding-right: 15px;
}

.month {
  padding: 9px 18px;
  color: var(--secondary-color-darkest);
}

.time-list {
  display: grid;
  grid-template-columns: repeat(7, auto);
  column-gap: 21px;
  padding: 18px 0;
  border: 1px solid var(--secondary-color-lighter);
  border-left: none;
  border-right: none;
  list-style-type: none;
}
.time-list__item {
  display: grid;
  grid-template-rows: repeat(3, auto);
  justify-items: center;
  padding: 16px 12px;
  border-radius: 20px;
}
.time-list__item.active {
  color: white;
  background: var(--danger-color);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12), 0 0 6px rgba(0, 0, 0, 0.04);
}
.time-list__item.active .day-dot {
  color: white !important;
}
.time-list__item .day {
  font-weight: bold;
  margin-bottom: 18px;
}
.time-list__item .day-number {
  margin-bottom: 8px;
}
.time-list__item .day-dot {
  width: 6px;
  height: 6px;
  color: var(--dot-color);
  background: currentColor;
  border-radius: 50%;
}

.weeks-option {
  display: flex;
}
.weeks-option__item {
  color: var(--secondary-color-darkest);
  text-decoration: none;
}
.weeks-option__item:not(:last-child) {
  margin-right: 21px;
}
.weeks-option__item:not(.active) {
  opacity: 0.5;
}

.junk-list {
  display: grid;
  grid-template-columns: repeat(4, auto);
  gap: 30px;
  padding: 0;
  margin: 0;
  list-style-type: none;
}
.junk-list__item {
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
  padding: 18px 24px;
  border: 1px solid var(--secondary-color-lighter);
  border-radius: 20px;
}
.junk-list__item:not(.active) {
  max-height: 148px;
}
.junk-list__item .junk-icon {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  border: 1px solid var(--junk-color);
  border-radius: 10px;
}
.junk-list__item .junk-name {
  margin-top: 18px;
  white-space: nowrap;
}
.junk-list__item .junk-size {
  margin-top: 9px;
  font-size: 18px;
  font-weight: bold;
  white-space: nowrap;
}
.junk-list__item.active {
  padding: 18px 3px 5px 3px;
  border: none;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.junk-list__item.active .junk-icon {
  background: var(--junk-color);
}
.junk-list__item.active .junk-icon svg {
  fill: white;
}
.junk-list__item.active .junk-size {
  margin-top: 21px;
  padding: 18px 36px;
  color: white;
  background: var(--junk-color);
  border-radius: 15px;
}

.monitor-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 32px;
  padding: 0;
  margin: 0;
  list-style-type: none;
}
.monitor-list__item {
  display: grid;
  grid-template-columns: repeat(2, auto);
  align-items: center;
  padding: 15px 30px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  border-radius: 20px;
}
.monitor-list__item .monitor-data {
  padding: 18px 0;
}
.monitor-list__item .monitor-data .monitor-type {
  font-weight: bold;
  white-space: nowrap;
  margin: 0 40px 18px 0;
}
.monitor-list__item .monitor-gauge {
  padding: 0 0 0 40px;
  border-left: 1px solid var(--secondary-color-lighter);
}
.monitor-list__item .monitor-gauge .gauge {
  --gauge-circle-color-lighter: var(--secondary-color);
  --gauge-color: var(--secondary-color-darkest);
  width: 70px;
  height: 70px;
}
.monitor-list__item .monitor-gauge .gauge::before {
  width: 87%;
  height: 87%;
  content: counter(value) &quot;%&quot;;
}
.monitor-list__item.active {
  color: white;
  background: var(--info-color-darker);
}
.monitor-list__item.active .monitor-gauge {
  border-left-color: var(--info-color-lighter);
}
.monitor-list__item.active .gauge {
  --gauge-bg: var(--info-color-darker);
  --gauge-color-lighter: var(--info-color-lighter);
  color: white;
}

.dashboard-others {
  padding: 18px 48px;
  background: var(--secondary-color);
  border-radius: 30px;
}

.function-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-template-rows: repeat(2, 1fr);
  gap: 20px;
  padding: 0;
  margin: 0;
  list-style-type: none;
}
.function-list__item {
  padding: 4px;
  background: white;
  border-radius: 15px;
  transition: 0.3s;
}
.function-list__item:hover {
  box-shadow: 0 0 0 1px var(--function-color);
}
.function-list__item .function {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 4px;
  color: white;
  background: var(--function-color);
  border-radius: 15px;
}
.function-list__item .function-icon {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 10px;
}
.function-list__item .function-data {
  display: flex;
  justify-content: space-between;
  padding: 30px 16px 21px 16px;
}
.function-list__item .function-name {
  margin-right: 22px;
  white-space: nowrap;
}
.function-list__item .function-switch {
  --switch-ball-bg: var(--function-color);
  --switch-checked-bg: var(--function-color);
  --switch-border-color: var(--function-color);
  --switch-hover-border-color: transparent;
  --switch-disabled-checked-bg: transparent;
}
.function-list__item .function-switch:checked {
  --switch-border-color: var(--function-color);
}
.function-list__item.update-function:hover {
  box-shadow: 0 0 0 1px var(--secondary-color-darker);
}
.function-list__item.update-function .function-icon {
  color: var(--secondary-color-darker);
  background: white;
}
.function-list__item.update-function .function-menu {
  color: var(--secondary-color-darker);
}
.function-list__item.update-function .function-switch {
  --switch-ball-bg: var(--secondary-color-darker);
  --switch-checked-bg: var(--secondary-color-darker);
  --switch-border-color: var(--secondary-color-darker);
  --switch-hover-border-color: transparent;
  --switch-disabled-checked-bg: transparent;
}
.function-list__item.update-function .function-switch:checked {
  --switch-border-color: var(--secondary-color-darker);
}

.statistics {
  display: flex;
  flex-direction: column;
  padding: 30px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
}
.statistics .progress-data {
  display: flex;
  justify-content: space-between;
}
.statistics .progress-text {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.statistics .progress-weekday {
  margin-bottom: 14px;
}
.statistics .progress-rate {
  font-size: 18px;
  font-weight: bold;
}

.progress-list {
  display: grid;
  gap: 40px;
  padding: 30px 0 0 0;
  margin: 0;
  list-style-type: none;
}
.progress-list__item {
  display: flex;
  align-items: center;
}
.progress-list__item .weekday-abbr {
  box-sizing: border-box;
  padding-right: 36px;
  max-width: 32px;
}
.progress-list__item .progress-bar {
  --progress-bar-color: var(--secondary-color);
  width: 300px;
}
.progress-list__item.active .weekday-abbr {
  color: var(--warning-color);
}
.progress-list__item.active .progress-bar {
  --progress-color: var(--warning-color);
}
</style>

	<div class="wrap">
		<div class="scwatbwsr_content">
			<div><?=settings_errors()?></div>
        </div>
		<div class="scwatbwsr_content mb-3">
		<?php adminMenuPage()?>
        </div>
		<div class="scwatbwsr_content pd-10">
        <h2 class="mb-3"><?php echo esc_html__("Table Booking Dashboard", "scwatbwsr-translate") ?></h2>
<main class="dashboard">
   <article class="dashboard-content">
        
        <section class="section">
            <div class="section-titles">
                <div class="section-title">Weekly Reports</div>
                <div class="section-subtitle">
                    <div class="weeks-option">
                        <a href="#" class="weeks-option__item">Today</a>
                        <a href="#" class="weeks-option__item active">Week</a>
                        <a href="#" class="weeks-option__item">Month</a>
                    </div>
                </div>
            </div>
            <ul class="junk-list">
                <li class="junk-list__item active" style="--junk-color: var(--warning-color-darker);">
                    <div class="junk-icon">
                        <svg t="1585662864272" class="icon" fill="var(--junk-color)" viewBox="0 0 1024 1024"
                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="9396" width="18" height="18">
                            <path
                                d="M949 173.6c0-40.5-32.9-73.4-73.4-73.4H148.4c-40.5 0-73.4 32.9-73.4 73.4v168.1c0 16.9 5.7 32.4 15.4 44.8-9.7 12.5-15.4 28-15.4 44.9v168.1c0 15.4 4.7 29.6 12.8 41.4-8.1 11.8-12.8 26-12.8 41.4v168.1c0 40.5 32.9 73.4 73.4 73.4h727.2c40.5 0 73.4-32.9 73.4-73.4V682.3c0-15.4-4.7-29.6-12.8-41.4 8.1-11.8 12.8-26.1 12.8-41.4V431.4c0-16.9-5.7-32.4-15.4-44.8 9.6-12.4 15.4-28 15.4-44.8V173.6z m-810 0c0-5.1 4.3-9.4 9.4-9.4h727.2c5.1 0 9.4 4.3 9.4 9.4v168.1c0 5.1-4.3 9.4-9.4 9.4H148.4c-5.1 0-9.4-4.3-9.4-9.4V173.6z m746 425.9c0 5.1-4.3 9.4-9.4 9.4H148.4c-5.1 0-9.4-4.3-9.4-9.4V431.4c0-5.1 4.3-9.4 9.4-9.4h727.2c5.1 0 9.4 4.3 9.4 9.4v168.1z m0 250.9c0 5.1-4.3 9.4-9.4 9.4H148.4c-5.1 0-9.4-4.3-9.4-9.4V682.3c0-5.1 4.3-9.4 9.4-9.4h727.2c5.1 0 9.4 4.3 9.4 9.4v168.1z"
                                p-id="9397"></path>
                            <path
                                d="M229.2 289.7h122c17.7 0 32-14.3 32-32s-14.3-32-32-32h-122c-17.7 0-32 14.3-32 32s14.3 32 32 32zM351.1 483.4h-122c-17.7 0-32 14.3-32 32s14.3 32 32 32h122c17.7 0 32-14.3 32-32 0-17.6-14.3-32-32-32zM351.1 734.3h-122c-17.7 0-32 14.3-32 32s14.3 32 32 32h122c17.7 0 32-14.3 32-32s-14.3-32-32-32z"
                                p-id="9398"></path>
                        </svg>
                    </div>
                    <div class="junk-name">Total booking</div>
                    <div class="junk-size">35</div>
                </li>
                <li class="junk-list__item" style="--junk-color: var(--danger-color-darker);">
                    <div class="junk-icon">
                        <svg t="1585663177457" class="icon" fill="var(--junk-color)" viewBox="0 0 1024 1024"
                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11402" width="18" height="18">
                            <path
                                d="M364 433.662V810c0 82.843-67.157 150-150 150S64 892.843 64 810s67.157-150 150-150c25.282 0 49.103 6.255 70 17.3V400.677a39.861 39.861 0 0 1 0-1.446V200c0-19.455 13.998-36.089 33.168-39.412l548-95C889.626 61.348 912 80.177 912 105v199.324c0.009 0.484 0.009 0.965 0 1.446V714c0 82.843-67.157 150-150 150s-150-67.157-150-150 67.157-150 150-150c25.282 0 49.103 6.255 70 17.3V352.53l-468 81.132z m0-81.193l468-81.131V152.53l-468 81.131V352.47zM214 880c38.66 0 70-31.34 70-70s-31.34-70-70-70-70 31.34-70 70 31.34 70 70 70z m548-96c38.66 0 70-31.34 70-70s-31.34-70-70-70-70 31.34-70 70 31.34 70 70 70z"
                                p-id="11403"></path>
                        </svg>
                    </div>
                    <div class="junk-name">Booked Tickets</div>
                    <div class="junk-size">125k</div>
                </li>
                <li class="junk-list__item" style="--junk-color: var(--primary-color-darker);">
                    <div class="junk-icon">
                        <svg t="1585663224255" class="icon" fill="var(--junk-color)" viewBox="0 0 1024 1024"
                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1146" data-darkreader-inline-fill=""
                            width="18" height="18">
                            <path
                                d="M896 234.666667H725.333333v-42.666667c0-70.72-57.28-128-128-128H426.666667c-70.72 0-128 57.28-128 128v42.666667H128a42.666667 42.666667 0 0 0 0 85.333333h42.666667v512c0 70.72 57.28 128 128 128h426.666666c70.72 0 128-57.28 128-128V320h42.666667a42.666667 42.666667 0 0 0 0-85.333333z m-512-42.666667c0-23.573333 19.093333-42.666667 42.666667-42.666667h170.666666c23.573333 0 42.666667 19.093333 42.666667 42.666667v42.666667H384v-42.666667z m384 640c0 23.573333-19.093333 42.666667-42.666667 42.666667H298.666667c-23.573333 0-42.666667-19.093333-42.666667-42.666667V320h512v512z"
                                p-id="1147"></path>
                        </svg>
                    </div>
                    <div class="junk-name">Revenue</div>
                    <div class="junk-size">$16.35 k</div>
                </li>
                <li class="junk-list__item" style="--junk-color: var(--info-color-darker);">
                    <div class="junk-icon">
                        <svg t="1585663289751" class="icon" fill="var(--junk-color)" viewBox="0 0 1024 1024"
                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13346" width="18" height="18">
                            <path
                                d="M725.333333 981.333333H298.666667c-140.8 0-256-115.2-256-256V298.666667c0-140.8 115.2-256 256-256h426.666666c140.8 0 256 115.2 256 256v426.666666c0 140.8-115.2 256-256 256zM298.666667 128C204.8 128 128 204.8 128 298.666667v426.666666c0 93.866667 76.8 170.666667 170.666667 170.666667h426.666666c93.866667 0 170.666667-76.8 170.666667-170.666667V298.666667c0-93.866667-76.8-170.666667-170.666667-170.666667H298.666667z"
                                p-id="13347"></path>
                            <path
                                d="M512 725.333333c-46.933333 0-89.6-12.8-128-42.666666-46.933333-34.133333-76.8-85.333333-85.333333-140.8C285.866667 426.666667 366.933333 315.733333 482.133333 298.666667c21.333333-4.266667 42.666667-4.266667 64 0 93.866667 12.8 166.4 85.333333 179.2 179.2 8.533333 55.466667-4.266667 110.933333-38.4 157.866666s-85.333333 76.8-140.8 85.333334c-12.8 0-21.333333 4.266667-34.133333 4.266666z m0-341.333333h-17.066667c-68.266667 8.533333-119.466667 76.8-106.666666 145.066667 8.533333 68.266667 76.8 119.466667 145.066666 106.666666 34.133333-4.266667 64-21.333333 85.333334-51.2s29.866667-59.733333 25.6-93.866666c-8.533333-55.466667-51.2-98.133333-106.666667-106.666667H512zM746.666667 320c-12.8 0-21.333333-4.266667-29.866667-12.8-4.266667-4.266667-8.533333-8.533333-8.533333-12.8-4.266667-4.266667-4.266667-12.8-4.266667-17.066667 0-12.8 4.266667-21.333333 12.8-29.866666 17.066667-17.066667 42.666667-17.066667 59.733333 0 8.533333 8.533333 12.8 17.066667 12.8 29.866666 0 4.266667 0 12.8-4.266666 17.066667s-4.266667 8.533333-8.533334 12.8c-8.533333 8.533333-17.066667 12.8-29.866666 12.8z"
                                p-id="13348"></path>
                        </svg>
                    </div>
                    <div class="junk-name">Price</div>
                    <div class="junk-size">$12</div>
                </li>
            </ul>
        </section>
        <section class="section">
            <div class="section-titles">
                <div class="section-title">Revenue By Year</div>
            </div>
            <ul class="monitor-list">
            <li class="monitor-list__item">
                    <div class="monitor-data">
                        <div class="monitor-type">2023</div>
                        <div class="monitor-date">$1k</div>
                    </div>
                    
                </li>
                <li class="monitor-list__item">
                    <div class="monitor-data">
                        <div class="monitor-type">2022</div>
                        <div class="monitor-date">$15K</div>
                    </div>
                    
                </li>
                <li class="monitor-list__item">
                    <div class="monitor-data">
                        <div class="monitor-type">2021</div>
                        <div class="monitor-date">$16k</div>
                    </div>
                    
                </li>
                <li class="monitor-list__item">
                    <div class="monitor-data">
                        <div class="monitor-type">2020</div>
                        <div class="monitor-date">$16k</div>
                    </div>
                    
                </li>
            </ul>
        </section>
    </article>
    <article class="dashboard-others">
       <section class="section">
            <div class="section-titles">
                <div class="section-title">
                    Statistics of Bookings
                </div>
            </div>
            <div class="statistics">
                <div class="progress-data">
                    <div class="progress-text">
                        <div class="progress-weekday">CURRENT/FRIDAY</div>
                        <div class="progress-rate">58%</div>
                    </div>
                </div>
                <ul class="progress-list">
                    <li class="progress-list__item">
                        <div class="weekday-abbr">
                            MO
                        </div>
                        <progress class="progress-bar" max="145" value="109"></progress>
                    </li>
                    <li class="progress-list__item">
                        <div class="weekday-abbr">
                            TU
                        </div>
                        <progress class="progress-bar" max="145" value="74"></progress>
                    </li>
                    <li class="progress-list__item">
                        <div class="weekday-abbr">
                            WE
                        </div>
                        <progress class="progress-bar" max="145" value="55"></progress>
                    </li>
                    <li class="progress-list__item active">
                        <div class="weekday-abbr">
                            TH
                        </div>
                        <progress class="progress-bar" max="145" value="118"></progress>
                    </li>
                    <li class="progress-list__item">
                        <div class="weekday-abbr">
                            FR
                        </div>
                        <progress class="progress-bar" max="145" value="90"></progress>
                    </li>
                    <li class="progress-list__item">
                        <div class="weekday-abbr">
                            SA
                        </div>
                        <progress class="progress-bar" max="145" value="116"></progress>
                    </li>
                    <li class="progress-list__item">
                        <div class="weekday-abbr">
                            SU
                        </div>
                        <progress class="progress-bar" max="145" value="72"></progress>
                    </li>
                </ul>
            </div>
        </section>
    </article>
</main>
</div>
			
		</div>
    </div>