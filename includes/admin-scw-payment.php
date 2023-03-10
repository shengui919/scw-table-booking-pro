	
	<div class="wrap">
        <div class="scwatbwsr_content">
            <div><?=settings_errors()?></div>
        </div>
        <div class="scwatbwsr_content mb-3">
        <?php adminMenuPage()?>
        </div>
		<div class="scwatbwsr_content">
		<section>
													<div class="scwatbwsr_content mb-3">
														
														<div class="content">
									<form action='options.php' method='post'>
										<?php 
										settings_fields( 'pluginSCWTBWSRPage' );
										
	$settings_fields= [
		'enabled' => [
			'title'   => __('Enable/Disable', 'woocommerce'),
			'type'    => 'checkbox',
			'label'   => __('Enable Reservations Charge', 'woocommerce'),
			'default' => 'no',
		],
		
	
		'api_key' => [
			'title'       => __('API Key', 'woocommerce'),
			'type'        => 'text',
			'description' => __('Please enter your IPPayware Portal API Key; this is needed in order to take payment.', 'woocommerce'),
			'desc_tip'    => true
		],
		'api_secret' => [
			'title'       => __('API Secret', 'woocommerce'),
			'type'        => 'text',
			'description' => __('Please enter your IPPayware Portal API Secret Token; this is needed in order to take payment.', 'woocommerce'),
			'desc_tip'    => true
		]
	];
	?>


						<div class="scwatbwsr_content">	
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?=$settings_fields['enabled']['title']?></p>
								</div>
								<div class="general-setting-right">
									<!-- <input type="checkbox"> -->
									<label class="sap-admin-switch">
										<input name="scwatbwsr_settings[enabled_payment]" <?php if(@$options['enabled_payment']=="on") echo "checked='true'";?> type="checkbox" class="sap-admin-option-toggle">
										<span class="sap-admin-switch-slider round"></span>
									</label>
									<p><?=$settings_fields['enabled']['label']?></p>
								</div>
							</div>
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Enable Offline Payment','scwatbwsr-translate')?></p>
								</div>
								<div class="general-setting-right">
									<!-- <input type="checkbox"> -->
									<label class="sap-admin-switch">
										<input name="scwatbwsr_settings[offline_payment]" <?php if(@$options['offline_payment']=="on") echo "checked='true'";?> type="checkbox" class="sap-admin-option-toggle">
										<span class="sap-admin-switch-slider round"></span>
									</label>
									<p><?php echo __('Customer can pay offline  in restaurants','scwatbwsr-translate')?></p>
								</div>
							</div>
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Online Payment Paritial %','scwatbwsr-translate')?></p>
								</div>
								<div class="general-setting-right">
									<input type="number"  min="1" max="99" value="<?=@$options['pay_later']?>" name="scwatbwsr_settings[pay_later]" class="require-deposit-scw">
									<p><?php echo __('OnlinePayment paritial %,user can pay offline or online in restaurants','scwatbwsr-translate')?></p>
								</div>
							</div> 
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Table Choose by customer','scwatbwsr-translate');?></p>
								</div>
								<div class="general-setting-right">
									<div class="form-radio-scw-per">
											<input type="radio" id="reservation" <?php if(@$options['customer_table']=="yes") echo "checked='true'";?> name="scwatbwsr_settings[customer_table]" value="yes">
											<label for="html">Yes</label>
										</div>
										<div class="form-radio-scw-per">
											<input type="radio" id="guest" <?php if(@$options['customer_table']=="no") echo "checked='true'";?> name="scwatbwsr_settings[customer_table]" value="no">
											<label for="css">No</label>
										</div>
									<p><?php echo __('Table choose by, per customer or Manager','scwatbwsr-translate')?>?</p>
								</div>
							</div>
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?=$settings_fields['api_key']['title']?></p>
								</div>
								<div class="general-setting-right">
									<input type="text" value="<?=$options['api_key']?>" name="scwatbwsr_settings[api_key]" class="require-deposit-scw">
									<p><?=$settings_fields['api_key']['description']?></p>
								</div>
							</div> 
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?=$settings_fields['api_secret']['title']?></p>
								</div>
								<div class="general-setting-right">
									<input type="text"  value="<?=$options['api_secret']?>" name="scwatbwsr_settings[api_secret]" class="require-deposit-scw">
									<p><?=$settings_fields['api_secret']['description']?></p>
								</div>
							</div> 
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Ippayware  Comission','scwatbwsr-translate')?></p>
								</div>
								<div class="general-setting-right">
									<input type="text"  value="<?=$options['commission']?>" name="scwatbwsr_settings[commission]" class="require-deposit-scw">
									<p>What deposit amount is required (either per reservation or per guest, depending on the setting above)? Minimum is $0.50 in most currencies.</p>
								</div>
							</div>
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Twilio SID','scwatbwsr-translate')?></p>
								</div>
								<div class="general-setting-right">
									<input type="text" value="<?=@$options['twilio_sid']?>" name="scwatbwsr_settings[twilio_sid]" class="require-deposit-scw">
									<p><?php echo __('Twilio SID from twilio account','scwatbwsr-translate')?></p>
								</div>
							</div> 
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Twilio Secert Key','scwatbwsr-translate')?></p>
								</div>
								<div class="general-setting-right">
									<input type="text"  value="<?=@$options['twilio_secert']?>" name="scwatbwsr_settings[twilio_secert]" class="require-deposit-scw">
									<p><?php echo __('Twilio Secert Key from twilio account','scwatbwsr-translate')?></p>
								</div>
							</div>
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Account SID','scwatbwsr-translate')?></p>
								</div>
								<div class="general-setting-right">
									<input type="text"  value="<?=@$options['twilio_account_sid']?>" name="scwatbwsr_settings[twilio_account_sid]" class="require-deposit-scw">
									<p><?php echo __('Twilio Account SID from twilio account','scwatbwsr-translate')?></p>
								</div>
							</div> 
							<div class="general-setting-scw">
								<div class="general-setting-left">
									<p><?php echo __('Twilio Number','scwatbwsr-translate')?></p>
								</div>
								<div class="general-setting-right">
									<input type="text" value="<?=@$options['twilio_number']?>" name="scwatbwsr_settings[twilio_number]" class="require-deposit-scw">
									<p><?php echo __('Send ID Number','scwatbwsr-translate')?></p>
								</div>
							</div> 
							
						</div>
						<?php

		submit_button();
			?>
		</form> 
									</div> 
													</div>	
                                                </section>						
							
		</div>
	</div>
				
				
		

