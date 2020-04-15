WooCommerce Availability Chart
===============================


## Description

This plugin will provide an "Availability Chart" on the product page. The chart will be a graphic representation of how many items are left in stock for the variation.  

### Admin changes

On the Edit Product page when the product type is set to Variable add an option on the General tab for "Availability Chart"

Location of option:
![option](http://f.cl.ly/items/0F1S0P1F0k451N1M3X2C/Edit%20Product%20%E2%80%B9%20WooCommerce%20Test%20%E2%80%94%20WordPress-1.jpg)

When the option is enabled the availability chart will be displayed on the product page. 

### Frontend changes

On the product page, the availability chart will show below the main content. You can use the action `do_action( 'woocommerce_after_main_content' )` to add the template. 

Please put the HTML for the availability chart into its own template inside of this plugin. 
You can use something like this (http://www.maxdesign.com.au/articles/percentage/) to 
style the bars, or use any other technique you want. 

Example of availability chart
![availability chart](http://f.cl.ly/items/1X0L2u0q1i2w0I090v2v/SLOW%20&%20STEADY%20(HEATHER%20BLACK)%20%7C%20Ugmonk.jpg)




