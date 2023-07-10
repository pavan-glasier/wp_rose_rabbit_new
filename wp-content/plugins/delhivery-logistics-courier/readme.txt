=== Delhivery Logistics Courier ===

Contributors: Rajlakshmi
donate link: www.delhivery.com
Tags: delhivery logistics courier
Requires at least: 4.9
Tested up to: 6.1.1
Requires PHP: 5.6
Stable tag: 1.0.107
License: GPLv3

== Description ==
Delhivery is one of India's largest e-commerce logistics companies that offers you access to ship to over 17000+ pin-codes.

The Delhivery Seller App becomes a natural extension for your Woocommerce storefront as it allows you to access all logistics & shipping related tasks within the same window.

Delhivery App offers the following features:

- Signup and Login - Existing Delhivery users (CL Panel Clients) can login to the plugin using their Cl Panel existing credentials and start shipping. New users can sign-up on the fly in 10 min and start shipping orders.
- Warehouse Management - Create new or integrate your existing pickup points for your business
- Order Management - Track all your Forward and Reverse shipments seamlessly
- Rate Calculator - Quickly check shipping tariff for your orderszz
- Pickup Request - Once your shipments are ready to be picked up, you can schedule a pickup request for the Delhivery executive to come and pick up the shipments from your warehouse or pickup point.
- Single and Bulk orders Shipping: Ship a single order at a time or ship bulk orders at one go seamlessly using this plugin.

Terms and Conditions: https://www.delhivery.com/terms-and-conditions/
Privacy Policy: https://www.delhivery.com/privacy-policy/



== Installation ==

### Manual Install From WordPress Dashboard

1. Login to your site's admin panel and navigate to Plugins -> Add New -> Upload.
2. Click choose file, select the plugin file and click install

== Frequently Asked Questions ==

= How do I manage my orders from the Delhivery Woo-commerce Plugin?

Go to My orders tab, you can simply edit, track or cancel orders.Print packing slips option is also available on the same page.

= How do I search for my package status?

You can go to My Orders and select 'Track Order' from the Actions tab.
The actions available on the 'Action' button will change. There would be the following "Four" fields visible (Edit, Cancel, Track and Shipping Label) in dropdown. On clicking on 'Cancel', the details of that order will no longer be visible.

= How will I know the estimated cost of my Package?

On the Rate Calculator Tab sellers can enter the origin, destination pin-code, shipping mode, COD amount, weight (higher of actual or volumetric) and check the Delhivery shipping charges.

= How do I Create Warehouses?

Warehouses are the pickup points from where Delhivery would be picking up your shipments. Each seller can have one or more warehouses or pickup points.

From the menu--> Select Warehouse option, you can create new warehouses or view your existing warehouses.

Click on the "Add New" button to create a new warehouse. Fields which are marked with "*" are mandatory and if you try to save without adding the mandatory fields there will be an error message shown.

= I have created an order. Will it be picked up automatically?

No,You will have to create a pickup request for Delhivery to assign an executive to pick up your shipments.You can do that through the app.

= Can I charge different shipping costs to different consignees?

Yes, you can enter shipping cost for your orders which is to be passed on to the consignee based on your business model.

== Features ==

= Signup and Login?
Register for an account by clicking on Create an account or by visiting cl.delhivery.com/signup. After receiving your username and password you can login into your delhivery account and access the dashboard

=Waybill
You can use this section to fetch new waybill
 
= Rate Calculator
You can use the rate calculator section to get the estimated delivery rate by entering the destination pincode, origin pincode, weight, shipment mode, shipment status and payment mode.

= List Warehouses / My Warehouse
You can list all the warehouses linked to your account under this section.

= Create Warehouses
You can create warehouses by entering the mandatory fields on the Create Warehouse Section which are used for your orders.

= List of Orders
You can take a look at your existing orders by navigating to the My Orders section. Here you can search for your orders as well as print your packaging slip and track orders.

= Create Pickup Request
You can create pickup requests from your warehouses by filling in the pickup location, expected package count and the date and time of pickup.


== Service Documentation ==
= Login
This API is used to connect your account, and get a token which will be used by subsequent Delhivery APIs.
URL: https://api-ums.delhivery.com/login/
Method:POST  
Payload: {  "username":"Username",  "password":"Password"}

=Create Pickup
This API is used in the Create Pickup section
URL: https://track.delhivery.com/fm/request/new/
Method: POST  
Payload: {"pickup_location":"","pickup_time":"", "pickup_date":"", "expected_package_count":""}

