# TODO: Update Payment Controller to Use PayPalService

## Pending Tasks
- [ ] Update app/config/payment.php to set 'gateway' => 'paypal'
- [ ] Modify Payment.php constructor to load PayPalService instead of PaymongoService
- [ ] Update create_room_source method to create PayPal orders instead of PayMongo sources
- [ ] Update create_tour_source method to create PayPal orders instead of PayMongo sources
- [ ] Update handle_room_payment_success to capture PayPal orders instead of creating PayMongo payments
- [ ] Update handle_tour_payment_success to capture PayPal orders instead of creating PayMongo payments
- [ ] Remove PayMongo-specific private methods (paypal_get_access_token, paypal_get_order)
- [ ] Update session variable names from paymongo_* to paypal_*
- [ ] Remove or adapt PayMongo-specific code in success handlers
- [ ] Test the updated payment flow
