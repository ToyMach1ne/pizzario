<?php
/**
 * Email Footer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

													</td>
												</tr>
											</table>
											<!-- End Content -->
										</td>
									</tr>
								</table>
								<!-- End Body -->
							</td>
						</tr>
						
						<?php
						$footer_text	= get_option( 'ec_supreme_all_footer_text' );
						$footer_image	= get_option( 'ec_supreme_all_footer_left_image' );
						$nav_bar		= ec_nav_bar();
						
						if ( $footer_image && ( $footer_text || $nav_bar ) ) :
							?>
							<tr>
								<td align="center" valign="top">
									
									<!-- Footer -->
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="50%" align="left" class="footer_container" >
												
												<table align="left" cellpadding="0" cellspacing="0" border="0" width="auto">
													<tr>
														<td align="left" class="footer_container_inner">
															
															<img src="<?php echo $footer_image; ?>" />
															
														</td>
													</tr>
												</table>
												
											</td>
											<td width="100%" align="right" class="footer_container" >
												
												<table align="right" cellpadding="0" cellspacing="0" border="0" width="auto">
													<tr>
														<td align="right" class="footer_container_inner bottom-nav">
															
															<?php echo $footer_text; ?>
															
															<?php echo ec_nav_bar(); ?>
															
														</td>
													</tr>
												</table>
												
											</td>
										</tr>
									</table>
									<!-- End Footer -->
									
								</td>
							</tr>
							<?php
						elseif ( $footer_text || $nav_bar ):
							?>
							<tr>
								<td align="center" valign="top">
									
									<!-- Footer -->
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="100%" align="center" class="footer_container" >
												
												<table align="center" cellpadding="0" cellspacing="0" border="0" width="auto">
													<tr>
														<td align="center" class="footer_container_inner bottom-nav">
															
															<?php echo $footer_text; ?>
															
															<?php echo ec_nav_bar(); ?>
															
														</td>
													</tr>
												</table>
												
											</td>
										</tr>
									</table>
									<!-- End Footer -->
									
								</td>
							</tr>
							<?php
						endif;
						?>
						
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