=Create Warehouse
This API is used to create a warehouse for your Account
URL: https://track.delhivery.com/api/backend/clientwarehouse/create/
Method: POST  
Payload: {"phone": "10DigitPhone", "city": "city", "name": "warehouse name", "pin": "pincode","address": "address","country": "country","contact_person": "name","email": "abc@gmail.com", "registered_name": "registered username"}

=Edit Warehouse
This API is used to edit the details of your existing warehouse
URL: https://track.delhivery.com/api/backend/clientwarehouse/edit/
Method: POST  
Payload: { "phone": "10DigitPhone", "address": "address", "name": "Warehouse Name","registered_name": "Name", "pin": "pincode"}

=Fetch Waybill
This API is used to fetch a list of waybills for your account
URL: https://track.delhivery.com/api/wbn/bulk.json?count=1
Method: GET  

= Refresh Token
This API is used to refresh your token in case your initial login token is expired.
URL: https://api-ums.delhivery.com/v2/refresh_token/
Methd: GET  

= Rate Calculator
This AI is used to get estimated charges
URL: https://track.delhivery.com/api/kinko/v1/invoice/charges/.json?cl='client_name'&ss='shipment_status'&md='shipment_mode'&pt='payment_mode'&d_pin='d_pincode'&o_pin='o_pincode'&cgm='wgt_in_gram';
Method: GET

= Create Order/Order Manifest
This API is used to manifest an order
URL: https://track.delhivery.com/api/cmu/create.json
Method: POST  
Payload:{"pickup_location":{"pin":"pincode","add":"address","phone":"phone","state":"state","city":"city","country":"","name":"name"},"shipments":[{"return_name":"","return_pin":"","return_city":"","return_phone":"","return_add":"","return_state":"","return_country":"","order":"UniqueIdentifier","phone":"phone","products_desc":"Example","product_type":"","cod_amount":"","name":"Name","waybill":"","country":"","order_date":null,"payment_mode":"prepaid","total_amount":0"seller_add":"addr","seller_cst":"","add":"add","seller_name":"Name","seller_inv":"0","seller_tin":"","seller_inv_date":"date","pin":"pincode","quantity":1,"weight":null,"state":"state","city":"City","supplier":"","extra_parameters":"","shipment_width":"","shipment_height":"","consignee_tin":"","tax_value":"","sales_tax_form_ack_no":"","category_of_goods":"","commodity_value":"","e_waybill":null,"source":"Woocommmerce"}]}


= Edit Order
URL: https://track.delhivery.com/api/p/edit
Method: POST
Payload: {"tax_value":"","product_category":"","waybill":"wbn","consignee_tin":"tin","name":"Name","phone":"phone","product_details":"Sample","add":"Address","commodity_value":"","gm":0}

= Track Order
This api is used to track the status of the order
URL: https://track.delhivery.com/api/packages/json/?token=API_Token&waybill=WBN&verbose=1
Method: GET

= Check Pincode serviceability
URL:   https://track.delhivery.com/c/api/pin-codes/json/?filter_codes=validPincode
Method: GET

= Get Warehouse Status
This API is used to get the status of a Warehouse
URL: https://track.delhivery.com/api/backend/clientwarehouse/status/
Method: POST
Payload: {"name": "name"}

= Packaging Slip
This API is used to generate a packing slip for your needed WBN
URL: https://track.delhivery.com/api/p/packing_slip/?wbns=<WBN>
Method: GET

= Get User wallet Details
URL: https://cl-api.delhivery.com/user/
This API is used to get the wallet details of a user
Method: GET

= Get Wallet Balance
This API is used to get the wallet balance of user
URL: https://api-bird.delhivery.com/proxy/wallet/<ID>
Method: GET  

== Screenshots ==

1. Login.png
2. RateCalculator.png
3. WaybillList.png
4. AddWarehouse.png
5. WarehousesList.png
6. OrdersList.png
7. CreatePickupRequest.png
8. FAQ.png

== Upgrade Notice ==

nothing

== Changelog ==

nothing

= 1.0.52 - 03/Nov/2020 =

* FIX: Fix bug of cancel order

= 1.0.53 - 04/Nov/2020 =

* FIX: Remove Delivered label from Filter from Order list

= 1.0.54 - 05/Nov/2020 =

* FIX: Packing slip design issue

= 1.0.55 - 18/Nov/2020 =

* FIX: Order listing issue

= 1.0.57 - 18/Nov/2020 =

* FIX: Order listing issue

= 1.0.58 - 18/Nov/2020 =

* FIX: Order listing issue on bulk order page

= 1.0.58 - 27/Nov/2020 =

* FIX: Function name conflict on Awb number listing

