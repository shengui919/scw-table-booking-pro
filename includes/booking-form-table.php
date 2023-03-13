<div class="col-md-2 col-sm-6 col-xs-6 column1">
										<?php
										foreach ($tables as $key => $table) {
											$seats = explode(",", $table->seats);
											$seatsC = count($seats);
											$arrNumb = range(1, $seatsC);
											$even = range(0, count($arrNumb), 2);
											$add = range(1, count($arrNumb), 2);

										?>
											<div class="table-<?= $arrayClass[$key] ?> table-list">
												<div class="chart-status">
													<h2><i class="fa-solid fa-plus-large"></i>Booked</h2>
												</div>
												<div class="chart-left">
													<?php foreach ($add as $i => $a) {
														if ($a > 0) { ?>
															<div id="seat-<?= $a ?>" data-seat="<?= $a ?>" class="chart1 <?= $price->type ?> selected-color"><?= $a ?>
																<i class="fa fa-check-circle"></i>
															</div>
													<?php }
													} ?>
												</div>
												<div data-id="<?= $table->id ?>" data-name="<?= $table->label ?>" class="name-table <?= $price->type ?>">
													<h2><i class="fa fa-check-circle"></i><?= $table->label ?></h2>
												</div>
												<div class="chart-right">
													<?php foreach ($even as $i => $a) {
														if ($a > 0) { ?>
															<div id="seat-<?= $a ?>" data-seat="<?= $a ?>" class="chart1 <?= $price->type ?> selected-color"><?= $a ?>
																<i class="fa fa-check-circle"></i>
															</div>
													<?php }
													} ?>
												</div>

											</div>
										<?php  } ?>
									</div>
