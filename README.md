# FME - Front End Multi Uploader (Wordpress Plugin)
<strong>Use:</strong> Creates a form that lets users create a new 'gallery' post in Wordpress, which also lets them upload multiple images at once into a repeating image field created by Wordpress Types. Form optimized for Bootstrap 3. 

<h2>Shortcode to display form:</h2>
<code>[gallery_post_form]</code>

<h2>Description</h2>

This plugin was made to solve the painful process of manually adding each image, one at a time when using Types, by offering a way to add an entire gallery of images at once, from the frontend. 


Developed on the basis of <a href="https://gist.github.com/daltonrooney/1737887">Dalton Rooney's file-upload-handler</a> 


<em>*There are many fixed values and fields in this plugin, so if something isn't working, please open up the source code and see if your Wordpress settings match up. Hopefully we can improve this by adding more settings into the WP Admin.</em>


<strong>**This plugin is likely missing security features, so please use conditional display to only let logged-in users with high permissions use the form in order to upload a gallery post. Further coding is needed to add nonces and verifications for use by guests or other users. With Toolset Access, this is very easy as you can simply wrap the shortcode in an Access display shortcode! Any suggestions are greatly appreciated!</strong>

<h2>Requirements:</h2>
1. Toolset Types Plugin
2. 'Gallery' (slug = gallery) custom post type with custom fields:
      a) photographer (wpcf-photographer) - A custom field for entering the photographer of the gallery you are adding
      b) gallery-image (wpcf-gallery-image) - A REPEATING custom field for uploading gallery images. In Types, you must select "allow multiple instances of this field" when creating the field.
3. Bootstrap 3+ - Not necessarily a requirement, but the form includes classes to provide better display with Bootstrap.
4. JQuery - Should be enabled on Wordpress anyways