= 1.0.60 - 29/Nov/2020 =

* FIX: sql query optimization

= 1.0.61 - 29/Nov/2020 =

* FIX: sql query optimization on edit order page

= 1.0.62 - 04/Dec/2020 =

* FIX: tracking link update issue

= 1.0.63 - 04/Dec/2020 =

* FIX: change pagination

= 1.0.64 - 06/Jan/2021 =

* FIX: db prefix issue

= 1.0.65 - 06/Jan/2021 =

* FIX: db prefix issue

= 1.0.66 - 28/Jan/2021 =

* FIX: Change function name

= 1.0.67 - 03/Mar/2021 =

* FIX: version update

= 1.0.68 - 08/Mar/2021 =

* FIX: code optimize


= 1.0.69 - 08/Mar/2021 =

* FIX: code optimize

= 1.0.70 - 10/Mar/2021 =

* FIX: duplicate order create issue

= 1.0.71 - 10/Mar/2021 =

* FIX: Issue in searching

= 1.0.72 - 10/Mar/2021 =

* FIX: Code Optimization

= 1.0.73 - 21/April/2021 =

* FIX: list Pending order

= 1.0.74 - 26/April/2021 =

* FIX: resolve css issue

= 1.0.75 - 13/May/2021 =

* FIX: Error message text changes

= 1.0.76 - 13/May/2021 =

* FIX: shipping address issue

= 1.0.77 - 19/May/2021 =

* FIX: shipping address issue

= 1.0.78 - 19/May/2021 =

* FIX: Remove pending orders from order list

= 1.0.79 - 19/May/2021 =

* FIX: calculation of weight for variation product

= 1.0.80 - 19/May/2021 =

* FIX: Send weight in manifest payload in gram

= 1.0.80 - 23/Sep/2021 =

* FIX: Order counting issue

= 1.0.82 - 1/Dec/2021 =

* FIX: Show creation date on order page

= 1.0.83 - 1/Feb/2022 =

* FIX:  remove 'woo' from your domain name


= 1.0.84 - 2/Feb/2022 =

* FIX:  test up to latest wordpress version 5.9

= 1.0.85 - 4/Feb/2022 =

* FIX:  sanitize code

= 1.0.86 -11/Feb/2022 =

* FIX:  Updated endpoint to latest stable endpoint.

= 1.0.87 -18/Feb/2022 =

* FIX:  Earlier, the Delhivery Owned Endpoint was not mapped to a proper CNAME, the service name (hosted on AWS) was used. We have now replaced it with the correct CNAME endpoint. From https://u8rmsd966f.execute-api.ap-southeast-1.amazonaws.com/prod to https://api-bird.delhivery.com/proxy/.

= 1.0.88 -23/Feb/2022 =

* FIX:  Added updated screenshots and added description for services

= 1.0.89 -02/Mar/2022 =

* FIX:  Updated Description, added Terms and Condition and Privacy Links, Added basic API documentation.

= 1.0.90 -03/Mar/2022 =

* FIX:  Fixes order page design issue

= 1.0.91 -03/Mar/2022 =

* FIX:  js issue

= 1.0.92 -03/Mar/2022 =

* FIX:  js issue

= 1.0.93 -03/Mar/2022 =

* FIX:  Add CSS/JS Folder

= 1.0.94 -03/Mar/2022 =

* FIX:  rename js file

= 1.0.95 -15/Mar/2022 =

* FIX:  PHP session not closed interfering with loopback issue


= 1.0.96 -15/Mar/2022 =

* FIX:  PHP session not closed interfering with loopback issue

= 1.0.97 -12/May/2022 =

* FIX:  issue in picking weight unit for woocommerce product

= 1.0.98 -17/May/2022 =

* FIX:  Check compatiblity with latest upcoming wordpress version 6.0

= 1.0.99 -03/June/2022 =

* FIX:  Change text on Login page.


= 1.0.100 -03/June/2022 =

* FIX:  Remove duplicate billing and shipping address from woocommerce orders listing

= 1.0.101 -13/July/2022

* FIX: Remove notices when fetching product details

= 1.0.102 -10/Aug/2022

* FIX: Edit order issue fix

= 1.0.103 -05/Sep/2022

* FIX: Tracking Order api url changes

= 1.0.104 -04/Oct/2022

* FIX: Content changes on Login page

= 1.0.105 -14/Oct/2022

* FIX: Content changes on Login page

= 1.0.106 -23/Oct/2022

* FIX: Session issue

= 1.0.107 -1/Dec/2022

* FIX: compatible with latest wordpress version 6.1.1



