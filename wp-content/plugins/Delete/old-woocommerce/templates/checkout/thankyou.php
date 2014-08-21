<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if ( $order ) : ?>

	<?php if ( in_array( $order->status, array( 'failed' ) ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<p><?php _e( 'Thank you. Your order has been received.', 'woocommerce' ); ?></p>

		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php _e( 'Thank you. Your order has been received.', 'woocommerce' ); ?></p>

<?php endif; ?>


    <?php include '/Users/shalinga/workspace/php/msl/wp-content/plugins/woocommerce/lib/LGPSClient.php'; ?>
    <?php include '/Users/shalinga/workspace/php/msl/wp-content/plugins/woocommerce/lib/lgps_config.php'; ?>

        <?php
        $service_code ="TEST20001";
        // if(isset ($_POST["serviceCode"])) {
        //     $service_code = $_POST["serviceCode"];
        // }
        
        $tx_amount = "10";
        
        // if(isset ($_POST["transactionAmount"])) {
        //     $tx_amount = $_POST["transactionAmount"];
        // }
        
        $tx_ref_no = "2386";
        
        // if(isset ($_POST["transactionRefNo"])) {
        //     $tx_ref_no = $_POST["transactionRefNo"];
        // }
                
        $return_url = "http://localhost/SamplePHPClient/response.php";
        
        $cipher_message = null;

        try {

            $lgpsClientReq = new LGPSClient(); //Create LGPSClient Object to handle Payment Request   
            
            $lgpsClientReq->setLogs(LOG_DIRECTORY_PATH,ENABLE_LOGS);
                     
            $lgpsClientReq->setClientPublicKey(CLIENT_PUBLIC_KEY); //Set Client Public Key
            $lgpsClientReq->setClientPrivateKey(CLIENT_PRIVATE_KEY, CLIENT_KEY_PASSWORD); //Set Client Private Key
            $lgpsClientReq->setLgpsPublicKey(LGPS_PUBLIC_KEY); //Set LGPS public Key
            
            $lgpsClientReq->setServiceCode($service_code); //Set Service code to Payment request
            $lgpsClientReq->setTransactionRefNo($tx_ref_no); //Set Transaction Ref No. to Payment request
            $lgpsClientReq->setTransactionAmount($tx_amount); //Set Transaction Amount to Payment request
            $lgpsClientReq->setReturnURL($return_url); //Set Return URL to Payment request

            $cipher_message = $lgpsClientReq->getPaymentRequest(); //Generate Encrypted Payment Request  
            
        } catch (LGPSException $e) {

            Logger::getInstance()->error($e->getMessage());
        }
        ?>


        <table width="517" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td valign="top">
                                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0"
                                                   cellpadding="0">
                                                       <?php if ($cipher_message != null) { ?>
                                                    <tr>
                                                        <td width="41%" height="25">&nbsp;</td>
                                                        <td width="4%" height="25">&nbsp;</td>
                                                        <td width="40%" height="25">
                                                            <div align="left" class="ctrlFonts  style3"></div></td>
                                                        <td width="32%"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="41%" height="25">&nbsp;</td>
                                                        <td width="4%" height="25">&nbsp;</td>
                                                        <td width="40%" height="25">
                                                            <div align="left" class="ctrlFonts  style3">
                                                                <strong>Service Code</strong>
                                                            </div></td>
                                                        <td width="32%"><b>:</b> &nbsp;&nbsp;<?php echo $service_code ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="41%" height="25">&nbsp;</td>
                                                        <td width="4%" height="25">&nbsp;</td>
                                                        <td width="40%" height="25">
                                                            <div align="left" class="ctrlFonts">
                                                                <strong>Transaction Amount</strong>
                                                            </div></td>
                                                        <td width="32%"><b>:</b> &nbsp;&nbsp;<?php echo $tx_amount ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="41%" height="25">&nbsp;</td>
                                                        <td width="4%" height="25">&nbsp;</td>
                                                        <td width="40%" height="25">
                                                            <div align="left" class="ctrlFonts">
                                                                <strong>Transaction Ref No</strong>
                                                            </div></td>
                                                        <td width="32%"><b>:&nbsp;&nbsp;</b><?php echo $tx_amount ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="41%" height="25">&nbsp;</td>
                                                        <td width="4%" height="25">&nbsp;</td>
                                                        <td width="40%" height="25">&nbsp;</td>

                                                        <form method="post" action="https://testlgps.lankagate.gov.lk:9443/lgps/accesslgps">
                                                            <INPUT TYPE="hidden" Value="<?php echo $cipher_message; ?>"
                                                                   name="clientPaymentRequest">
                                                        
                                                        <td width="32%" height="35"><input type="submit"
                                                                                           name="Pay" value="Pay">
                                                        </td></form>

                                                    </tr>
                                                    <?php }  else { ?>
                                                    <tr>
                                                        <td class="error">
                                                            <?php
                                                                echo "<br><br>
                                                                Error in creating Encrypted Payment Request!<br>
                                                                Please check the log files to view the error details.
                                                                <br><br>";
                                                        
                                                            ?> 
                                                    </td>
                                                </tr>
                                               <?php }
                                                        ?> 

                                </table></td>
                        </tr>
                    </table></td>
            </tr>
        </table></td>
</tr>
</table>