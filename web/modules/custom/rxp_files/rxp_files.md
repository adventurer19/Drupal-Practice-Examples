# Managing attachment display
Once a file has been attached to content, you can specify whether it will be
displayed in the list of attached fiels or not.
Listed files are displayed automatically in a section at the b uttom of the content;
non-listed files can be example be embeedded in your content, but are not inlcluded in the list
Embeddeing a file in your content means you copy the path of the file manually enbed it where you want
for example, to insert the content as a link tag.
Note that the text format filterd html by defaulkt refuses any images tags.
Additional options for managing the display of the file list are available
in the manage display tab of the specific content type's administration page

# Managing file locations and access
When you create a file field, you can specify the sub-directory of the site's
file system where uploaded files for this content type will be stored.
The site's file system paths are defined on the File system page
Administer > Configuration > Media > File system

You can also specify whether files are stored in a public directory or in a 
private file storage area.
Files in the public directory can be accessed directly through the web server
when public files are listed , direct links to the files are used and anyone who knows a file's url
can download the file.
Files in the private directory are not accessible directly through the web server.
when private files are listed , the links are Drupal path requests
(for example, "/system/files/name-of-the-file.pdf)"; here,
'system/files' is not an actually folder in the filesystem whose content are served by the web server,
but instead is a virtual URL managed by Drupal through which the private files can be downloaded)
This adds to server load and download time, since Drupal must resolve the path for each file download request,
but allows for access restrictions to be added.

The best practice for public files is to store them in the multi-side directory like :
sites/default/files

The default way to securely add a private directory for your files is to use a 
directory that can not be accesd directly by your web server, but can be accessed
by Drupal. Ideally this directory should be located outside of your Drupal root folder.

The simple way to add a private directory for your files is to create a sub-directory
under the public directory like

/sites/default/files/private

When you specify the private directory in the admin/config/media/files-system
it will automatically create the sub-directory and create a simple
.htaccess file which deny from all

Whenever possible it's recommended that you choose a directory located outside of 
your Drupal root folder ( or acutally outside your web root) 
which may be tricky if you are on a shared host. If you do have access thought, you can 
choose a private directory which will be on the same level as your web root directory
often called public_html or www similar 
Note that if you are using open_basedir feature in your php settings the private choosen directory 
must be added in the open_basedir allowed dir. Also when using open_basedir and it needfs to create directories 
there , you may hit the issue tracked in the impossiblity of recursive flag when creating directories,
so you must create the needed directories manuall.y

Accessing Private files

It is important to understand that just because a file field is configured to use the 
private file system , that does not mean Drupal will prevent anyone from viewing 
files uploaded via that field